<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon"><i class="fas fa-bolt"></i></div>
        <div>
            <div class="logo-text">NovaCRM</div>
            <div class="logo-sub">{{ __('admin.admin_panel') }}</div>
        </div>
    </div>

    <nav class="sidebar-nav">

        <div class="nav-section-label">{{ __('admin.nav_overview') }}</div>
        <a href="{{ route('admin.dashboard') }}"
           class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i><span>{{ __('admin.nav_dashboard') }}</span>
        </a>

        <div class="nav-section-label">{{ __('admin.nav_management') }}</div>
        <a href="{{ route('admin.clients.index') }}"
           class="nav-item {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
            <i class="fas fa-handshake"></i><span>{{ __('admin.nav_clients') }}</span>
        </a>
        <a href="{{ route('admin.tasks.index') }}"
           class="nav-item {{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}">
            <i class="fas fa-tasks"></i><span>{{ __('admin.nav_tasks') }}</span>
        </a>
        <a href="{{ route('admin.services.index') }}"
           class="nav-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
            <i class="fas fa-layer-group"></i><span>{{ __('admin.nav_services') }}</span>
        </a>
        <a href="{{ route('admin.employees.index') }}"
           class="nav-item {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i><span>{{ __('admin.nav_employees') }}</span>
        </a>
        <a href="{{ route('admin.users.index') }}"
           class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fas fa-user-circle"></i><span>{{ __('admin.nav_users') }}</span>
        </a>

        <div class="nav-section-label">{{ __('admin.nav_finance') }}</div>
        <a href="{{ route('admin.contracts.index') }}"
           class="nav-item {{ request()->routeIs('admin.contracts.*') ? 'active' : '' }}">
            <i class="fas fa-file-invoice-dollar"></i><span>{{ __('admin.nav_contracts') }}</span>
        </a>
        <a href="{{ route('admin.commissions.index') }}"
           class="nav-item {{ request()->routeIs('admin.commissions.*') ? 'active' : '' }}">
            <i class="fas fa-percentage"></i><span>{{ __('admin.nav_commissions') }}</span>
        </a>
        <a href="#" class="nav-item {{ request()->routeIs('admin.payroll.*') ? 'active' : '' }}">
            <i class="fas fa-wallet"></i><span>{{ __('admin.nav_payroll') }}</span>
        </a>

        <div class="nav-section-label">{{ __('admin.nav_system') }}</div>
        <a href="#" class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i><span>{{ __('admin.nav_reports') }}</span>
        </a>
        <a href="#" class="nav-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i><span>{{ __('admin.nav_settings') }}</span>
        </a>

    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="user-avatar">{{ strtoupper(substr(auth()->guard('admin')->user()->username ?? 'A', 0, 1)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ auth()->guard('admin')->user()->username ?? 'Admin' }}</div>
                <div class="user-role">{{ auth()->guard('admin')->user()->is_super ? __('admin.super_admin') : __('admin.admin') }}</div>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn" title="{{ __('admin.logout') }}">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>