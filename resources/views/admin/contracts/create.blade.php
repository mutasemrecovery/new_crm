@extends('layouts.admin')
@section('title', isset($contract) ? __('admin.edit_contract') : __('admin.add_contract'))
@section('page-title', isset($contract) ? __('admin.edit_contract') : __('admin.add_contract'))

@push('styles')
<style>
.page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:26px;gap:16px;flex-wrap:wrap}
.page-header h1{font-family:'Syne',sans-serif;font-size:22px;font-weight:800}
.btn-primary{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,var(--accent),#8b7eff);color:#fff;border:none;border-radius:10px;padding:11px 24px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;transition:opacity .2s;box-shadow:0 4px 14px rgba(108,99,255,.3);text-decoration:none}
.btn-primary:hover{opacity:.9;color:#fff}
.btn-secondary{display:inline-flex;align-items:center;gap:8px;background:var(--surface2);color:var(--text);border:1px solid var(--border);border-radius:10px;padding:10px 18px;font-size:13px;font-weight:600;text-decoration:none;transition:all .2s;font-family:inherit;cursor:pointer}
.btn-secondary:hover{border-color:var(--accent);color:var(--accent)}
.btn-danger-sm{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;background:rgba(255,101,132,.1);color:var(--accent2);border:1px solid rgba(255,101,132,.2);border-radius:7px;cursor:pointer;font-size:13px;transition:all .2s;flex-shrink:0}
.btn-danger-sm:hover{background:rgba(255,101,132,.2)}
.btn-add-row{display:inline-flex;align-items:center;gap:6px;background:rgba(108,99,255,.08);color:var(--accent);border:1.5px dashed rgba(108,99,255,.3);border-radius:10px;padding:8px 16px;font-size:12px;font-weight:700;cursor:pointer;transition:all .2s;font-family:inherit}
.btn-add-row:hover{background:rgba(108,99,255,.14);border-color:var(--accent)}

.form-card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:26px;margin-bottom:20px}
.form-card-title{font-family:'Syne',sans-serif;font-size:14px;font-weight:700;margin-bottom:18px;display:flex;align-items:center;gap:9px}
.form-card-title i{color:var(--accent)}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px}
@media(max-width:700px){.form-grid,.form-grid-3{grid-template-columns:1fr}}
.form-group{display:flex;flex-direction:column;gap:6px}
.form-group.full{grid-column:1/-1}
.form-label{font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.6px}
.form-control{background:var(--surface2);border:1.5px solid var(--border);border-radius:10px;padding:10px 14px;font-size:13px;color:var(--text);font-family:inherit;outline:none;transition:border-color .2s,box-shadow .2s;width:100%;box-sizing:border-box}
.form-control:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(108,99,255,.12)}
.form-control::placeholder{color:var(--muted)}
.form-control.is-invalid{border-color:var(--accent2)}
.field-error{font-size:11px;color:var(--accent2)}

/* Items / Payments table builder */
.builder-table{width:100%;border-collapse:collapse;margin-bottom:12px}
.builder-table th{font-size:10px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.6px;padding:6px 10px;text-align:start;border-bottom:1px solid var(--border)}
.builder-table td{padding:6px 6px;vertical-align:middle}
.builder-table tr.item-row:hover td{background:rgba(255,255,255,.01)}
.builder-table .form-control{padding:8px 10px;font-size:12px}

/* Net amount display */
.net-display{background:rgba(108,99,255,.08);border:1.5px solid rgba(108,99,255,.25);border-radius:12px;padding:14px 18px;display:flex;align-items:center;justify-content:space-between;margin-top:12px}
.net-display .label{font-size:12px;color:var(--muted);font-weight:600}
.net-display .value{font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:var(--accent)}

/* Payments total check */
.payments-check{font-size:12px;padding:8px 12px;border-radius:8px;margin-top:8px;display:flex;align-items:center;gap:8px}
.payments-check.ok{background:rgba(67,233,123,.08);color:#43e97b;border:1px solid rgba(67,233,123,.2)}
.payments-check.warn{background:rgba(247,183,49,.08);color:#f7b731;border:1px solid rgba(247,183,49,.2)}
.payments-check.err{background:rgba(255,101,132,.08);color:var(--accent2);border:1px solid rgba(255,101,132,.2)}
</style>
@endpush

@section('content')

<div class="page-header">
    <h1>{{ isset($contract) ? __('admin.edit_contract') : __('admin.add_contract') }}</h1>
    <a href="{{ isset($contract) ? route('admin.contracts.show',$contract) : route('admin.contracts.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-{{ app()->getLocale()==='ar'?'right':'left' }}"></i> {{ __('admin.back') }}
    </a>
</div>

<form method="POST"
      action="{{ isset($contract) ? route('admin.contracts.update',$contract) : route('admin.contracts.store') }}"
      id="contractForm">
    @csrf
    @if(isset($contract)) @method('PUT') @endif

    {{-- ══ Basic Info ══════════════════════════════════════════ --}}
    <div class="form-card">
        <div class="form-card-title"><i class="fas fa-file-contract"></i> {{ __('admin.contract_info') }}</div>
        <div class="form-grid">

            <div class="form-group">
                <label class="form-label">{{ __('admin.client') }} *</label>
                <select name="client_id" class="form-control @error('client_id') is-invalid @enderror" required>
                    <option value="">— {{ __('admin.select_client') }} —</option>
                    @foreach($clients as $c)
                    <option value="{{ $c->id }}"
                        {{ old('client_id', isset($contract)?$contract->client_id:($selectedClient?->id??'')) == $c->id ? 'selected' : '' }}>
                        {{ $c->name }}
                    </option>
                    @endforeach
                </select>
                @error('client_id')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.status') }} *</label>
                <select name="status" class="form-control" required>
                    @foreach(['draft','active','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ old('status', $contract->status??'draft') === $s ? 'selected' : '' }}>
                        {{ __('admin.'.$s) }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.start_date') }} *</label>
                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
                       value="{{ old('start_date', isset($contract->start_date)?$contract->start_date->format('Y-m-d'):now()->format('Y-m-d')) }}" required>
                @error('start_date')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.end_date') }}</label>
                <input type="date" name="end_date" class="form-control"
                       value="{{ old('end_date', isset($contract->end_date)?$contract->end_date->format('Y-m-d'):'') }}">
            </div>

            <div class="form-group full">
                <label class="form-label">{{ __('admin.scope') }}</label>
                <textarea name="scope" class="form-control" rows="3"
                          placeholder="{{ __('admin.scope_placeholder') }}">{{ old('scope', $contract->scope??'') }}</textarea>
            </div>

            <div class="form-group full">
                <label class="form-label">{{ __('admin.notes') }}</label>
                <textarea name="notes" class="form-control" rows="2">{{ old('notes', $contract->notes??'') }}</textarea>
            </div>

        </div>
    </div>

    {{-- ══ Contract Items ═══════════════════════════════════════ --}}
    <div class="form-card">
        <div class="form-card-title"><i class="fas fa-list-ul"></i> {{ __('admin.contract_items') }}
            <span style="font-size:12px;font-weight:400;color:var(--muted)">({{ __('admin.contract_items_hint') }})</span>
        </div>

        <table class="builder-table" id="items-table">
            <thead>
                <tr>
                    <th style="width:28%">{{ __('admin.description') }}</th>
                    <th style="width:22%">{{ __('admin.service') }}</th>
                    <th style="width:12%">{{ __('admin.qty') }}</th>
                    <th style="width:16%">{{ __('admin.unit_price') }}</th>
                    <th style="width:16%">{{ __('admin.total') }}</th>
                    <th style="width:6%"></th>
                </tr>
            </thead>
            <tbody id="items-body">
                @if(isset($contract) && $contract->items->count())
                    @foreach($contract->items as $i => $item)
                    <tr class="item-row" data-index="{{ $i }}">
                        <td><input type="text" name="items[{{ $i }}][description]" class="form-control"
                                   value="{{ old('items.'.$i.'.description', $item->description) }}"
                                   placeholder="{{ __('admin.item_description') }}" required></td>
                        <td>
                            <select name="items[{{ $i }}][service_id]" class="form-control">
                                <option value="">—</option>
                                @foreach($services as $svc)
                                <option value="{{ $svc->id }}" {{ old('items.'.$i.'.service_id',$item->service_id)==$svc->id?'selected':'' }}>
                                    {{ $svc->name }}
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" name="items[{{ $i }}][quantity]" class="form-control item-qty"
                                   value="{{ old('items.'.$i.'.quantity', $item->quantity) }}"
                                   min="0.01" step="0.01" oninput="recalcItem(this)"></td>
                        <td><input type="number" name="items[{{ $i }}][unit_price]" class="form-control item-price"
                                   value="{{ old('items.'.$i.'.unit_price', $item->unit_price) }}"
                                   min="0" step="0.01" oninput="recalcItem(this)"></td>
                        <td><input type="number" name="items[{{ $i }}][total]" class="form-control item-total"
                                   value="{{ old('items.'.$i.'.total', $item->total) }}"
                                   readonly style="color:var(--accent);font-weight:700"></td>
                        <td><button type="button" class="btn-danger-sm" onclick="removeRow(this,'items-body','recalcTotals')"><i class="fas fa-times"></i></button></td>
                    </tr>
                    @endforeach
                @else
                    {{-- one blank row by default --}}
                    <tr class="item-row" data-index="0">
                        <td><input type="text" name="items[0][description]" class="form-control"
                                   placeholder="{{ __('admin.item_description') }}"></td>
                        <td>
                            <select name="items[0][service_id]" class="form-control">
                                <option value="">—</option>
                                @foreach($services as $svc)
                                <option value="{{ $svc->id }}">{{ $svc->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" name="items[0][quantity]" class="form-control item-qty"
                                   value="1" min="0.01" step="0.01" oninput="recalcItem(this)"></td>
                        <td><input type="number" name="items[0][unit_price]" class="form-control item-price"
                                   value="" min="0" step="0.01" placeholder="0.00" oninput="recalcItem(this)"></td>
                        <td><input type="number" name="items[0][total]" class="form-control item-total"
                                   value="" readonly style="color:var(--accent);font-weight:700"></td>
                        <td><button type="button" class="btn-danger-sm" onclick="removeRow(this,'items-body','recalcTotals')"><i class="fas fa-times"></i></button></td>
                    </tr>
                @endif
            </tbody>
        </table>
        <button type="button" class="btn-add-row" onclick="addItemRow()">
            <i class="fas fa-plus"></i> {{ __('admin.add_item') }}
        </button>
    </div>

    {{-- ══ Amounts ══════════════════════════════════════════════ --}}
    <div class="form-card">
        <div class="form-card-title"><i class="fas fa-calculator"></i> {{ __('admin.amounts') }}</div>
        <div class="form-grid-3">
            <div class="form-group">
                <label class="form-label">{{ __('admin.total_amount') }} *</label>
                <input type="number" name="total_amount" id="total_amount" class="form-control @error('total_amount') is-invalid @enderror"
                       value="{{ old('total_amount', $contract->total_amount??'') }}"
                       min="0" step="0.01" placeholder="0.00" required oninput="recalcNet()">
                @error('total_amount')<span class="field-error">{{ $message }}</span>@enderror
                <span style="font-size:11px;color:var(--muted)">{{ __('admin.total_amount_hint') }}</span>
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('admin.discount') }}</label>
                <input type="number" name="discount" id="discount" class="form-control"
                       value="{{ old('discount', $contract->discount??0) }}"
                       min="0" step="0.01" placeholder="0.00" oninput="recalcNet()">
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('admin.tax') }}</label>
                <input type="number" name="tax" id="tax" class="form-control"
                       value="{{ old('tax', $contract->tax??0) }}"
                       min="0" step="0.01" placeholder="0.00" oninput="recalcNet()">
            </div>
        </div>
        <div class="net-display">
            <span class="label">{{ __('admin.net_amount') }}</span>
            <span class="value" id="net-display">$0.00</span>
        </div>
    </div>

    {{-- ══ Payment Schedule ═════════════════════════════════════ --}}
    <div class="form-card">
        <div class="form-card-title"><i class="fas fa-calendar-alt"></i> {{ __('admin.payment_schedule') }}
            <span style="font-size:12px;font-weight:400;color:var(--muted)">({{ __('admin.payment_schedule_hint') }})</span>
        </div>

        <table class="builder-table">
            <thead>
                <tr>
                    <th style="width:6%">#</th>
                    <th style="width:28%">{{ __('admin.label') }}</th>
                    <th style="width:20%">{{ __('admin.amount') }}</th>
                    <th style="width:20%">{{ __('admin.due_date') }}</th>
                    <th style="width:20%">{{ __('admin.notes') }}</th>
                    <th style="width:6%"></th>
                </tr>
            </thead>
            <tbody id="payments-body">
                @if(isset($contract) && $contract->payments->count())
                    @foreach($contract->payments as $i => $pay)
                    {{-- Only allow editing pending payments --}}
                    @if($pay->status === 'pending')
                    <tr class="item-row" data-index="{{ $i }}">
                        <td style="font-family:'Syne',sans-serif;font-weight:800;color:var(--muted);padding:6px 10px">
                            {{ $pay->payment_number }}
                        </td>
                        <td><input type="text" name="payments[{{ $i }}][label]" class="form-control"
                                   value="{{ old('payments.'.$i.'.label', $pay->label) }}"
                                   placeholder="{{ __('admin.payment_label_hint') }}"></td>
                        <td><input type="number" name="payments[{{ $i }}][amount]" class="form-control pay-amount"
                                   value="{{ old('payments.'.$i.'.amount', $pay->amount) }}"
                                   min="0" step="0.01" required oninput="checkPaymentsTotal()"></td>
                        <td><input type="date" name="payments[{{ $i }}][due_date]" class="form-control"
                                   value="{{ old('payments.'.$i.'.due_date', $pay->due_date->format('Y-m-d')) }}" required></td>
                        <td><input type="text" name="payments[{{ $i }}][notes]" class="form-control"
                                   value="{{ old('payments.'.$i.'.notes', $pay->notes) }}"></td>
                        <td><button type="button" class="btn-danger-sm" onclick="removeRow(this,'payments-body','checkPaymentsTotal')"><i class="fas fa-times"></i></button></td>
                    </tr>
                    @else
                    {{-- Paid payments: show read-only --}}
                    <tr style="opacity:.5">
                        <td style="padding:6px 10px;font-weight:700">{{ $pay->payment_number }}</td>
                        <td colspan="4" style="padding:6px 10px;font-size:12px;color:var(--muted)">
                            {{ $pay->label }} — JD {{ number_format($pay->amount,0) }}
                            <span class="badge badge-paid" style="font-size:10px">{{ __('admin.paid') }}</span>
                        </td>
                        <td></td>
                    </tr>
                    @endif
                    @endforeach
                @else
                    <tr class="item-row" data-index="0">
                        <td style="font-family:'Syne',sans-serif;font-weight:800;color:var(--muted);padding:6px 10px">1</td>
                        <td><input type="text" name="payments[0][label]" class="form-control"
                                   placeholder="{{ __('admin.payment_label_hint') }}"></td>
                        <td><input type="number" name="payments[0][amount]" class="form-control pay-amount"
                                   value="" min="0" step="0.01" required oninput="checkPaymentsTotal()"></td>
                        <td><input type="date" name="payments[0][due_date]" class="form-control" required></td>
                        <td><input type="text" name="payments[0][notes]" class="form-control"></td>
                        <td><button type="button" class="btn-danger-sm" onclick="removeRow(this,'payments-body','checkPaymentsTotal')"><i class="fas fa-times"></i></button></td>
                    </tr>
                @endif
            </tbody>
        </table>

        <button type="button" class="btn-add-row" onclick="addPaymentRow()">
            <i class="fas fa-plus"></i> {{ __('admin.add_installment') }}
        </button>

        <div class="payments-check" id="payments-check" style="display:none"></div>
    </div>

    {{-- Actions --}}
    <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:4px">
        <a href="{{ isset($contract) ? route('admin.contracts.show',$contract) : route('admin.contracts.index') }}"
           class="btn-secondary">{{ __('admin.cancel') }}</a>
        <button type="submit" class="btn-primary">
            <i class="fas fa-save"></i>
            {{ isset($contract) ? __('admin.update') : __('admin.create') }}
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
// ── services data for item rows ─────────────────────────────
const servicesOptions = `@foreach($services as $svc)<option value="{{ $svc->id }}">{{ addslashes(app()->getLocale()==='ar'?$svc->name:($svc->name_en??$svc->name)) }}</option>@endforeach`;

// ── Item rows ───────────────────────────────────────────────
let itemIndex = {{ isset($contract) ? $contract->items->count() : 1 }};

function addItemRow() {
    const i = itemIndex++;
    const tr = document.createElement('tr');
    tr.className = 'item-row';
    tr.dataset.index = i;
    tr.innerHTML = `
        <td><input type="text" name="items[${i}][description]" class="form-control" placeholder="{{ __('admin.item_description') }}"></td>
        <td><select name="items[${i}][service_id]" class="form-control"><option value="">—</option>${servicesOptions}</select></td>
        <td><input type="number" name="items[${i}][quantity]" class="form-control item-qty" value="1" min="0.01" step="0.01" oninput="recalcItem(this)"></td>
        <td><input type="number" name="items[${i}][unit_price]" class="form-control item-price" min="0" step="0.01" placeholder="0.00" oninput="recalcItem(this)"></td>
        <td><input type="number" name="items[${i}][total]" class="form-control item-total" readonly style="color:var(--accent);font-weight:700"></td>
        <td><button type="button" class="btn-danger-sm" onclick="removeRow(this,'items-body','recalcTotals')"><i class="fas fa-times"></i></button></td>
    `;
    document.getElementById('items-body').appendChild(tr);
}

function recalcItem(input) {
    const row   = input.closest('tr');
    const qty   = parseFloat(row.querySelector('.item-qty')?.value)   || 0;
    const price = parseFloat(row.querySelector('.item-price')?.value) || 0;
    const total = row.querySelector('.item-total');
    if (total) total.value = (qty * price).toFixed(2);
    recalcTotals();
}

function recalcTotals() {
    // sum all item totals → update total_amount
    let sum = 0;
    document.querySelectorAll('.item-total').forEach(el => { sum += parseFloat(el.value) || 0; });
    if (sum > 0) {
        document.getElementById('total_amount').value = sum.toFixed(2);
        recalcNet();
    }
}

// ── Net amount ──────────────────────────────────────────────
function recalcNet() {
    const total    = parseFloat(document.getElementById('total_amount').value)  || 0;
    const discount = parseFloat(document.getElementById('discount').value)      || 0;
    const tax      = parseFloat(document.getElementById('tax').value)            || 0;
    const net      = total - discount + tax;
    document.getElementById('net-display').textContent = '$' + net.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
    checkPaymentsTotal();
}

// ── Payment rows ────────────────────────────────────────────
let payIndex = {{ isset($contract) ? $contract->payments->count() : 1 }};

function addPaymentRow() {
    const i   = payIndex++;
    const num = document.querySelectorAll('#payments-body tr').length + 1;
    const tr  = document.createElement('tr');
    tr.className = 'item-row';
    tr.innerHTML = `
        <td style="font-family:'Syne',sans-serif;font-weight:800;color:var(--muted);padding:6px 10px">${num}</td>
        <td><input type="text" name="payments[${i}][label]" class="form-control" placeholder="{{ __('admin.payment_label_hint') }}"></td>
        <td><input type="number" name="payments[${i}][amount]" class="form-control pay-amount" min="0" step="0.01" required oninput="checkPaymentsTotal()"></td>
        <td><input type="date" name="payments[${i}][due_date]" class="form-control" required></td>
        <td><input type="text" name="payments[${i}][notes]" class="form-control"></td>
        <td><button type="button" class="btn-danger-sm" onclick="removeRow(this,'payments-body','checkPaymentsTotal')"><i class="fas fa-times"></i></button></td>
    `;
    document.getElementById('payments-body').appendChild(tr);
}

function checkPaymentsTotal() {
    const net  = parseFloat(document.getElementById('total_amount').value) || 0;
    const disc = parseFloat(document.getElementById('discount').value) || 0;
    const tax  = parseFloat(document.getElementById('tax').value) || 0;
    const netVal = net - disc + tax;

    let paySum = 0;
    document.querySelectorAll('.pay-amount').forEach(el => { paySum += parseFloat(el.value) || 0; });

    const el   = document.getElementById('payments-check');
    const diff = Math.abs(netVal - paySum);
    el.style.display = 'flex';

    if (diff < 0.01) {
        el.className = 'payments-check ok';
        el.innerHTML = '<i class="fas fa-check-circle"></i> {{ __("admin.payments_match") }}';
    } else if (paySum < netVal) {
        el.className = 'payments-check warn';
        el.innerHTML = `<i class="fas fa-exclamation-triangle"></i> {{ __("admin.payments_less") }} — $${(netVal-paySum).toFixed(2)} {{ __("admin.remaining") }}`;
    } else {
        el.className = 'payments-check err';
        el.innerHTML = `<i class="fas fa-times-circle"></i> {{ __("admin.payments_exceed") }} $${(paySum-netVal).toFixed(2)}`;
    }
}

// ── Generic remove row ──────────────────────────────────────
function removeRow(btn, bodyId, recalcFn) {
    const tbody = document.getElementById(bodyId);
    if (tbody.querySelectorAll('tr.item-row').length <= 1) return; // keep at least 1
    btn.closest('tr').remove();
    if (recalcFn === 'recalcTotals') recalcTotals();
    if (recalcFn === 'checkPaymentsTotal') checkPaymentsTotal();
}

// ── Init on load ────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    recalcNet();
    checkPaymentsTotal();
});
</script>
@endpush