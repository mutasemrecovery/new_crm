@php
    $employee = auth()->user()?->employee;
    $isSales  = $employee?->is_sales ?? false;
@endphp

<aside class="sidebar" id="sidebar">

    {{-- Logo --}}
    <div class="sidebar-logo">
        <div class="logo-icon"><i class="fas fa-bolt"></i></div>
        <div>
            <div class="logo-text">RecoveryCRM</div>
            <div class="logo-sub">{{ __('emp.portal') }}</div>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="sidebar-nav">

        <div class="nav-section-label">{{ __('emp.nav_workspace') }}</div>

        <a href="{{ route('employee.dashboard') }}"
           class="nav-item {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>{{ __('emp.nav_dashboard') }}</span>
        </a>

        <a href="{{ route('employee.tasks.index') }}"
           class="nav-item {{ request()->routeIs('employee.tasks.*') ? 'active' : '' }}">
            <i class="fas fa-check-square"></i>
            <span>{{ __('emp.nav_tasks') }}</span>
            @if(($pendingTasksCount ?? 0) > 0)
                <span class="nav-badge">{{ $pendingTasksCount }}</span>
            @endif
        </a>

        <a href="{{ route('employee.attendance.index') }}"
           class="nav-item {{ request()->routeIs('employee.attendance.*') ? 'active' : '' }}">
            <i class="fas fa-clock"></i>
            <span>{{ __('emp.nav_attendance') }}</span>
        </a>

        {{-- Sales section (only for is_sales employees) --}}
        @if($isSales)
        <div class="nav-section-label">{{ __('emp.nav_sales') }}</div>

        <a href="{{ route('employee.deals.index') }}"
           class="nav-item {{ request()->routeIs('employee.deals.*') ? 'active' : '' }}">
            <i class="fas fa-suitcase"></i>
            <span>{{ __('emp.nav_deals') }}</span>
        </a>

        <a href="{{ route('employee.commissions.index') }}"
           class="nav-item {{ request()->routeIs('employee.commissions.*') ? 'active' : '' }}">
            <i class="fas fa-percentage"></i>
            <span>{{ __('emp.nav_commissions') }}</span>
        </a>

        <a href="{{ route('employee.clients.index') }}"
           class="nav-item {{ request()->routeIs('employee.clients.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>{{ __('emp.nav_clients') }}</span>
        </a>
        @endif

        <div class="nav-section-label">{{ __('emp.nav_personal') }}</div>

        <a href="{{ route('employee.leaves.index') }}"
           class="nav-item {{ request()->routeIs('employee.leaves.*') ? 'active' : '' }}">
            <i class="fas fa-umbrella-beach"></i>
            <span>{{ __('emp.nav_leaves') }}</span>
        </a>

        <a href="{{ route('employee.payroll.index') }}"
           class="nav-item {{ request()->routeIs('employee.payroll.*') ? 'active' : '' }}">
            <i class="fas fa-wallet"></i>
            <span>{{ __('emp.nav_payroll') }}</span>
        </a>

        <a href="{{ route('employee.profile') }}"
           class="nav-item {{ request()->routeIs('employee.profile*') ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            <span>{{ __('emp.nav_profile') }}</span>
        </a>

    </nav>

    {{-- Footer --}}
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="user-avatar">
                @if(auth()->user()?->photo)
                    <img src="{{ asset(auth()->user()->photo) }}" alt="">
                @else
                    {{ strtoupper(substr(auth()->user()->name ?? 'E', 0, 1)) }}
                @endif
            </div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name ?? '' }}</div>
                <div class="user-role">{{ $employee?->job_title ?? __('emp.employee') }}</div>
            </div>
            <form action="{{ route('employee.logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn" title="{{ __('emp.logout') }}">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>

</aside>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>