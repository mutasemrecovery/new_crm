@extends('layouts.admin')
@section('title', __('admin.nav_leaves'))
@push('styles')
<style>
.ph{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap}
.ph h1{font-family:'Syne',sans-serif;font-size:22px;font-weight:800}
.btn{display:inline-flex;align-items:center;gap:7px;border-radius:10px;padding:9px 18px;font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .2s;font-family:inherit;border:none;white-space:nowrap}
.btn-primary{background:linear-gradient(135deg,var(--accent),#8b7eff);color:#fff;box-shadow:0 4px 14px rgba(108,99,255,.3)}.btn-primary:hover{opacity:.9;color:#fff}
.btn-ghost{background:var(--surface2);color:var(--text);border:1.5px solid var(--border)}.btn-ghost:hover{border-color:var(--accent);color:var(--accent)}
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px}
@media(max-width:700px){.stats{grid-template-columns:1fr 1fr}}
.stat{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:16px 18px}
.stat-v{font-family:'Syne',sans-serif;font-size:28px;font-weight:800}
.stat-l{font-size:11px;color:var(--muted);margin-top:2px}
.fb{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:18px;align-items:center}
.fs{background:var(--surface2);border:1.5px solid var(--border);border-radius:9px;padding:8px 13px;color:var(--text);font-size:13px;font-family:inherit;outline:none}
.fs:focus{border-color:var(--accent)}
.tcard{background:var(--surface);border:1px solid var(--border);border-radius:16px;overflow:hidden}
.at{width:100%;border-collapse:collapse;font-size:13px}
.at th{text-align:start;font-size:11px;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);font-weight:700;padding:11px 16px;border-bottom:1px solid var(--border);background:var(--surface2);white-space:nowrap}
.at td{padding:11px 16px;border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle}
.at tr:last-child td{border-bottom:none}
.at tr:hover td{background:rgba(255,255,255,.02)}
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700}
.badge-pending{background:rgba(247,183,49,.12);color:#f7b731}
.badge-approved{background:rgba(67,233,123,.12);color:#43e97b}
.badge-rejected{background:rgba(255,101,132,.12);color:var(--accent2)}
.badge-annual{background:rgba(108,99,255,.12);color:var(--accent)}
.badge-sick{background:rgba(0,198,255,.12);color:#00c6ff}
.badge-emergency{background:rgba(255,101,132,.12);color:var(--accent2)}
.badge-unpaid{background:rgba(107,114,128,.12);color:var(--muted)}
.badge-other{background:rgba(247,183,49,.12);color:#f7b731}
.av{width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,var(--accent),#8b7eff);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#fff;flex-shrink:0}
.emp-cell{display:flex;align-items:center;gap:9px}
.ab{display:inline-flex;align-items:center;justify-content:center;width:29px;height:29px;border-radius:7px;color:var(--muted);text-decoration:none;transition:all .15s;font-size:12px;background:none;border:none;cursor:pointer}
.ab:hover{background:var(--surface2);color:var(--text)}
</style>
@endpush
@section('content')

<div class="ph">
    <div>
        <h1>{{ __('admin.nav_leaves') }}</h1>
        <p style="font-size:13px;color:var(--muted);margin-top:3px">{{ __('admin.leaves_subtitle') }}</p>
    </div>
    <a href="{{ route('admin.leaves.balances') }}" class="btn btn-ghost">
        <i class="fas fa-calendar-alt"></i> {{ __('admin.manage_balances') }}
    </a>
</div>

<div class="stats">
    <div class="stat" style="cursor:pointer" onclick="qs('pending')">
        <div class="stat-v" style="color:#f7b731">{{ $stats['pending'] }}</div>
        <div class="stat-l">{{ __('admin.pending') }}</div>
    </div>
    <div class="stat" onclick="qs('approved')" style="cursor:pointer">
        <div class="stat-v" style="color:#43e97b">{{ $stats['approved'] }}</div>
        <div class="stat-l">{{ __('admin.approved') }}</div>
    </div>
    <div class="stat" onclick="qs('rejected')" style="cursor:pointer">
        <div class="stat-v" style="color:var(--accent2)">{{ $stats['rejected'] }}</div>
        <div class="stat-l">{{ __('admin.rejected') }}</div>
    </div>
    <div class="stat">
        <div class="stat-v" style="color:var(--accent)">{{ $stats['this_month'] }}</div>
        <div class="stat-l">{{ __('admin.this_month') }}</div>
    </div>
</div>

<form method="GET" id="ff">
    <div class="fb">
        <select name="status" class="fs" onchange="this.form.submit()">
            <option value="">{{ __('admin.all_statuses') }}</option>
            @foreach(['pending','approved','rejected'] as $s)
            <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ __('admin.'.$s) }}</option>
            @endforeach
        </select>
        <select name="type" class="fs" onchange="this.form.submit()">
            <option value="">{{ __('admin.all_types') }}</option>
            @foreach(['annual','sick','emergency','unpaid','other'] as $t)
            <option value="{{ $t }}" {{ request('type')===$t?'selected':'' }}>{{ __('admin.leave_'.$t) }}</option>
            @endforeach
        </select>
        <select name="employee_id" class="fs" onchange="this.form.submit()">
            <option value="">{{ __('admin.all_employees') }}</option>
            @foreach($employees as $emp)
            <option value="{{ $emp->id }}" {{ request('employee_id')==$emp->id?'selected':'' }}>{{ $emp->name }}</option>
            @endforeach
        </select>
        @if(request()->hasAny(['status','type','employee_id']))
        <a href="{{ route('admin.leaves.index') }}" class="btn btn-ghost" style="padding:8px 12px"><i class="fas fa-times"></i></a>
        @endif
    </div>
</form>

<div class="tcard">
    @if($leaves->count())
    <div style="overflow-x:auto">
        <table class="at">
            <thead>
                <tr>
                    <th>{{ __('admin.employee') }}</th>
                    <th>{{ __('admin.leave_type') }}</th>
                    <th>{{ __('admin.start_date') }}</th>
                    <th>{{ __('admin.end_date') }}</th>
                    <th>{{ __('admin.days_count') }}</th>
                    <th>{{ __('admin.status') }}</th>
                    <th>{{ __('admin.submitted_at') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($leaves as $leave)
                <tr>
                    <td>
                        <div class="emp-cell">
                            <div class="av">{{ strtoupper(mb_substr($leave->employee->name,0,1)) }}</div>
                            <span style="font-weight:600">{{ $leave->employee->name }}</span>
                        </div>
                    </td>
                    <td><span class="badge badge-{{ $leave->type }}">{{ __('admin.leave_'.$leave->type) }}</span></td>
                    <td style="font-size:12px">{{ $leave->start_date->format('d M Y') }}</td>
                    <td style="font-size:12px">{{ $leave->end_date->format('d M Y') }}</td>
                    <td>
                        <span style="font-family:'Syne',sans-serif;font-weight:800;color:var(--accent)">
                            {{ $leave->days_count }}
                        </span>
                        <span style="font-size:11px;color:var(--muted)"> {{ __('admin.days') }}</span>
                    </td>
                    <td><span class="badge badge-{{ $leave->status }}">{{ __('admin.'.$leave->status) }}</span></td>
                    <td style="font-size:11px;color:var(--muted)">{{ $leave->created_at->diffForHumans() }}</td>
                    <td>
                        <a href="{{ route('admin.leaves.show', $leave) }}" class="ab">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:16px">{{ $leaves->links() }}</div>
    @else
    <div style="text-align:center;padding:52px 20px;color:var(--muted)">
        <i class="fas fa-calendar-times" style="font-size:38px;opacity:.2;display:block;margin-bottom:12px"></i>
        <p style="margin:0;font-size:13px">{{ __('admin.no_leaves') }}</p>
    </div>
    @endif
</div>
@endsection
@push('scripts')
<script>
function qs(s) {
    document.querySelector('select[name=status]').value = s;
    document.getElementById('ff').submit();
}
</script>
@endpush