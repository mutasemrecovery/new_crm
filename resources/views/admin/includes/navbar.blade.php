<header class="topbar">

    {{-- Mobile toggle --}}
    <button class="topbar-toggle" onclick="toggleSidebar()" aria-label="Toggle menu">
        <i class="fas fa-bars"></i>
    </button>

    {{-- Page title (injected from each view) --}}
    <span class="topbar-title">@yield('page-title', __('admin.dashboard'))</span>

    {{-- Search --}}
    <div class="topbar-search">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="{{ __('admin.search_placeholder') }}">
    </div>

    {{-- Right actions --}}
    <div class="topbar-actions">

        {{-- Language Switcher --}}
        <div class="lang-switcher">
            @if(app()->getLocale() === 'ar')
                <a href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}"
                   class="lang-btn" title="English">
                    <span class="lang-flag">🇬🇧</span>
                    <span class="lang-label">EN</span>
                </a>
            @else
                <a href="{{ LaravelLocalization::getLocalizedURL('ar', null, [], true) }}"
                   class="lang-btn" title="العربية">
                    <span class="lang-flag">🇸🇦</span>
                    <span class="lang-label">AR</span>
                </a>
            @endif
        </div>

        {{-- Notifications --}}
        <div class="notif-wrap">
            <button class="topbar-btn" id="notifBtn" onclick="toggleNotif()" aria-label="{{ __('admin.notifications') }}">
                <i class="fas fa-bell"></i>
                <span class="topbar-notif-dot"></span>
            </button>
            <div class="notif-dropdown" id="notifDropdown">
                <div class="notif-header">
                    <span>{{ __('admin.notifications') }}</span>
                    <a href="#">{{ __('admin.mark_all_read') }}</a>
                </div>
                <div class="notif-list">
                    <div class="notif-item unread">
                        <div class="notif-icon green"><i class="fas fa-user-plus"></i></div>
                        <div class="notif-body">
                            <p>{{ __('admin.notif_new_employee') }}</p>
                            <span>2 {{ __('admin.minutes_ago') }}</span>
                        </div>
                    </div>
                    <div class="notif-item unread">
                        <div class="notif-icon purple"><i class="fas fa-handshake"></i></div>
                        <div class="notif-body">
                            <p>{{ __('admin.notif_deal_closed') }}</p>
                            <span>18 {{ __('admin.minutes_ago') }}</span>
                        </div>
                    </div>
                    <div class="notif-item">
                        <div class="notif-icon yellow"><i class="fas fa-tasks"></i></div>
                        <div class="notif-body">
                            <p>{{ __('admin.notif_tasks_assigned') }}</p>
                            <span>1 {{ __('admin.hour_ago') }}</span>
                        </div>
                    </div>
                </div>
                <div class="notif-footer">
                    <a href="#">{{ __('admin.view_all_notifications') }}</a>
                </div>
            </div>
        </div>

        {{-- Messages --}}
        <a href="#" class="topbar-btn" title="{{ __('admin.messages') }}">
            <i class="fas fa-envelope"></i>
        </a>

        {{-- Admin dropdown --}}
        <div class="admin-menu-wrap">
            <button class="admin-avatar-btn" onclick="toggleAdminMenu()">
                <div class="topbar-avatar">
                    {{ strtoupper(substr(auth()->guard('admin')->user()->username ?? 'A', 0, 1)) }}
                </div>
                <i class="fas fa-chevron-down" style="font-size:10px;"></i>
            </button>
            <div class="admin-dropdown" id="adminDropdown">
                <div class="admin-dropdown-header">
                    <strong>{{ auth()->guard('admin')->user()->username ?? 'Admin' }}</strong>
                    <span>{{ auth()->guard('admin')->user()->is_super ? __('admin.super_admin') : __('admin.admin') }}</span>
                </div>
                <a href="#"><i class="fas fa-user-cog"></i> {{ __('admin.profile') }}</a>
                <a href="#"><i class="fas fa-cog"></i> {{ __('admin.settings') }}</a>
                <hr>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-logout">
                        <i class="fas fa-sign-out-alt"></i> {{ __('admin.logout') }}
                    </button>
                </form>
            </div>
        </div>

    </div>
</header>