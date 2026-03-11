<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::with('assignedSales');

        if ($s = $request->search) {
            $query->where(fn($q) => $q->where('name','like',"%$s%")
                ->orWhere('phone','like',"%$s%")->orWhere('email','like',"%$s%")
                ->orWhere('contact_person','like',"%$s%"));
        }
        if ($request->status)   $query->where('status',   $request->status);
        if ($request->priority) $query->where('priority', $request->priority);

        $clients = $query->latest()->paginate(15)->withQueryString();
        $stats   = [
            'total'   => Client::count(),
            'active'  => Client::where('status','active')->count(),
            'pending' => Client::where('status','pending')->count(),
            'closed'  => Client::where('status','closed')->count(),
            'revenue' => Client::where('status','active')->sum('monthly_value'),
        ];
        return view('admin.clients.index', compact('clients','stats'));
    }

    public function create()
    {
        $services       = Service::where('is_active',true)->orderBy('name')->get();
        $salesEmployees = User::orderBy('name')->get();
        return view('admin.clients.create', compact('services','salesEmployees'));
    }

    public function store(Request $request)
    {
        // ── Validate only the client fields ──────────────────────
        // NOTE: service_quantities & service_distribute are NOT included here
        // to avoid "Array to string conversion" — they're handled in syncServices()
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'contact_person'    => 'nullable|string|max:255',
            'phone'             => 'nullable|string|max:30',
            'email'             => 'nullable|email|max:255',
            'whatsapp'          => 'nullable|string|max:30',
            'address'           => 'nullable|string',
            'industry'          => 'nullable|string|max:100',
            'notes'             => 'nullable|string',
            'status'            => 'required|in:active,pending,paused,closed',
            'priority'          => 'required|in:high,medium,low',
            'contract_start'    => 'nullable|date',
            'contract_end'      => 'nullable|date',
            'monthly_value'     => 'nullable|numeric|min:0',
            'assigned_sales_id' => 'nullable|exists:users,id',
        ]);

        $client = Client::create($data);
        $this->syncServices($client, $request);

        return redirect()->route('admin.clients.index')
            ->with('success', __('admin.created_successfully', ['item' => __('admin.client')]));
    }

    public function show(Client $client)
    {
        $client->load('assignedSales','clientServices.service','tasks.employees','commissions.employee.user');
        return view('admin.clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        $client->load('clientServices');
        $services           = Service::where('is_active',true)->orderBy('name')->get();
        $salesEmployees     = User::orderBy('name')->get();
        $clientServiceIds   = $client->services()->pluck('services.id')->toArray();
        return view('admin.clients.create', compact('client','services','salesEmployees','clientServiceIds'));
    }

    public function update(Request $request, Client $client)
    {
        // ── Same fix: only client-level fields in validate ────────
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'contact_person'    => 'nullable|string|max:255',
            'phone'             => 'nullable|string|max:30',
            'email'             => 'nullable|email|max:255',
            'whatsapp'          => 'nullable|string|max:30',
            'address'           => 'nullable|string',
            'industry'          => 'nullable|string|max:100',
            'notes'             => 'nullable|string',
            'status'            => 'required|in:active,pending,paused,closed',
            'priority'          => 'required|in:high,medium,low',
            'contract_start'    => 'nullable|date',
            'contract_end'      => 'nullable|date',
            'monthly_value'     => 'nullable|numeric|min:0',
            'assigned_sales_id' => 'nullable|exists:users,id',
        ]);

        $client->update($data);
        $this->syncServices($client, $request);

        return redirect()->route('admin.clients.index')
            ->with('success', __('admin.updated_successfully', ['item' => __('admin.client')]));
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('admin.clients.index')
            ->with('success', __('admin.deleted_successfully', ['item' => __('admin.client')]));
    }

    private function syncServices(Client $client, Request $request): void
    {
        $sync = [];
        foreach ($request->input('services', []) as $sid) {
            $sync[$sid] = [
                'price'             => $request->input("service_prices.$sid", 0),
                'details'           => $request->input("service_details.$sid"),
                'status'            => $request->input("service_statuses.$sid", 'active'),
                'start_date'        => $request->input("service_starts.$sid"),
                'monthly_quantity'  => (int) $request->input("service_quantities.$sid", 1),
                'distribute_weekly' => $request->boolean("service_distribute.$sid", true),
            ];
        }
        $client->services()->sync($sync);
    }
}