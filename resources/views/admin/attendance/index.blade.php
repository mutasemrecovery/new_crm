{{-- ════════════════════════════════════════════════════
     resources/views/admin/attendance/index.blade.php
════════════════════════════════════════════════════ --}}
@extends('layouts.admin')
@section('title', __('admin.attendance'))

@push('styles')
<style>
.ph{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap}
.ph h1{font-family:'Syne',sans-serif;font-size:22px;font-weight:800}
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px}
@media(max-width:700px){.stats{grid-template-columns:1fr 1fr}}
.stat{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:16px 18px}
.stat-v{font-family:'Syne',sans-serif;font-size:28px;font-weight:800}
.stat-l{font-size:11px;color:var(--muted);margin-top:2px}
.fb{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:18px;align-items:center}
.fs{background:var(--surface2);border:1.5px solid var(--border);border-radius:9px;padding:8px 13px;color:var(--text);font-size:13px;font-family:inherit;outline:none;cursor:pointer}
.fs:focus{border-color:var(--accent)}
.btn{display:inline-flex;align-items:center;gap:7px;border-radius:10px;padding:9px 16px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;border:none;text-decoration:none;transition:all .2s}
.btn-primary{background:linear-gradient(135deg,var(--accent),#8b7eff);color:#fff}
.btn-ghost{background:var(--surface2);color:var(--text);border:1.5px solid var(--border)}
.btn-ghost:hover{border-color:var(--accent);color:var(--accent)}
.tcard{background:var(--surface);border:1px solid var(--border);border-radius:16px;overflow:hidden}
.at{width:100%;border-collapse:collapse;font-size:13px}
.at th{text-align:start;font-size:11px;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);font-weight:700;padding:11px 16px;border-bottom:1px solid var(--border);background:var(--surface2);white-space:nowrap}
.at td{padding:11px 16px;border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle}
.at tr:last-child td{border-bottom:none}
.at tr:hover td{background:rgba(255,255,255,.02)}
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700}
.badge-present{background:rgba(67,233,123,.12);color:#43e97b}
.badge-late{background:rgba(247,183,49,.12);color:#f7b731}
.badge-half_day{background:rgba(0,198,255,.12);color:#00c6ff}
.badge-absent{background:rgba(255,101,132,.12);color:var(--accent2)}
.av{width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,var(--accent),#8b7eff);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#fff;flex-shrink:0}
.emp-cell{display:flex;align-items:center;gap:9px}
.absent-section{background:var(--surface);border:1px solid rgba(255,101,132,.15);border-radius:16px;padding:18px;margin-top:16px}
.absent-head{font-size:13px;font-weight:700;color:var(--accent2);margin-bottom:12px;display:flex;align-items:center;gap:7px}
.absent-grid{display:flex;flex-wrap:wrap;gap:8px}
.absent-chip{display:flex;align-items:center;gap:7px;background:rgba(255,101,132,.06);border:1px solid rgba(255,101,132,.15);border-radius:10px;padding:7px 12px;font-size:12px}
.view-toggle{display:flex;gap:4px;background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:3px}
.vt-btn{padding:6px 14px;border-radius:7px;font-size:12px;font-weight:700;cursor:pointer;border:none;font-family:inherit;background:none;color:var(--muted);transition:all .15s}
.vt-btn.active{background:var(--accent);color:#fff}
</style>
@endpush

@section('content')
<div class="ph">
    <div>
        <h1>{{ __('admin.attendance') }}</h1>
        <p style="font-size:13px;color:var(--muted);margin-top:3px">{{ __('admin.attendance_subtitle') }}</p>
    </div>
    <a href="{{ route('admin.settings.index') }}" class="btn btn-ghost">
        <i class="fas fa-map-marker-alt"></i> {{ __('admin.location_settings') }}
    </a>
</div>

{{-- Stats --}}
<div class="stats">
    <div class="stat">
        <div class="stat-v" style="color:#43e97b">{{ $stats['present'] }}</div>
        <div class="stat-l">{{ __('admin.present') }}</div>
    </div>
    <div class="stat">
        <div class="stat-v" style="color:#f7b731">{{ $stats['late'] }}</div>
        <div class="stat-l">{{ __('admin.late') }}</div>
    </div>
    <div class="stat">
        <div class="stat-v" style="color:var(--accent2)">{{ $stats['absent'] }}</div>
        <div class="stat-l">{{ __('admin.absent') }}</div>
    </div>
    <div class="stat">
        <div class="stat-v" style="color:var(--accent)">
            {{ isset($stats['total']) ? $stats['total'] : ($stats['avg_hours'].'h') }}
        </div>
        <div class="stat-l">{{ $viewMode === 'day' ? __('admin.total_employees') : __('admin.avg_hours') }}</div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" id="filter-form">
    <div class="fb">
        {{-- View toggle --}}
        <div class="view-toggle">
            <button type="button" class="vt-btn {{ $viewMode==='day'?'active':'' }}"
                    onclick="setView('day')">{{ __('admin.day_view') }}</button>
            <button type="button" class="vt-btn {{ $viewMode==='month'?'active':'' }}"
                    onclick="setView('month')">{{ __('admin.month_view') }}</button>
        </div>
        <input type="hidden" name="view" id="view-input" value="{{ $viewMode }}">

        {{-- Date/Month --}}
        <div id="date-picker" style="{{ $viewMode==='month'?'display:none':'' }}">
            <input type="date" name="date" class="fs" value="{{ $date }}"
                   onchange="document.getElementById('filter-form').submit()">
        </div>
        <div id="month-picker" style="{{ $viewMode==='day'?'display:none':'' }}">
            <input type="month" name="month" class="fs" value="{{ $month }}"
                   onchange="document.getElementById('filter-form').submit()">
        </div>

        {{-- Employee filter --}}
        <select name="employee_id" class="fs" onchange="document.getElementById('filter-form').submit()">
            <option value="">{{ __('admin.all_employees') }}</option>
            @foreach($employees as $emp)
            <option value="{{ $emp->id }}" {{ $empId == $emp->id ? 'selected' : '' }}>
                {{ $emp->name }}
            </option>
            @endforeach
        </select>

        @if(request()->hasAny(['employee_id','date','month']))
        <a href="{{ route('admin.attendance.index') }}" class="btn btn-ghost" style="padding:8px 12px">
            <i class="fas fa-times"></i>
        </a>
        @endif
    </div>
</form>

{{-- Records --}}
<div class="tcard">
    @if($records->count())
    <div style="overflow-x:auto">
        <table class="at">
            <thead>
                <tr>
                    <th>{{ __('admin.employee') }}</th>
                    <th>{{ $viewMode==='month' ? __('admin.date') : __('admin.check_in') }}</th>
                    @if($viewMode==='month')<th>{{ __('admin.check_in') }}</th>@endif
                    <th>{{ __('admin.check_out') }}</th>
                    <th>{{ __('admin.worked_hours') }}</th>
                    <th>{{ __('admin.status') }}</th>
                    <th>{{ __('admin.distance') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $r)
                <tr>
                    <td>
                        <div class="emp-cell">
                            <div class="av">{{ strtoupper(mb_substr($r->employee->name,0,1)) }}</div>
                            <div>
                                <div style="font-weight:600;font-size:13px">{{ $r->employee->name }}</div>
                                <div style="font-size:10px;color:var(--muted)">{{ $r->employee->job_title }}</div>
                            </div>
                        </div>
                    </td>
                    @if($viewMode==='month')
                    <td style="font-weight:600">{{ $r->date->format('d M') }}</td>
                    @endif
                    <td>
                        @if($r->check_in)
                        <span style="font-weight:600">{{ $r->check_in->format('H:i') }}</span>
                        @else <span style="color:var(--muted)">—</span>
                        @endif
                    </td>
                    <td>
                        @if($r->check_out)
                        {{ $r->check_out->format('H:i') }}
                        @else
                        <span style="color:var(--muted);font-size:11px">{{ __('admin.still_in') }}</span>
                        @endif
                    </td>
                    <td style="font-weight:700;color:var(--accent)">{{ $r->worked_hours_formatted }}</td>
                    <td><span class="badge badge-{{ $r->status }}">{{ __('admin.'.$r->status) }}</span></td>
                    <td>
                        @if($r->check_in_distance)
                        <span style="font-size:11px;color:var(--muted)">
                            <i class="fas fa-map-marker-alt" style="font-size:9px"></i>
                            {{ round($r->check_in_distance) }}m
                        </span>
                        @else <span style="color:var(--muted)">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align:center;padding:52px 20px;color:var(--muted)">
        <i class="fas fa-calendar-times" style="font-size:38px;opacity:.2;display:block;margin-bottom:12px"></i>
        <p style="margin:0;font-size:13px">{{ __('admin.no_attendance_records') }}</p>
    </div>
    @endif
</div>

{{-- Absent employees (day view only) --}}
@if($viewMode === 'day' && isset($absentEmployees) && $absentEmployees->count())
<div class="absent-section">
    <div class="absent-head">
        <i class="fas fa-user-times"></i>
        {{ __('admin.absent_today') }} ({{ $absentEmployees->count() }})
    </div>
    <div class="absent-grid">
        @foreach($absentEmployees as $emp)
        <div class="absent-chip">
            <div class="av" style="width:26px;height:26px;font-size:10px">
                {{ strtoupper(mb_substr($emp->name,0,1)) }}
            </div>
            {{ $emp->name }}
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
function setView(v) {
    document.getElementById('view-input').value = v;
    document.getElementById('date-picker').style.display  = v === 'day'   ? '' : 'none';
    document.getElementById('month-picker').style.display = v === 'month' ? '' : 'none';
    document.querySelectorAll('.vt-btn').forEach(b => b.classList.remove('active'));
    document.querySelector(`.vt-btn:${v === 'day' ? 'first' : 'last'}-child`).classList.add('active');
    document.getElementById('filter-form').submit();
}
</script>
@endpush