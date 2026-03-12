@extends('layouts.admin')
@section('title', __('admin.nav_contracts'))
@section('page-title', __('admin.nav_contracts'))

@push('styles')
<style>
.page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:26px;gap:16px;flex-wrap:wrap}
.page-header h1{font-family:'Syne',sans-serif;font-size:22px;font-weight:800}
.btn{display:inline-flex;align-items:center;gap:8px;border-radius:10px;padding:10px 20px;font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .2s;font-family:inherit;white-space:nowrap;border:none}
.btn-primary{background:linear-gradient(135deg,var(--accent),#8b7eff);color:#fff;box-shadow:0 4px 14px rgba(108,99,255,.3)}
.btn-primary:hover{opacity:.9;color:#fff}
.btn-ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border)}
.btn-ghost:hover{border-color:var(--accent);color:var(--accent)}
.btn-sm{padding:6px 12px;font-size:12px;border-radius:8px}
.act-btn{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:7px;color:var(--muted);text-decoration:none;transition:all .15s;font-size:13px;background:none;border:none;cursor:pointer}
.act-btn:hover{background:var(--surface2);color:var(--text)}
.act-btn.del:hover{background:rgba(255,101,132,.1);color:var(--accent2)}

/* Stats */
.stats-strip{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:24px}
@media(max-width:900px){.stats-strip{grid-template-columns:repeat(3,1fr)}}
@media(max-width:600px){.stats-strip{grid-template-columns:1fr 1fr}}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:16px 18px}
.stat-val{font-family:'Syne',sans-serif;font-size:22px;font-weight:800}
.stat-lbl{font-size:11px;color:var(--muted);margin-top:2px;text-transform:uppercase;letter-spacing:.5px}

/* Filters */
.filters{display:flex;gap:10px;margin-bottom:18px;flex-wrap:wrap}
.filters input,.filters select{background:var(--surface2);border:1.5px solid var(--border);border-radius:10px;padding:9px 14px;font-size:13px;color:var(--text);font-family:inherit;outline:none;transition:border-color .2s}
.filters input:focus,.filters select:focus{border-color:var(--accent)}
.filters input{flex:1;min-width:180px}

/* Table */
.table-card{background:var(--surface);border:1px solid var(--border);border-radius:16px;overflow:hidden}
.data-table{width:100%;border-collapse:collapse}
.data-table th{font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;padding:12px 16px;text-align:start;border-bottom:1px solid var(--border);white-space:nowrap}
.data-table td{padding:13px 16px;border-bottom:1px solid rgba(255,255,255,.04);font-size:13px;vertical-align:middle}
.data-table tr:last-child td{border-bottom:none}
.data-table tr:hover td{background:rgba(255,255,255,.02)}

/* Badges */
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
.badge-active,.badge-paid,.badge-completed{background:rgba(67,233,123,.1);color:#43e97b}
.badge-pending,.badge-draft{background:rgba(107,114,128,.1);color:var(--muted)}
.badge-overdue,.badge-cancelled{background:rgba(255,101,132,.1);color:var(--accent2)}

/* Progress */
.prog-wrap{background:var(--surface2);border-radius:99px;height:5px;overflow:hidden;margin-top:5px;width:100px}
.prog-fill{height:100%;background:linear-gradient(135deg,var(--accent),#8b7eff);border-radius:99px}

.empty-state{text-align:center;padding:60px 20px;color:var(--muted)}
.empty-state i{font-size:40px;margin-bottom:14px;opacity:.3;display:block}
.pagination-wrap{padding:16px 20px;border-top:1px solid var(--border);display:flex;justify-content:flex-end}
</style>
@endpush

@section('content')

<div class="page-header">
    <h1>{{ __('admin.nav_contracts') }}</h1>
    <a href="{{ route('admin.contracts.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> {{ __('admin.add_contract') }}
    </a>
</div>

{{-- Stats --}}
<div class="stats-strip">
    <div class="stat-card">
        <div class="stat-val">{{ $stats['total'] }}</div>
        <div class="stat-lbl">{{ __('admin.total') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-val" style="color:#43e97b">{{ $stats['active'] }}</div>
        <div class="stat-lbl">{{ __('admin.active') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-val" style="color:var(--accent)">{{ $stats['completed'] }}</div>
        <div class="stat-lbl">{{ __('admin.completed') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-val" style="color:var(--accent2)">{{ $stats['overdue'] }}</div>
        <div class="stat-lbl">{{ __('admin.overdue_payments') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-val" style="color:#f7b731;font-size:17px">JD {{ number_format($stats['pending_amount'],0) }}</div>
        <div class="stat-lbl">{{ __('admin.pending_amount') }}</div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.contracts.index') }}">
    <div class="filters">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="{{ __('admin.search_contracts') }}">
        <select name="status" onchange="this.form.submit()">
            <option value="">— {{ __('admin.all_statuses') }} —</option>
            @foreach(['draft','active','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ __('admin.'.$s) }}</option>
            @endforeach
        </select>
        @if(request()->hasAny(['search','status']))
            <a href="{{ route('admin.contracts.index') }}" class="btn btn-ghost btn-sm">
                <i class="fas fa-times"></i> {{ __('admin.clear') }}
            </a>
        @endif
        <button type="submit" class="btn btn-ghost btn-sm">
            <i class="fas fa-search"></i>
        </button>
    </div>
</form>

<div class="table-card">
    @if($contracts->count())
    <table class="data-table">
        <thead>
            <tr>
                <th>{{ __('admin.contract_number') }}</th>
                <th>{{ __('admin.client') }}</th>
                <th>{{ __('admin.net_amount') }}</th>
                <th>{{ __('admin.paid') }} / {{ __('admin.remaining') }}</th>
                <th>{{ __('admin.start_date') }}</th>
                <th>{{ __('admin.status') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($contracts as $contract)
            @php
                $paidAmt = $contract->payments->where('status','paid')->sum('amount');
                $progress = $contract->net_amount > 0 ? min(100, round($paidAmt / $contract->net_amount * 100)) : 0;
                $remaining = $contract->net_amount - $paidAmt;
                $overdueCount = $contract->payments->where('status','pending')->filter(fn($p) => $p->due_date->isPast())->count();
            @endphp
            <tr>
                <td>
                    <a href="{{ route('admin.contracts.show', $contract) }}"
                       style="font-family:'Syne',sans-serif;font-weight:700;color:var(--accent);text-decoration:none">
                        {{ $contract->contract_number }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('admin.clients.show', $contract->client) }}"
                       style="font-weight:600;color:var(--text);text-decoration:none">
                        {{ $contract->client->name }}
                    </a>
                </td>
                <td style="font-family:'Syne',sans-serif;font-weight:800">JD {{ number_format($contract->net_amount,0) }}</td>
                <td>
                    <div style="font-size:12px;color:var(--muted)">
                        <span style="color:#43e97b;font-weight:600">JD {{ number_format($paidAmt,0) }}</span>
                        / <span style="color:{{ $remaining>0?'#f7b731':'#43e97b' }};font-weight:600">JD {{ number_format($remaining,0) }}</span>
                    </div>
                    <div class="prog-wrap"><div class="prog-fill" style="width:{{ $progress }}%"></div></div>
                    @if($overdueCount)
                    <div style="font-size:10px;color:var(--accent2);margin-top:3px">
                        ⚠ {{ $overdueCount }} {{ __('admin.overdue') }}
                    </div>
                    @endif
                </td>
                <td style="color:var(--muted)">{{ $contract->start_date->format('d M Y') }}</td>
                <td><span class="badge badge-{{ $contract->status }}">{{ __('admin.'.$contract->status) }}</span></td>
                <td>
                    <div style="display:flex;gap:4px">
                        <a href="{{ route('admin.contracts.show', $contract) }}" class="act-btn" title="{{ __('admin.view') }}">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.contracts.edit', $contract) }}" class="act-btn" title="{{ __('admin.edit') }}">
                            <i class="fas fa-pen"></i>
                        </a>
                        <form action="{{ route('admin.contracts.destroy', $contract) }}" method="POST" id="del-c{{ $contract->id }}">
                            @csrf @method('DELETE')
                            <button type="button" class="act-btn del"
                                    onclick="confirmDelete('del-c{{ $contract->id }}','{{ __('admin.confirm_delete') }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if($contracts->hasPages())
    <div class="pagination-wrap">{{ $contracts->links() }}</div>
    @endif
    @else
    <div class="empty-state">
        <i class="fas fa-file-contract"></i>
        <p>{{ __('admin.no_contracts_yet') }}</p>
        <a href="{{ route('admin.contracts.create') }}" class="btn btn-primary" style="margin-top:16px;display:inline-flex">
            <i class="fas fa-plus"></i> {{ __('admin.add_contract') }}
        </a>
    </div>
    @endif
</div>

@endsection