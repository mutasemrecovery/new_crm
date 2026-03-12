<header class="topbar">

    <button class="topbar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <span class="topbar-title">@yield('page-title', __('emp.dashboard'))</span>

    {{-- Dept badge --}}
    @php $dept = auth()->user()?->employee?->department; @endphp
    @if($dept)
    <span class="dept-pill dept-{{ $dept }}">
        {{ __('admin.' . $dept) }}
    </span>
    @endif

    <div class="topbar-actions">

        {{-- Language switcher --}}
        <div class="lang-switcher">
            @if(app()->getLocale() === 'ar')
                <a href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}" class="lang-btn" title="English">
                    <span>🇬🇧</span> <span class="lang-label">EN</span>
                </a>
            @else
                <a href="{{ LaravelLocalization::getLocalizedURL('ar', null, [], true) }}" class="lang-btn" title="العربية">
                    <span>🇸🇦</span> <span class="lang-label">AR</span>
                </a>
            @endif
        </div>

        {{-- Notifications --}}
        <div class="notif-wrap">
            <button class="topbar-btn" onclick="toggleNotif()" id="notifBtn">
                <i class="fas fa-bell"></i>
                @if(($unreadNotifs ?? 0) > 0)
                    <span class="topbar-notif-dot"></span>
                @endif
            </button>
            <div class="notif-dropdown" id="notifDropdown">
                <div class="notif-header">
                    <span>{{ __('emp.notifications') }}</span>
                    <a href="{{ route('employee.notifications.readAll') }}" onclick="event.preventDefault(); this.closest('form')?.submit()">
                        {{ __('emp.mark_all_read') }}
                    </a>
                </div>
                <div class="notif-list">
                    <div class="notif-item unread">
                        <div class="notif-icon indigo"><i class="fas fa-tasks"></i></div>
                        <div class="notif-body">
                            <p>{{ __('emp.notif_new_task') }}</p>
                            <span>{{ __('emp.just_now') }}</span>
                        </div>
                    </div>
                    <div class="notif-item">
                        <div class="notif-icon green"><i class="fas fa-check-circle"></i></div>
                        <div class="notif-body">
                            <p>{{ __('emp.notif_leave_approved') }}</p>
                            <span>2 {{ __('emp.hours_ago') }}</span>
                        </div>
                    </div>
                </div>
                <div class="notif-footer">
                    <a href="{{ route('employee.notifications.index') }}">{{ __('emp.view_all') }}</a>
                </div>
            </div>
        </div>

        {{-- User menu --}}
        <div class="user-menu-wrap">
            <button class="user-avatar-btn" onclick="toggleUserMenu()">
                <div class="topbar-avatar">
                    @if(auth()->user()?->photo)
                        <img src="{{ asset(auth()->user()->photo) }}" alt="">
                    @else
                        {{ strtoupper(substr(auth()->user()->name ?? 'E', 0, 1)) }}
                    @endif
                </div>
                <i class="fas fa-chevron-down" style="font-size:9px;color:var(--muted);"></i>
            </button>
            <div class="user-dropdown" id="userDropdown">
                <div class="user-dropdown-header">
                    <strong>{{ auth()->user()->name }}</strong>
                    <span>{{ auth()->user()->employee?->job_title ?? __('emp.employee') }}</span>
                </div>
                <a href="{{ route('employee.profile') }}"><i class="fas fa-user"></i> {{ __('emp.nav_profile') }}</a>
                <hr>
                <form action="{{ route('employee.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-logout">
                        <i class="fas fa-sign-out-alt"></i> {{ __('emp.logout') }}
                    </button>
                </form>
            </div>
        </div>

    </div>
</header>