@extends('layouts.admin')
@section('title', __('admin.nav_commissions'))

@push('styles')
<style>
:root{--g:linear-gradient(135deg,var(--accent),#8b7eff)}
.page-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap}
.page-header h1{font-family:'Syne',sans-serif;font-size:22px;font-weight:800}
.btn{display:inline-flex;align-items:center;gap:8px;border-radius:10px;padding:8px 16px;font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .2s;font-family:inherit;border:none;white-space:nowrap}
.btn-ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border)}.btn-ghost:hover{border-color:var(--accent);color:var(--accent)}
.btn-success{background:rgba(67,233,123,.1);color:#43e97b;border:1px solid rgba(67,233,123,.2)}.btn-success:hover{background:rgba(67,233,123,.2)}
.kpi-strip{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px}
@media(max-width:700px){.kpi-strip{grid-template-columns:1fr}}
.kpi{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:18px 20px}
.kpi-val{font-family:'Syne',sans-serif;font-size:26px;font-weight:800}
.kpi-lbl{font-size:11px;color:var(--muted);margin-top:3px;text-transform:uppercase;letter-spacing:.5px}
.filters{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:18px}
.filter-select{background:var(--surface2);border:1px solid var(--border);border-radius:9px;padding:9px 14px;color:var(--text);font-size:13px;font-family:inherit;outline:none}.filter-select:focus{border-color:var(--accent)}
.emp-cards{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:14px;margin-bottom:28px}
.emp-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:18px;display:flex;flex-direction:column;gap:12px}
.emp-top{display:flex;align-items:center;gap:12px}
.emp-av{width:42px;height:42px;border-radius:12px;background:var(--g);display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-size:16px;font-weight:800;color:#fff;flex-shrink:0}
.emp-stats{display:grid;grid-template-columns:1fr 1fr;gap:8px}
.emp-stat{background:var(--surface2);border-radius:8px;padding:9px 11px}
.emp-stat-val{font-family:'Syne',sans-serif;font-size:15px;font-weight:800}
.emp-stat-lbl{font-size:10px;color:var(--muted);margin-top:2px}
.table-card{background:var(--surface);border:1px solid var(--border);border-radius:16px;overflow:hidden}
.tbl{width:100%;border-collapse:collapse;font-size:13px}
.tbl th{font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);font-weight:600;padding:12px 16px;border-bottom:1px solid var(--border);background:var(--surface2);text-align:start;white-space:nowrap}
.tbl td{padding:12px 16px;border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle}
.tbl tr:last-child td{border-bottom:none}
.tbl tr:hover td{background:rgba(255,255,255,.02)}
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
.badge-pending{background:rgba(247,183,49,.1);color:#f7b731}
.badge-paid{background:rgba(67,233,123,.1);color:#43e97b}
.action-btn{display:inline-flex;align-items:center;justify-content:center;border-radius:7px;color:var(--muted);border:none;background:transparent;cursor:pointer;font-size:12px;transition:all .15s}
.action-btn:hover{background:var(--surface2);color:var(--text)}
.empty{text-align:center;padding:50px;color:var(--muted)}
.empty i{font-size:36px;margin-bottom:12px;opacity:.25;display:block}
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>{{ __('admin.nav_commissions') }}</h1>
        <p style="font-size:13px;color:var(--muted);margin-top:3px">{{ __('admin.commissions_subtitle') }}</p>
    </div>
</div>

<div class="kpi-strip">
    <div class="kpi"><div class="kpi-val" style="color:#f7b731">${{ number_format($stats['total_pending'],2) }}</div><div class="kpi-lbl">{{ __('admin.pending_payout') }}</div></div>
    <div class="kpi"><div class="kpi-val" style="color:#43e97b">${{ number_format($stats['total_paid'],2) }}</div><div class="kpi-lbl">{{ __('admin.total_paid_out') }}</div></div>
    <div class="kpi"><div class="kpi-val">{{ $stats['count_pending'] }}</div><div class="kpi-lbl">{{ __('admin.pending_count') }}</div></div>
</div>

@php $byEmployee = $commissions->getCollection()->groupBy('employee_id'); @endphp
@if($byEmployee->count())
<p style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:12px">{{ __('admin.by_employee') }}</p>
<div class="emp-cards">
    @foreach($byEmployee as $empId => $empComms)
    @php $emp = $empComms->first()->employee @endphp
    <div class="emp-card">
        <div class="emp-top">
            <div class="emp-av">{{ strtoupper(mb_substr($emp->name??'?',0,1)) }}</div>
            <div>
                <div style="font-size:14px;font-weight:700">{{ $emp->name ?? '—' }}</div>
                <div style="font-size:11px;color:var(--muted)">{{ $emp->position ?? $emp->department ?? '' }}</div>
            </div>
        </div>
        <div class="emp-stats">
            <div class="emp-stat"><div class="emp-stat-val" style="color:#f7b731">${{ number_format($empComms->where('status','pending')->sum('amount'),0) }}</div><div class="emp-stat-lbl">{{ __('admin.pending') }}</div></div>
            <div class="emp-stat"><div class="emp-stat-val" style="color:#43e97b">${{ number_format($empComms->where('status','paid')->sum('amount'),0) }}</div><div class="emp-stat-lbl">{{ __('admin.paid') }}</div></div>
        </div>
        @if($empComms->where('status','pending')->count() > 0)
        <form action="{{ route('admin.commissions.pay-employee', $emp) }}" method="POST">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-success" style="width:100%;justify-content:center;font-size:12px"
                    onclick="return confirm('{{ __('admin.confirm_pay_all') }}')">
                <i class="fas fa-money-bill-wave"></i>
                {{ __('admin.pay_all_pending') }} ({{ $empComms->where('status','pending')->count() }})
            </button>
        </form>
        @endif
    </div>
    @endforeach
</div>
@endif

<form method="GET">
    <div class="filters">
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">{{ __('admin.all_statuses') }}</option>
            <option value="pending" {{ request('status')==='pending'?'selected':'' }}>{{ __('admin.pending') }}</option>
            <option value="paid" {{ request('status')==='paid'?'selected':'' }}>{{ __('admin.paid') }}</option>
        </select>
        <select name="employee_id" class="filter-select" onchange="this.form.submit()">
            <option value="">{{ __('admin.all_employees') }}</option>
            @foreach($employees as $emp)
            <option value="{{ $emp->id }}" {{ request('employee_id')==$emp->id?'selected':'' }}>{{ $emp->name }}</option>
            @endforeach
        </select>
        @if(request()->hasAny(['status','employee_id']))
        <a href="{{ route('admin.commissions.index') }}" class="btn btn-ghost" style="padding:9px 14px"><i class="fas fa-times"></i> {{ __('admin.clear') }}</a>
        @endif
    </div>
</form>

<div class="table-card">
    @if($commissions->count())
    <div style="overflow-x:auto">
        <table class="tbl">
            <thead>
                <tr>
                    <th>{{ __('admin.employee') }}</th>
                    <th>{{ __('admin.client') }}</th>
                    <th>{{ __('admin.invoice') }}</th>
                    <th>{{ __('admin.rate') }}</th>
                    <th>{{ __('admin.amount') }}</th>
                    <th>{{ __('admin.status') }}</th>
                    <th>{{ __('admin.paid_at') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($commissions as $comm)
                <tr>
                    <td style="font-weight:600">{{ $comm->employee->name ?? '—' }}</td>
                    <td><a href="{{ route('admin.clients.show',$comm->client) }}" style="color:var(--text);text-decoration:none;font-weight:500">{{ $comm->client->name }}</a></td>
                    <td>
                        @if($comm->invoice)
                        <a href="{{ route('admin.invoices.show',$comm->invoice) }}" style="color:var(--accent);text-decoration:none;font-family:'Syne',sans-serif;font-size:12px;font-weight:700">{{ $comm->invoice->invoice_number }}</a>
                        @else —
                        @endif
                    </td>
                    <td style="color:var(--muted)">{{ $comm->rate }}%</td>
                    <td style="font-family:'Syne',sans-serif;font-weight:800;color:var(--accent)">${{ number_format($comm->amount,2) }}</td>
                    <td><span class="badge badge-{{ $comm->status }}">{{ __('admin.'.$comm->status) }}</span></td>
                    <td style="font-size:12px;color:var(--muted)">{{ $comm->paid_at?->format('d M Y') ?? '—' }}</td>
                    <td>
                        @if($comm->status==='pending')
                        <form action="{{ route('admin.commissions.pay-single',$comm) }}" method="POST" style="display:inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-success" style="padding:5px 10px;font-size:11px" title="{{ __('admin.mark_paid') }}">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @else
                        <span style="color:#43e97b;font-size:13px"><i class="fas fa-check-circle"></i></span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($commissions->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;justify-content:flex-end">{{ $commissions->links() }}</div>
    @endif
    @else
    <div class="empty"><i class="fas fa-percentage"></i><p>{{ __('admin.no_commissions') }}</p></div>
    @endif
</div>
@endsection