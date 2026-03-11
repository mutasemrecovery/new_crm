@extends('layouts.admin')

@section('title', __('admin.dashboard'))
@section('page-title', __('admin.dashboard'))

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 18px;
        margin-bottom: 28px;
    }
    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px; padding: 22px;
        position: relative; overflow: hidden;
        transition: transform .2s, border-color .2s;
    }
    .stat-card:hover { transform: translateY(-3px); border-color: rgba(255,255,255,.15); }
    .stat-card::before {
        content: ''; position: absolute; top: -40px; right: -40px;
        width: 110px; height: 110px; border-radius: 50%; opacity: .07;
    }
    html[dir="rtl"] .stat-card::before { right: auto; left: -40px; }
    .stat-card:nth-child(1)::before { background: #6c63ff; }
    .stat-card:nth-child(2)::before { background: #43e97b; }
    .stat-card:nth-child(3)::before { background: #ff6584; }
    .stat-card:nth-child(4)::before { background: #f7b731; }
    .stat-card:nth-child(5)::before { background: #00c6ff; }
    .stat-card:nth-child(6)::before { background: #fc5c7d; }
    .stat-icon {
        width: 46px; height: 46px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 19px; margin-bottom: 14px;
    }
    .stat-icon.purple { background: rgba(108,99,255,.15); color: var(--accent); }
    .stat-icon.green  { background: rgba(67,233,123,.15);  color: var(--accent3); }
    .stat-icon.pink   { background: rgba(255,101,132,.15); color: var(--accent2); }
    .stat-icon.yellow { background: rgba(247,183,49,.15);  color: #f7b731; }
    .stat-icon.cyan   { background: rgba(0,198,255,.15);   color: #00c6ff; }
    .stat-icon.red    { background: rgba(252,92,125,.15);  color: #fc5c7d; }
    .stat-value { font-size: 30px; font-weight: 800; margin-bottom: 3px; }
    .stat-label { font-size: 12px; color: var(--muted); font-weight: 500; }
    .stat-change { margin-top: 10px; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 4px; }
    .stat-change.up   { color: var(--accent3); }
    .stat-change.down { color: var(--accent2); }

    .quick-actions { display: grid; grid-template-columns: repeat(auto-fit,minmax(130px,1fr)); gap:14px; margin-bottom:28px; }
    .quick-card {
        background: var(--surface); border: 1px solid var(--border);
        border-radius:14px; padding:18px 14px;
        display:flex; flex-direction:column; align-items:center; gap:9px;
        text-decoration:none; transition:all .2s; text-align:center;
    }
    .quick-card:hover { border-color:var(--accent); background:rgba(108,99,255,.05); transform:translateY(-2px); }
    .quick-card i { font-size:22px; }
    .quick-card span { font-size:12px; font-weight:600; color:var(--muted); }
    .quick-card:hover span { color:var(--text); }

    .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:28px; }
    @media(max-width:1100px){ .grid-2{ grid-template-columns:1fr; } }

    .section-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:18px; }
    .section-title  { font-size:16px; font-weight:700; }
    .section-action { font-size:12px; color:var(--accent); font-weight:600; text-decoration:none; display:flex; align-items:center; gap:4px; }

    .emp-avatar-sm { width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:inline-flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;margin-inline-end:8px; }
    .emp-name-wrap { display:flex;align-items:center; }

    .activity-list { display:flex;flex-direction:column; }
    .activity-item { display:flex;gap:12px;padding:13px 0;border-bottom:1px solid var(--border); }
    .activity-item:last-child { border-bottom:none; }
    .activity-dot  { width:34px;height:34px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:13px; }
    .activity-dot.green  { background:rgba(67,233,123,.15);color:var(--accent3); }
    .activity-dot.purple { background:rgba(108,99,255,.15);color:var(--accent); }
    .activity-dot.pink   { background:rgba(255,101,132,.15);color:var(--accent2); }
    .activity-dot.yellow { background:rgba(247,183,49,.15);color:#f7b731; }
    .activity-msg  { font-size:13px;font-weight:500;line-height:1.5; }
    .activity-msg strong { color:var(--text); }
    .activity-time { font-size:11px;color:var(--muted);margin-top:2px; }

    .donut-wrap { display:flex;align-items:center;gap:22px; }
    .donut { width:100px;height:100px;flex-shrink:0;border-radius:50%;background:conic-gradient(var(--accent) 0% 37%,var(--accent2) 37% 58%,var(--accent3) 58% 74%,#f7b731 74% 86%,#00c6ff 86% 92%,#6b7280 92% 100%);display:flex;align-items:center;justify-content:center;position:relative; }
    .donut::after { content:'';width:64px;height:64px;background:var(--surface);border-radius:50%;position:absolute; }
    .donut-center { position:relative;z-index:1;text-align:center; }
    .donut-center .val { font-size:17px;font-weight:800; }
    .donut-center .lbl { font-size:9px;color:var(--muted); }
    .donut-legend { display:flex;flex-direction:column;gap:7px; }
    .legend-item  { display:flex;align-items:center;gap:8px;font-size:12px; }
    .legend-dot   { width:8px;height:8px;border-radius:50%;flex-shrink:0; }
    .dept-list { display:flex;flex-direction:column;gap:12px;margin-top:20px; }
    .dept-info { display:flex;justify-content:space-between;margin-bottom:5px;font-size:13px; }
    .dept-bar-bg { height:5px;background:var(--surface2);border-radius:99px;overflow:hidden; }
    .dept-bar-fill { height:100%;border-radius:99px; }

    .page-header-greeting h1 { font-size:24px;font-weight:800; }
    .page-header-greeting p  { font-size:13px;color:var(--muted);margin-top:4px; }
    .date-badge { font-size:12px;color:var(--muted);background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:6px 12px;display:inline-flex;align-items:center;gap:6px; }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div class="page-header-greeting">
        <h1>
            @php
                $hour = now()->hour;
                if ($hour < 12)      echo __('admin.good_morning');
                elseif ($hour < 17)  echo __('admin.good_afternoon');
                else                  echo __('admin.good_evening');
            @endphp,
            {{ auth()->guard('admin')->user()->username ?? 'Admin' }} 👋
        </h1>
        <p>{{ __('admin.dashboard_subtitle') }}</p>
    </div>
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        <span class="date-badge">
            <i class="fas fa-calendar-alt"></i>
            {{ now()->locale(app()->getLocale())->isoFormat('dddd، D MMMM YYYY') }}
        </span>
        <a href="#" class="btn-primary">
            <i class="fas fa-plus"></i> {{ __('admin.new_deal') }}
        </a>
    </div>
</div>

{{-- Quick Actions --}}
<div class="quick-actions">
    <a href="{{ route('admin.employees.create') }}" class="quick-card">
        <i class="fas fa-user-plus" style="color:var(--accent);"></i>
        <span>{{ __('admin.add_employee') }}</span>
    </a>
    <a href="{{ route('admin.users.create') }}" class="quick-card">
        <i class="fas fa-user-circle" style="color:var(--accent3);"></i>
        <span>{{ __('admin.add_user') }}</span>
    </a>

    <a href="#" class="quick-card">
        <i class="fas fa-tasks" style="color:var(--accent2);"></i>
        <span>{{ __('admin.add_task') }}</span>
    </a>
    <a href="#" class="quick-card">
        <i class="fas fa-chart-bar" style="color:#00c6ff;"></i>
        <span>{{ __('admin.reports') }}</span>
    </a>
    <a href="#" class="quick-card">
        <i class="fas fa-handshake" style="color:#fc5c7d;"></i>
        <span>{{ __('admin.new_client') }}</span>
    </a>
</div>

{{-- Stat Cards --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-users"></i></div>
        <div class="stat-value">{{ $totalEmployees ?? 24 }}</div>
        <div class="stat-label">{{ __('admin.total_employees') }}</div>
        <div class="stat-change up"><i class="fas fa-arrow-up"></i> +3 {{ __('admin.this_month') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-handshake"></i></div>
        <div class="stat-value">{{ $activeDeals ?? 12 }}</div>
        <div class="stat-label">{{ __('admin.active_deals') }}</div>
        <div class="stat-change up"><i class="fas fa-arrow-up"></i> +5 {{ __('admin.this_week') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pink"><i class="fas fa-file-invoice-dollar"></i></div>
        <div class="stat-value">{{ number_format($totalRevenue ?? 84500) }}</div>
        <div class="stat-label">{{ __('admin.revenue') }}</div>
        <div class="stat-change up"><i class="fas fa-arrow-up"></i> +12%</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow"><i class="fas fa-user-circle"></i></div>
        <div class="stat-value">{{ $totalUsers ?? 187 }}</div>
        <div class="stat-label">{{ __('admin.app_users') }}</div>
        <div class="stat-change up"><i class="fas fa-arrow-up"></i> +18 {{ __('admin.new') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon cyan"><i class="fas fa-tasks"></i></div>
        <div class="stat-value">{{ $pendingTasks ?? 37 }}</div>
        <div class="stat-label">{{ __('admin.pending_tasks') }}</div>
        <div class="stat-change down"><i class="fas fa-arrow-down"></i> 5 {{ __('admin.overdue') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-percentage"></i></div>
        <div class="stat-value">{{ number_format($totalCommissions ?? 6200) }}</div>
        <div class="stat-label">{{ __('admin.commissions_due') }}</div>
        <div class="stat-change" style="color:var(--muted);"><i class="fas fa-minus"></i></div>
    </div>
</div>

{{-- Charts Row --}}
<div class="grid-2">
    {{-- Department Breakdown --}}
    <div class="card">
        <div class="section-header">
            <span class="section-title">{{ __('admin.employees_by_dept') }}</span>
            <a href="{{ route('admin.employees.index') }}" class="section-action">{{ __('admin.view_all') }} <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}"></i></a>
        </div>
        <div class="donut-wrap">
            <div class="donut"><div class="donut-center"><div class="val">24</div><div class="lbl">{{ __('admin.total') }}</div></div></div>
            <div class="donut-legend">
                <div class="legend-item"><div class="legend-dot" style="background:var(--accent)"></div> {{ __('admin.design') }} (9)</div>
                <div class="legend-item"><div class="legend-dot" style="background:var(--accent2)"></div> {{ __('admin.sales') }} (5)</div>
                <div class="legend-item"><div class="legend-dot" style="background:var(--accent3)"></div> {{ __('admin.development') }} (4)</div>
                <div class="legend-item"><div class="legend-dot" style="background:#f7b731"></div> {{ __('admin.marketing') }} (3)</div>
                <div class="legend-item"><div class="legend-dot" style="background:#00c6ff"></div> {{ __('admin.video') }} (2)</div>
                <div class="legend-item"><div class="legend-dot" style="background:#6b7280"></div> {{ __('admin.other') }} (1)</div>
            </div>
        </div>
        @php
            $depts = [
                ['key'=>'design','count'=>9,'color'=>'var(--accent)'],
                ['key'=>'sales','count'=>5,'color'=>'var(--accent2)'],
                ['key'=>'development','count'=>4,'color'=>'var(--accent3)'],
                ['key'=>'marketing','count'=>3,'color'=>'#f7b731'],
                ['key'=>'video','count'=>2,'color'=>'#00c6ff'],
            ];
        @endphp
        <div class="dept-list">
            @foreach($depts as $d)
            <div>
                <div class="dept-info">
                    <span style="font-weight:600;">{{ __('admin.' . $d['key']) }}</span>
                    <span style="color:var(--muted);">{{ $d['count'] }}</span>
                </div>
                <div class="dept-bar-bg">
                    <div class="dept-bar-fill" style="width:{{ round($d['count']/24*100) }}%;background:{{ $d['color'] }};"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Activity Feed --}}
    <div class="card">
        <div class="section-header">
            <span class="section-title">{{ __('admin.recent_activity') }}</span>
            <a href="#" class="section-action">{{ __('admin.view_all') }} <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}"></i></a>
        </div>
        <div class="activity-list">
            <div class="activity-item">
                <div class="activity-dot green"><i class="fas fa-user-plus"></i></div>
                <div><div class="activity-msg"><strong>Ahmed Al-Rashid</strong> {{ __('admin.activity_added_employee') }}</div><div class="activity-time">2 {{ __('admin.minutes_ago') }}</div></div>
            </div>
            <div class="activity-item">
                <div class="activity-dot purple"><i class="fas fa-handshake"></i></div>
                <div><div class="activity-msg"><strong>{{ __('admin.deal') }} #1042</strong> {{ __('admin.activity_deal_closed') }} — $12,000</div><div class="activity-time">18 {{ __('admin.minutes_ago') }}</div></div>
            </div>
          
            <div class="activity-item">
                <div class="activity-dot yellow"><i class="fas fa-tasks"></i></div>
                <div><div class="activity-msg"><strong>5 {{ __('admin.nav_tasks') }}</strong> {{ __('admin.activity_tasks_assigned') }}</div><div class="activity-time">3 {{ __('admin.hours_ago') }}</div></div>
            </div>
            <div class="activity-item">
                <div class="activity-dot green"><i class="fas fa-percentage"></i></div>
                <div><div class="activity-msg">{{ __('admin.commission') }} <strong>$420</strong> {{ __('admin.activity_commission_paid') }}</div><div class="activity-time">{{ __('admin.yesterday') }}</div></div>
            </div>
        </div>
    </div>
</div>

{{-- Employees Table --}}
<div class="card" style="margin-bottom:32px;">
    <div class="section-header">
        <span class="section-title">{{ __('admin.recent_employees') }}</span>
        <a href="{{ route('admin.employees.index') }}" class="section-action">{{ __('admin.view_all') }} <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}"></i></a>
    </div>
    <div class="table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>{{ __('admin.employee') }}</th>
                    <th>{{ __('admin.job_title') }}</th>
                    <th>{{ __('admin.department') }}</th>
                    <th>{{ __('admin.status') }}</th>
                    <th>{{ __('admin.hire_date') }}</th>
                    <th>{{ __('admin.salary') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentEmployees ?? [] as $emp)
                <tr>
                    <td>
                        <div class="emp-name-wrap">
                            <div class="emp-avatar-sm">{{ strtoupper(substr($emp->name, 0, 1)) }}</div>
                            <span style="font-weight:600;">{{ $emp->name }}</span>
                        </div>
                    </td>
                    <td style="color:var(--muted);">{{ $emp->job_title }}</td>
                    <td><span class="badge badge-{{ $emp->department }}">{{ __('admin.' . $emp->department) }}</span></td>
                    <td><span class="badge badge-{{ $emp->status }}">{{ __('admin.' . $emp->status) }}</span></td>
                    <td style="color:var(--muted);font-size:12px;">{{ $emp->hire_date?->format('Y-m-d') }}</td>
                    <td style="font-weight:700;">${{ number_format($emp->salary, 0) }}</td>
                    <td>
                        <a href="{{ route('admin.employees.show', $emp) }}" style="color:var(--muted);font-size:13px;" title="{{ __('admin.actions') }}">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                {{-- Fallback sample rows --}}
                @php
                    $sample = [
                        ['name'=>'Ahmed Al-Rashid','title'=>'Sales Manager','dept'=>'sales','status'=>'active','hire'=>'2024-01-15','salary'=>'5,200'],
                        ['name'=>'Sara Mohammed','title'=>'UI/UX Designer','dept'=>'design','status'=>'active','hire'=>'2023-08-01','salary'=>'4,800'],
                        ['name'=>'Omar Khalil','title'=>'Full-Stack Dev','dept'=>'development','status'=>'active','hire'=>'2023-05-10','salary'=>'6,500'],
                        ['name'=>'Lina Hassan','title'=>'Video Editor','dept'=>'video','status'=>'vacation','hire'=>'2024-03-20','salary'=>'3,900'],
                    ];
                @endphp
                @foreach($sample as $emp)
                <tr>
                    <td><div class="emp-name-wrap"><div class="emp-avatar-sm">{{ strtoupper(substr($emp['name'],0,1)) }}</div><span style="font-weight:600;">{{ $emp['name'] }}</span></div></td>
                    <td style="color:var(--muted);">{{ $emp['title'] }}</td>
                    <td><span class="badge badge-{{ $emp['dept'] }}">{{ __('admin.' . $emp['dept']) }}</span></td>
                    <td><span class="badge badge-{{ $emp['status'] }}">{{ __('admin.' . $emp['status']) }}</span></td>
                    <td style="color:var(--muted);font-size:12px;">{{ $emp['hire'] }}</td>
                    <td style="font-weight:700;">${{ $emp['salary'] }}</td>
                    <td><a href="#" style="color:var(--muted);font-size:13px;"><i class="fas fa-eye"></i></a></td>
                </tr>
                @endforeach
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection