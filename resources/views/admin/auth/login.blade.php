<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('admin.login') }} — RecoveryCRM</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    @if(app()->getLocale() === 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* ── VARIABLES ── */
        :root {
            --bg:       #080a0f;
            --surface:  #0f1218;
            --surface2: #161b24;
            --border:   rgba(255,255,255,0.07);
            --accent:   #6c63ff;
            --accent2:  #ff6584;
            --accent3:  #43e97b;
            --text:     #eaedf5;
            --muted:    #5a6275;
            --error:    #ff6584;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { height: 100%; }

        body {
            font-family: {{ app()->getLocale() === 'ar' ? "'Cairo'" : "'DM Sans'" }}, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: stretch;
            overflow: hidden;
        }

        /* ════════════════════════════════════════
           LEFT PANEL — Decorative / Brand
        ════════════════════════════════════════ */
        .left-panel {
            flex: 1;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px;
            overflow: hidden;
        }

        /* Animated mesh background */
        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 20%, rgba(108,99,255,.25) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 80% 80%, rgba(255,101,132,.18) 0%, transparent 55%),
                radial-gradient(ellipse 50% 50% at 50% 50%, rgba(67,233,123,.08) 0%, transparent 60%);
            animation: meshShift 8s ease-in-out infinite alternate;
        }

        @keyframes meshShift {
            0%   { opacity: 1; transform: scale(1) rotate(0deg); }
            100% { opacity: .85; transform: scale(1.05) rotate(2deg); }
        }

        /* Grid lines */
        .left-panel::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        /* Floating orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            animation: float 6s ease-in-out infinite;
            pointer-events: none;
        }
        .orb-1 { width: 300px; height: 300px; background: rgba(108,99,255,.2); top: 5%; left: -10%; animation-delay: 0s; }
        .orb-2 { width: 200px; height: 200px; background: rgba(255,101,132,.15); bottom: 15%; right: -5%; animation-delay: 2s; }
        .orb-3 { width: 150px; height: 150px; background: rgba(67,233,123,.12); top: 55%; left: 35%; animation-delay: 4s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50%       { transform: translateY(-25px) scale(1.05); }
        }

        /* Brand content */
        .brand-top { position: relative; z-index: 2; }
        .brand-logo {
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }
        .brand-icon {
            width: 46px; height: 46px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: #fff;
            box-shadow: 0 8px 28px rgba(108,99,255,.45);
        }
        .brand-name { font-family: 'Syne', 'Cairo', sans-serif; font-size: 22px; font-weight: 800; color: #fff; }
        .brand-tagline { font-size: 11px; color: var(--muted); letter-spacing: 2.5px; text-transform: uppercase; margin-top: 2px; }

        .brand-center { position: relative; z-index: 2; }
        .brand-headline {
            font-family: 'Syne', 'Cairo', sans-serif;
            font-size: clamp(28px, 3vw, 42px);
            font-weight: 800;
            line-height: 1.2;
            color: #fff;
            margin-bottom: 18px;
        }
        .brand-headline span {
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .brand-desc {
            font-size: 15px;
            color: rgba(255,255,255,.5);
            line-height: 1.7;
            max-width: 380px;
        }

        /* Feature pills */
        .brand-features {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 36px;
        }
        .feature-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 13px;
            font-weight: 500;
            color: rgba(255,255,255,.7);
            backdrop-filter: blur(10px);
            width: fit-content;
            animation: slideInFeature .5s ease both;
        }
        .feature-pill:nth-child(1) { animation-delay: .1s; }
        .feature-pill:nth-child(2) { animation-delay: .2s; }
        .feature-pill:nth-child(3) { animation-delay: .3s; }

        @keyframes slideInFeature {
            from { opacity: 0; transform: translateX(-16px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .feature-pill i {
            width: 28px; height: 28px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
        }
        .fi-purple { background: rgba(108,99,255,.2); color: var(--accent); }
        .fi-pink   { background: rgba(255,101,132,.2); color: var(--accent2); }
        .fi-green  { background: rgba(67,233,123,.2);  color: var(--accent3); }

        .brand-bottom { position: relative; z-index: 2; }
        .brand-stats {
            display: flex;
            gap: 32px;
        }
        .stat-mini { }
        .stat-mini-val { font-family: 'Syne', 'Cairo', sans-serif; font-size: 24px; font-weight: 800; color: #fff; }
        .stat-mini-lbl { font-size: 12px; color: var(--muted); margin-top: 2px; }

        /* ════════════════════════════════════════
           RIGHT PANEL — Login Form
        ════════════════════════════════════════ */
        .right-panel {
            width: 480px;
            min-height: 100vh;
            background: var(--surface);
            border-left: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 52px 48px;
            position: relative;
            overflow-y: auto;
        }

        html[dir="rtl"] .right-panel {
            border-left: none;
            border-right: 1px solid var(--border);
        }

        /* Subtle top glow on form panel */
        .right-panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--accent), var(--accent2), transparent);
        }

        /* Language switcher inside form */
        .lang-switch-row {
            position: absolute;
            top: 24px;
            right: 24px;
            display: flex;
            gap: 6px;
        }
        html[dir="rtl"] .lang-switch-row { right: auto; left: 24px; }

        .lang-pill {
            display: flex; align-items: center; gap: 5px;
            padding: 5px 11px;
            border-radius: 8px;
            font-size: 12px; font-weight: 700;
            text-decoration: none;
            transition: all .2s;
            border: 1px solid var(--border);
            color: var(--muted);
            background: var(--surface2);
        }
        .lang-pill.active,
        .lang-pill:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: rgba(108,99,255,.08);
        }

        /* Form content */
        .form-box {
            animation: fadeUp .5s ease both;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .form-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(108,99,255,.1);
            border: 1px solid rgba(108,99,255,.2);
            border-radius: 20px;
            padding: 5px 14px;
            font-size: 11px; font-weight: 700;
            color: var(--accent);
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .form-eyebrow i { font-size: 10px; }

        .form-heading {
            font-family: 'Syne', 'Cairo', sans-serif;
            font-size: 30px;
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 8px;
        }
        .form-subheading {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 36px;
            line-height: 1.6;
        }

        /* Error banner */
        .error-banner {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,101,132,.08);
            border: 1px solid rgba(255,101,132,.25);
            border-radius: 11px;
            padding: 13px 16px;
            margin-bottom: 22px;
            font-size: 13px;
            color: var(--error);
            animation: shake .4s ease;
        }
        @keyframes shake {
            0%,100%{ transform:translateX(0) }
            20%    { transform:translateX(-6px) }
            40%    { transform:translateX(6px) }
            60%    { transform:translateX(-4px) }
            80%    { transform:translateX(4px) }
        }
        .error-banner i { font-size: 16px; flex-shrink: 0; }

        /* Form group */
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: rgba(255,255,255,.75);
        }

        /* Input wrapper */
        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-icon {
            position: absolute;
            left: 15px;
            color: var(--muted);
            font-size: 15px;
            pointer-events: none;
            transition: color .2s;
            z-index: 1;
        }
        html[dir="rtl"] .input-icon { left: auto; right: 15px; }

        .form-input {
            width: 100%;
            background: var(--surface2);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 14px 46px;
            font-size: 14px;
            color: var(--text);
            font-family: inherit;
            outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }
        .form-input::placeholder { color: var(--muted); }
        .form-input:focus {
            border-color: var(--accent);
            background: rgba(108,99,255,.05);
            box-shadow: 0 0 0 4px rgba(108,99,255,.12);
        }
        .form-input:focus + .input-line { width: 100%; }
        .form-input.is-invalid { border-color: var(--error); }
        .form-input.is-invalid:focus { box-shadow: 0 0 0 4px rgba(255,101,132,.12); }

        /* Input wrap focus — icon changes color */
        .input-wrap:focus-within .input-icon { color: var(--accent); }

        /* Password toggle */
        .pw-toggle {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            color: var(--muted);
            font-size: 15px;
            cursor: pointer;
            transition: color .2s;
            padding: 4px;
            z-index: 1;
        }
        html[dir="rtl"] .pw-toggle { right: auto; left: 14px; }
        .pw-toggle:hover { color: var(--text); }

        /* Field error */
        .field-error {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: var(--error);
            margin-top: 6px;
        }

        /* Remember row */
        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 9px;
            cursor: pointer;
            font-size: 13px;
            color: var(--muted);
            user-select: none;
        }
        .checkbox-label input[type="checkbox"] { display: none; }
        .custom-checkbox {
            width: 18px; height: 18px;
            border: 1.5px solid var(--border);
            border-radius: 5px;
            background: var(--surface2);
            display: flex; align-items: center; justify-content: center;
            transition: all .2s;
            flex-shrink: 0;
        }
        .checkbox-label input:checked + .custom-checkbox {
            background: var(--accent);
            border-color: var(--accent);
        }
        .checkbox-label input:checked + .custom-checkbox::after {
            content: '✓';
            color: #fff;
            font-size: 11px;
            font-weight: 700;
        }

        /* Submit button */
        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, var(--accent) 0%, #8b7eff 50%, var(--accent2) 100%);
            background-size: 200% 200%;
            color: #fff;
            border: none;
            border-radius: 13px;
            padding: 15px 24px;
            font-size: 15px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all .3s ease;
            box-shadow: 0 6px 24px rgba(108,99,255,.4);
            position: relative;
            overflow: hidden;
            letter-spacing: .3px;
        }
        .btn-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,.08);
            opacity: 0;
            transition: opacity .2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 32px rgba(108,99,255,.5);
            background-position: right center;
        }
        .btn-login:hover::before { opacity: 1; }
        .btn-login:active { transform: translateY(0); }

        /* Loading state */
        .btn-login.loading .btn-text { opacity: 0; }
        .btn-login.loading .btn-spinner { display: block; }
        .btn-spinner {
            display: none;
            position: absolute;
            width: 20px; height: 20px;
            border: 2.5px solid rgba(255,255,255,.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 28px 0;
            color: var(--muted);
            font-size: 12px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* Security note */
        .security-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 28px;
            font-size: 12px;
            color: var(--muted);
        }
        .security-note i { color: var(--accent3); }

        /* ── RESPONSIVE ── */
        @media (max-width: 900px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; border: none; padding: 40px 28px; }
        }
        @media (max-width: 480px) {
            .right-panel { padding: 32px 20px; }
            .form-heading { font-size: 24px; }
        }
    </style>
</head>
<body>

    <!-- ═══ LEFT PANEL ═══ -->
    <div class="left-panel">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>

        <!-- Brand logo -->
        <div class="brand-top">
            <div class="brand-logo">
                <div class="brand-icon"><i class="fas fa-bolt"></i></div>
                <div>
                    <div class="brand-name">RecoveryCRM</div>
                    <div class="brand-tagline">{{ __('admin.admin_panel') }}</div>
                </div>
            </div>
        </div>

        <!-- Headline -->
        <div class="brand-center">
            @if(app()->getLocale() === 'ar')
                <div class="brand-headline">
                    إدارة شركتك<br><span>بذكاء وكفاءة</span>
                </div>
                <p class="brand-desc">
                    منصة متكاملة لإدارة الموظفين، العملاء، الصفقات، والرواتب — كل شيء في مكان واحد.
                </p>
            @else
                <div class="brand-headline">
                    Manage your business<br><span>with precision.</span>
                </div>
                <p class="brand-desc">
                    A complete platform for managing employees, clients, deals, and payroll — all in one place.
                </p>
            @endif

            <div class="brand-features">
                <div class="feature-pill">
                    <i class="fas fa-users fi-purple"></i>
                    {{ __('admin.nav_employees') }} & {{ __('admin.nav_payroll') }}
                </div>
                <div class="feature-pill">
                    <i class="fas fa-handshake fi-pink"></i>
                    {{ __('admin.nav_clients') }} & {{ __('admin.nav_deals') }}
                </div>
                <div class="feature-pill">
                    <i class="fas fa-chart-bar fi-green"></i>
                    {{ __('admin.nav_reports') }} & {{ __('admin.nav_commissions') }}
                </div>
            </div>
        </div>

        <!-- Bottom stats -->
        <div class="brand-bottom">
            <div class="brand-stats">
                <div class="stat-mini">
                    <div class="stat-mini-val">24+</div>
                    <div class="stat-mini-lbl">{{ __('admin.total_employees') }}</div>
                </div>
                <div class="stat-mini">
                    <div class="stat-mini-val">100%</div>
                    <div class="stat-mini-lbl">{{ app()->getLocale() === 'ar' ? 'آمن' : 'Secure' }}</div>
                </div>
                <div class="stat-mini">
                    <div class="stat-mini-val">EN/AR</div>
                    <div class="stat-mini-lbl">{{ app()->getLocale() === 'ar' ? 'متعدد اللغات' : 'Bilingual' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ RIGHT PANEL — FORM ═══ -->
    <div class="right-panel">

        <!-- Language switcher -->
        <div class="lang-switch-row">
            <a href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}"
               class="lang-pill {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                🇬🇧 EN
            </a>
            <a href="{{ LaravelLocalization::getLocalizedURL('ar', null, [], true) }}"
               class="lang-pill {{ app()->getLocale() === 'ar' ? 'active' : '' }}">
                🇸🇦 AR
            </a>
        </div>

        <div class="form-box">

            <!-- Eyebrow -->
            <div class="form-eyebrow">
                <i class="fas fa-shield-halved"></i>
                {{ app()->getLocale() === 'ar' ? 'منطقة مشرفين' : 'Admin Area' }}
            </div>

            <!-- Heading -->
            <h1 class="form-heading">
                {{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Welcome back' }}
            </h1>
            <p class="form-subheading">
                {{ app()->getLocale() === 'ar'
                    ? 'أدخل بيانات حسابك للوصول إلى لوحة التحكم'
                    : 'Sign in to your admin account to continue.' }}
            </p>

            {{-- ── Error banner ── --}}
            @if($errors->any() || session('error'))
            <div class="error-banner">
                <i class="fas fa-circle-exclamation"></i>
                <span>
                    {{ $errors->first() ?? session('error') }}
                </span>
            </div>
            @endif

            {{-- ── Login Form ── --}}
            <form action="{{ route('admin.login') }}" method="POST" id="loginForm" novalidate>
                @csrf

                {{-- Username --}}
                <div class="form-group">
                    <label class="form-label" for="username">
                        {{ app()->getLocale() === 'ar' ? 'اسم المستخدم' : 'Username' }}
                    </label>
                    <div class="input-wrap">
                        <i class="fas fa-user input-icon"></i>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="form-input {{ $errors->has('username') ? 'is-invalid' : '' }}"
                            value="{{ old('username') }}"
                            placeholder="{{ app()->getLocale() === 'ar' ? 'اسم المستخدم...' : 'Enter your username...' }}"
                            autocomplete="username"
                            autofocus
                            required
                        >
                    </div>
                    @error('username')
                        <div class="field-error">
                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label class="form-label" for="password">
                        {{ app()->getLocale() === 'ar' ? 'كلمة المرور' : 'Password' }}
                    </label>
                    <div class="input-wrap">
                        <i class="fas fa-lock input-icon"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            placeholder="{{ app()->getLocale() === 'ar' ? 'كلمة المرور...' : 'Enter your password...' }}"
                            autocomplete="current-password"
                            required
                        >
                        <button type="button" class="pw-toggle" onclick="togglePassword()" id="pwToggle" aria-label="Toggle password">
                            <i class="fas fa-eye" id="pwIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="field-error">
                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="form-row">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <div class="custom-checkbox"></div>
                        {{ app()->getLocale() === 'ar' ? 'تذكرني' : 'Remember me' }}
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-login" id="loginBtn">
                    <span class="btn-spinner"></span>
                    <span class="btn-text" style="display:flex;align-items:center;gap:9px;">
                        <i class="fas fa-right-to-bracket"></i>
                        {{ app()->getLocale() === 'ar' ? 'دخول' : 'Sign In' }}
                    </span>
                </button>
            </form>

            <div class="divider">
                {{ app()->getLocale() === 'ar' ? 'نظام آمن' : 'Secure System' }}
            </div>

            <div class="security-note">
                <i class="fas fa-shield-halved"></i>
                {{ app()->getLocale() === 'ar'
                    ? 'هذه المنطقة محمية ومخصصة للمشرفين فقط'
                    : 'This area is protected and restricted to authorized admins only.' }}
            </div>

        </div>
    </div>

    <script>
        // ── Password toggle ──────────────────────
        function togglePassword() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('pwIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        // ── Loading state on submit ──────────────
        document.getElementById('loginForm').addEventListener('submit', function () {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            btn.disabled = true;
            // Re-enable after 5s as fallback
            setTimeout(() => {
                btn.classList.remove('loading');
                btn.disabled = false;
            }, 5000);
        });

        // ── Custom checkbox toggle ────────────────
        document.querySelectorAll('.checkbox-label').forEach(label => {
            label.addEventListener('click', function () {
                const cb = this.querySelector('input[type="checkbox"]');
                cb.checked = !cb.checked;
            });
        });
    </script>
</body>
</html>