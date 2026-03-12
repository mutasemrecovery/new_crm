@extends('layouts.admin')
@section('title', $contract->contract_number)

@push('styles')
<style>
:root{--g:linear-gradient(135deg,var(--accent),#8b7eff)}
.page-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap}
.page-header h1{font-family:'Syne',sans-serif;font-size:22px;font-weight:800;margin-bottom:6px}
.btn{display:inline-flex;align-items:center;gap:7px;border-radius:10px;padding:9px 18px;font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .2s;font-family:inherit;border:none;white-space:nowrap}
.btn-primary{background:var(--g);color:#fff;box-shadow:0 4px 14px rgba(108,99,255,.3)}.btn-primary:hover{opacity:.88;color:#fff}
.btn-ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border)}.btn-ghost:hover{border-color:var(--accent);color:var(--accent)}
.btn-success{background:rgba(67,233,123,.15);color:#43e97b;border:1px solid rgba(67,233,123,.3)}.btn-success:hover{background:rgba(67,233,123,.25)}
.btn-sm{padding:6px 12px;font-size:12px;border-radius:8px}

/* KPIs */
.kpi-strip{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px}
@media(max-width:800px){.kpi-strip{grid-template-columns:1fr 1fr}}
.kpi{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:18px 20px}
.kpi-val{font-family:'Syne',sans-serif;font-size:24px;font-weight:800}
.kpi-lbl{font-size:11px;color:var(--muted);margin-top:3px;text-transform:uppercase;letter-spacing:.5px}

/* Progress bar */
.prog-wrap{background:var(--surface2);border-radius:99px;height:6px;overflow:hidden;margin-top:8px}
.prog-fill{height:100%;background:var(--g);border-radius:99px;transition:width .4s}

/* Layout */
.detail-layout{display:grid;grid-template-columns:320px 1fr;gap:20px}
@media(max-width:1000px){.detail-layout{grid-template-columns:1fr}}
.card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:22px;margin-bottom:18px}
.card:last-child{margin-bottom:0}
.card-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--border)}
.card-title{font-family:'Syne',sans-serif;font-size:14px;font-weight:700;display:flex;align-items:center;gap:8px}
.card-title i{color:var(--accent);font-size:13px}
.dl-row{display:flex;justify-content:space-between;align-items:flex-start;padding:9px 0;border-bottom:1px solid rgba(255,255,255,.04);gap:12px;font-size:13px}
.dl-row:last-child{border-bottom:none}
.dl-label{color:var(--muted);font-weight:500;flex-shrink:0;min-width:120px}
.dl-val{font-weight:600;text-align:end}

/* Badges */
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
.badge-active,.badge-paid,.badge-completed{background:rgba(67,233,123,.1);color:#43e97b}
.badge-pending,.badge-draft{background:rgba(107,114,128,.1);color:var(--muted)}
.badge-overdue,.badge-cancelled{background:rgba(255,101,132,.1);color:var(--accent2)}

/* Payments table */
.payments-table{width:100%;border-collapse:collapse}
.payments-table th{font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;padding:8px 12px;text-align:start;border-bottom:1px solid var(--border)}
.payments-table td{padding:12px;border-bottom:1px solid rgba(255,255,255,.04);font-size:13px;vertical-align:middle}
.payments-table tr:last-child td{border-bottom:none}
.payments-table tr:hover td{background:rgba(255,255,255,.02)}

/* Modal */
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.65);z-index:1000;align-items:center;justify-content:center;padding:20px}
.modal-overlay.open{display:flex}
.modal{background:var(--surface);border:1px solid var(--border);border-radius:18px;padding:28px;width:100%;max-width:460px;animation:mIn .22s ease}
@keyframes mIn{from{opacity:0;transform:translateY(-18px)}to{opacity:1;transform:translateY(0)}}
.modal-title{font-family:'Syne',sans-serif;font-size:16px;font-weight:800;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between}
.modal-close{background:none;border:none;color:var(--muted);cursor:pointer;font-size:18px}
.form-group{display:flex;flex-direction:column;gap:6px;margin-bottom:14px}
.form-label{font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px}
.form-control{background:var(--surface2);border:1.5px solid var(--border);border-radius:10px;padding:10px 14px;font-size:13px;color:var(--text);font-family:inherit;outline:none;transition:border-color .2s;width:100%;box-sizing:border-box}
.form-control:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(108,99,255,.1)}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>{{ $contract->contract_number }}</h1>
        <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center">
            <span class="badge badge-{{ $contract->status }}">{{ __('admin.'.$contract->status) }}</span>
            <span style="font-size:12px;color:var(--muted)">{{ $contract->client->name }}</span>
        </div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
        <a href="{{ route('admin.contracts.edit', $contract) }}" class="btn btn-ghost btn-sm">
            <i class="fas fa-pen"></i> {{ __('admin.edit') }}
        </a>
        <a href="{{ route('admin.contracts.index') }}" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-{{ app()->getLocale()==='ar'?'right':'left' }}"></i>
        </a>
    </div>
</div>

{{-- KPIs --}}
<div class="kpi-strip">
    <div class="kpi">
        <div class="kpi-val" style="color:var(--accent)">JD {{ number_format($contract->net_amount,0) }}</div>
        <div class="kpi-lbl">{{ __('admin.contract_value') }}</div>
    </div>
    <div class="kpi">
        <div class="kpi-val" style="color:#43e97b">JD {{ number_format($contract->paid_amount,0) }}</div>
        <div class="kpi-lbl">{{ __('admin.paid') }}</div>
        <div class="prog-wrap"><div class="prog-fill" style="width:{{ $contract->payment_progress }}%"></div></div>
    </div>
    <div class="kpi">
        <div class="kpi-val" style="color:{{ $contract->remaining_amount > 0 ? '#f7b731' : '#43e97b' }}">
            JD {{ number_format($contract->remaining_amount,0) }}
        </div>
        <div class="kpi-lbl">{{ __('admin.remaining') }}</div>
    </div>
    <div class="kpi">
        <div class="kpi-val">{{ $contract->payments->count() }}</div>
        <div class="kpi-lbl">{{ __('admin.installments') }}</div>
    </div>
</div>

<div class="detail-layout">
    {{-- LEFT: contract info --}}
    <div>
        <div class="card">
            <div class="card-head"><div class="card-title"><i class="fas fa-file-contract"></i> {{ __('admin.contract_details') }}</div></div>

            <div class="dl-row">
                <span class="dl-label">{{ __('admin.client') }}</span>
                <a href="{{ route('admin.clients.show', $contract->client) }}"
                   style="font-weight:600;color:var(--accent);text-decoration:none">{{ $contract->client->name }}</a>
            </div>
            <div class="dl-row">
                <span class="dl-label">{{ __('admin.start_date') }}</span>
                <span class="dl-val">{{ $contract->start_date->format('d M Y') }}</span>
            </div>
            @if($contract->end_date)
            <div class="dl-row">
                <span class="dl-label">{{ __('admin.end_date') }}</span>
                <span class="dl-val">{{ $contract->end_date->format('d M Y') }}</span>
            </div>
            @endif
            <div class="dl-row">
                <span class="dl-label">{{ __('admin.total_amount') }}</span>
                <span class="dl-val">JD {{ number_format($contract->total_amount,2) }}</span>
            </div>
            @if($contract->discount > 0)
            <div class="dl-row">
                <span class="dl-label">{{ __('admin.discount') }}</span>
                <span class="dl-val" style="color:var(--accent2)">-JD {{ number_format($contract->discount,2) }}</span>
            </div>
            @endif
            @if($contract->tax > 0)
            <div class="dl-row">
                <span class="dl-label">{{ __('admin.tax') }}</span>
                <span class="dl-val">+JD {{ number_format($contract->tax,2) }}</span>
            </div>
            @endif
            <div class="dl-row">
                <span class="dl-label">{{ __('admin.net_amount') }}</span>
                <span class="dl-val" style="font-family:'Syne',sans-serif;font-size:15px;color:var(--accent)">
                    JD {{ number_format($contract->net_amount,2) }}
                </span>
            </div>
            <div class="dl-row">
                <span class="dl-label">{{ __('admin.created_by') }}</span>
                <span class="dl-val">{{ $contract->createdBy->name }}</span>
            </div>
            @if($contract->assigned_sales)
            <div class="dl-row">
                <span class="dl-label">{{ __('admin.assigned_sales') }}</span>
                <span class="dl-val">{{ $contract->client->assignedSales?->name ?? '—' }}</span>
            </div>
            @endif
        </div>

        @if($contract->scope || $contract->notes)
        <div class="card">
            <div class="card-head"><div class="card-title"><i class="fas fa-align-left"></i> {{ __('admin.scope') }}</div></div>
            @if($contract->scope)
            <p style="font-size:13px;color:var(--muted);line-height:1.7;margin:0 0 12px">{{ $contract->scope }}</p>
            @endif
            @if($contract->notes)
            <p style="font-size:12px;color:var(--muted);line-height:1.6;margin:0;border-top:1px solid var(--border);padding-top:10px">
                {{ $contract->notes }}
            </p>
            @endif
        </div>
        @endif

        {{-- Contract items --}}
        @if($contract->items->count())
        <div class="card">
            <div class="card-head"><div class="card-title"><i class="fas fa-list"></i> {{ __('admin.contract_items') }}</div></div>
            @foreach($contract->items as $item)
            <div class="dl-row">
                <span class="dl-label" style="min-width:0;flex:1">
                    @if($item->service)<span style="font-size:10px;color:var(--accent)">[{{ $item->service->name }}]</span> @endif
                    {{ $item->description }}
                    @if($item->quantity != 1)<span style="color:var(--muted)"> ×{{ $item->quantity }}</span>@endif
                </span>
                <span class="dl-val">JD {{ number_format($item->total,0) }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- RIGHT: payments --}}
    <div>
        <div class="card">
            <div class="card-head">
                <div class="card-title"><i class="fas fa-money-bill-wave"></i> {{ __('admin.payment_schedule') }}</div>
                <span style="font-size:12px;color:var(--muted)">
                    {{ $contract->payments->where('status','paid')->count() }} / {{ $contract->payments->count() }}
                    {{ __('admin.paid') }}
                </span>
            </div>

            <table class="payments-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('admin.label') }}</th>
                        <th>{{ __('admin.amount') }}</th>
                        <th>{{ __('admin.due_date') }}</th>
                        <th>{{ __('admin.status') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contract->payments as $payment)
                    @php
                        $isOverdue = $payment->status === 'pending' && $payment->due_date->isPast();
                        $badgeClass = $payment->status === 'paid' ? 'badge-paid'
                            : ($isOverdue ? 'badge-overdue' : 'badge-pending');
                        $badgeLabel = $payment->status === 'paid' ? __('admin.paid')
                            : ($isOverdue ? __('admin.overdue') : __('admin.pending'));
                    @endphp
                    <tr>
                        <td style="font-family:'Syne',sans-serif;font-weight:800;color:var(--muted)">
                            {{ $payment->payment_number }}
                        </td>
                        <td>
                            <div style="font-weight:600">{{ $payment->label ?: __('admin.installment').' '.$payment->payment_number }}</div>
                            @if($payment->status === 'paid' && $payment->paid_at)
                            <div style="font-size:11px;color:var(--muted)">{{ __('admin.paid_on') }}: {{ $payment->paid_at->format('d M Y') }}</div>
                            @endif
                        </td>
                        <td style="font-family:'Syne',sans-serif;font-weight:800">JD {{ number_format($payment->amount,0) }}</td>
                        <td style="color:{{ $isOverdue ? 'var(--accent2)' : 'var(--text)' }}">
                            {{ $payment->due_date->format('d M Y') }}
                            @if($isOverdue)
                            <div style="font-size:10px;color:var(--accent2)">{{ $payment->days_overdue }} {{ __('admin.days_late') }}</div>
                            @endif
                        </td>
                        <td><span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span></td>
                        <td>
                            @if($payment->status === 'paid' && $payment->receipt)
                                <a href="{{ route('admin.receipts.print', $payment->receipt) }}"
                                   target="_blank"
                                   class="btn btn-ghost btn-sm" title="{{ __('admin.print_receipt') }}">
                                    <i class="fas fa-print"></i>
                                </a>
                            @elseif($payment->status === 'pending')
                                <button class="btn btn-success btn-sm"
                                        onclick="openPayModal({{ $payment->id }}, '{{ $payment->label ?: __('admin.installment').' '.$payment->payment_number }}', {{ $payment->amount }})">
                                    <i class="fas fa-check"></i> {{ __('admin.record_payment') }}
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pay Modal --}}
<div class="modal-overlay" id="payModal">
    <div class="modal">
        <div class="modal-title">
            <span id="payModalTitle">{{ __('admin.record_payment') }}</span>
            <button class="modal-close" onclick="closePayModal()">×</button>
        </div>
        <form id="payForm" method="POST">
            @csrf

            <div style="background:var(--surface2);border-radius:10px;padding:12px 16px;margin-bottom:16px;display:flex;justify-content:space-between;align-items:center">
                <span style="font-size:13px;color:var(--muted)">{{ __('admin.amount') }}</span>
                <span id="payAmount" style="font-family:'Syne',sans-serif;font-weight:800;font-size:18px;color:var(--accent)"></span>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">{{ __('admin.payment_date') }} *</label>
                    <input type="date" name="paid_at" class="form-control"
                           value="{{ now()->format('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('admin.payment_method') }} *</label>
                    <select name="payment_method" class="form-control" required>
                        <option value="cash">{{ __('admin.cash') }}</option>
                        <option value="bank_transfer">{{ __('admin.bank_transfer') }}</option>
                        <option value="cheque">{{ __('admin.cheque') }}</option>
                        <option value="online">{{ __('admin.online') }}</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.reference') }}</label>
                <input type="text" name="reference" class="form-control"
                       placeholder="{{ __('admin.reference_hint') }}">
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.notes') }}</label>
                <textarea name="notes" class="form-control" rows="2"></textarea>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end">
                <button type="button" class="btn btn-ghost btn-sm" onclick="closePayModal()">{{ __('admin.cancel') }}</button>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-check"></i> {{ __('admin.confirm_payment') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const PAY_BASE = '{{ url("admin/contracts/payments") }}';

function openPayModal(paymentId, label, amount) {
    document.getElementById('payForm').action = `${PAY_BASE}/${paymentId}/pay`;
    document.getElementById('payModalTitle').textContent =
        '{{ __("admin.record_payment") }}: ' + label;
    document.getElementById('payAmount').textContent =
        '$' + new Intl.NumberFormat().format(amount);
    document.getElementById('payModal').classList.add('open');
}
function closePayModal() {
    document.getElementById('payModal').classList.remove('open');
}
document.getElementById('payModal').addEventListener('click', function(e) {
    if (e.target === this) closePayModal();
});
</script>
@endpush