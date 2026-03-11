<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Contract;
use App\Models\ContractPayment;
use App\Models\Client;
use App\Models\Service;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $query = Contract::with('client')->latest();

        if ($s = $request->search) {
            $query->where('contract_number', 'like', "%$s%")
                  ->orWhereHas('client', fn($q) => $q->where('name', 'like', "%$s%"));
        }
        if ($request->status)    $query->where('status', $request->status);
        if ($request->client_id) $query->where('client_id', $request->client_id);

        $contracts = $query->paginate(15)->withQueryString();

        $stats = [
            'total'     => Contract::count(),
            'active'    => Contract::where('status', 'active')->count(),
            'completed' => Contract::where('status', 'completed')->count(),
            'overdue'   => ContractPayment::overdue()->count(),
            'pending_amount' => ContractPayment::pending()->sum('amount'),
        ];

        return view('admin.contracts.index', compact('contracts', 'stats'));
    }

    public function create(Request $request)
    {
        $clients  = Client::orderBy('name')->get();
        $services = Service::active()->orderBy('name')->get();
        // pre-select client if coming from client profile
        $selectedClient = $request->client_id
            ? Client::find($request->client_id)
            : null;

        return view('admin.contracts.create', compact('clients', 'services', 'selectedClient'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id'    => 'required|exists:clients,id',
            'start_date'   => 'required|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'total_amount' => 'required|numeric|min:0',
            'discount'     => 'nullable|numeric|min:0',
            'tax'          => 'nullable|numeric|min:0',
            'scope'        => 'nullable|string',
            'notes'        => 'nullable|string',
            'status'       => 'required|in:draft,active,completed,cancelled',
            // items
            'items'             => 'nullable|array',
            'items.*.description' => 'required_with:items|string',
            'items.*.service_id'  => 'nullable|exists:services,id',
            'items.*.quantity'    => 'nullable|numeric|min:0',
            'items.*.unit_price'  => 'nullable|numeric|min:0',
            // payments
            'payments'              => 'required|array|min:1',
            'payments.*.label'      => 'nullable|string',
            'payments.*.amount'     => 'required|numeric|min:0',
            'payments.*.due_date'   => 'required|date',
        ]);

        // حساب الصافي
        $total    = $data['total_amount'];
        $discount = $data['discount'] ?? 0;
        $tax      = $data['tax'] ?? 0;
        $net      = $total - $discount + $tax;

        $contract = Contract::create([
            'client_id'    => $data['client_id'],
            'created_by'   => auth()->id(),
            'start_date'   => $data['start_date'],
            'end_date'     => $data['end_date'] ?? null,
            'total_amount' => $total,
            'discount'     => $discount,
            'tax'          => $tax,
            'net_amount'   => $net,
            'scope'        => $data['scope'] ?? null,
            'notes'        => $data['notes'] ?? null,
            'status'       => $data['status'],
        ]);

        // حفظ البنود
        foreach ($request->input('items', []) as $item) {
            if (empty($item['description'])) continue;
            $qty   = $item['quantity']   ?? 1;
            $price = $item['unit_price'] ?? 0;
            $contract->items()->create([
                'service_id'  => $item['service_id'] ?? null,
                'description' => $item['description'],
                'quantity'    => $qty,
                'unit_price'  => $price,
                'total'       => $qty * $price,
            ]);
        }

        // حفظ الدفعات
        foreach ($request->input('payments', []) as $i => $payment) {
            $contract->payments()->create([
                'payment_number' => $i + 1,
                'label'          => $payment['label'] ?? null,
                'amount'         => $payment['amount'],
                'due_date'       => $payment['due_date'],
                'status'         => 'pending',
            ]);
        }

        return redirect()->route('admin.contracts.show', $contract)
            ->with('success', __('admin.created_successfully', ['item' => __('admin.contract')]));
    }

    public function show(Contract $contract)
    {
        $contract->load('client.assignedSales', 'items.service', 'payments.receipt', 'commissions', 'createdBy');
        return view('admin.contracts.show', compact('contract'));
    }

    public function edit(Contract $contract)
    {
        $contract->load('items', 'payments');
        $clients  = Client::orderBy('name')->get();
        $services = Service::active()->orderBy('name')->get();
        return view('admin.contracts.edit', compact('contract', 'clients', 'services'));
    }

    public function update(Request $request, Contract $contract)
    {
        $data = $request->validate([
            'client_id'    => 'required|exists:clients,id',
            'start_date'   => 'required|date',
            'end_date'     => 'nullable|date',
            'total_amount' => 'required|numeric|min:0',
            'discount'     => 'nullable|numeric|min:0',
            'tax'          => 'nullable|numeric|min:0',
            'scope'        => 'nullable|string',
            'notes'        => 'nullable|string',
            'status'       => 'required|in:draft,active,completed,cancelled',
        ]);

        $net = $data['total_amount'] - ($data['discount'] ?? 0) + ($data['tax'] ?? 0);
        $contract->update(array_merge($data, ['net_amount' => $net]));

        return redirect()->route('admin.contracts.show', $contract)
            ->with('success', __('admin.updated_successfully', ['item' => __('admin.contract')]));
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();
        return redirect()->route('admin.contracts.index')
            ->with('success', __('admin.deleted_successfully', ['item' => __('admin.contract')]));
    }

    // ═══════════════════════════════════════════════════════
    // تسجيل دفع + إنشاء سند قبض تلقائي
    // POST /admin/contracts/payments/{payment}/pay
    // ═══════════════════════════════════════════════════════
    public function markPaymentPaid(Request $request, ContractPayment $payment)
    {
        if ($payment->status === 'paid') {
            return back()->with('error', __('admin.already_paid'));
        }

        $data = $request->validate([
            'paid_at'        => 'required|date',
            'payment_method' => 'required|string|max:50',
            'reference'      => 'nullable|string|max:100',
            'notes'          => 'nullable|string',
        ]);

        $data['issued_by'] = auth()->id();
        $receipt = $payment->markAsPaid($data);

        return redirect()
            ->route('admin.contracts.show', $payment->contract_id)
            ->with('success', __('admin.payment_recorded') . ' — ' . $receipt->receipt_number);
    }

    // ═══════════════════════════════════════════════════════
    // طباعة سند القبض
    // GET /admin/receipts/{receipt}/print
    // ═══════════════════════════════════════════════════════
    public function printReceipt(\App\Models\PaymentReceipt $receipt)
    {
        $receipt->load('contractPayment.contract.client', 'issuedBy');
        return view('admin.contracts.receipt-print', compact('receipt'));
    }
}