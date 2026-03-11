<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ app()->getLocale() === 'ar' ? 'تسجيل دخول الموظف' : 'Employee Login' }} — NovaCRM</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    @if(app()->getLocale() === 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --bg:       #f0f2f8;
            --surface:  #ffffff;
            --surface2: #f5f6fb;
            --border:   #e2e5ef;
            --accent:   #4f46e5;
            --accent-h: #3730a3;
            --accent2:  #e11d48;
            --accent3:  #059669;
            --text:     #111827;
            --muted:    #6b7280;
            --shadow:   0 8px 40px rgba(79,70,229,.12);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { height: 100%; }

        body {
            font-family: {{ app()->getLocale() === 'ar' ? "'Cairo'" : "'Plus Jakarta Sans'" }}, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }

        /* ── Soft background shapes ── */
        body::before {
            content: '';
            position: fixed;
            top: -200px; left: -200px;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(79,70,229,.08) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -200px; right: -150px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(225,29,72,.06) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        /* ── Floating dots ── */
        .dot {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            opacity: .5;
            animation: driftDot linear infinite;
        }
        .dot-1  { width:8px;  height:8px;  background:var(--accent);  top:15%;  left:8%;   animation-duration:18s; animation-delay:0s; }
        .dot-2  { width:5px;  height:5px;  background:var(--accent2); top:70%;  left:5%;   animation-duration:22s; animation-delay:3s; }
        .dot-3  { width:10px; height:10px; background:var(--accent3); top:30%;  right:6%;  animation-duration:15s; animation-delay:1s; }
        .dot-4  { width:6px;  height:6px;  background:var(--accent);  bottom:20%; right:10%; animation-duration:20s; animation-delay:5s; }
        .dot-5  { width:4px;  height:4px;  background:var(--accent2); top:55%;  left:90%;  animation-duration:25s; animation-delay:2s; }

        @keyframes driftDot {
            0%   { transform: translateY(0) rotate(0deg); opacity: .4; }
            50%  { transform: translateY(-40px) rotate(180deg); opacity: .7; }
            100% { transform: translateY(0) rotate(360deg); opacity: .4; }
        }

        /* ═══ CARD CONTAINER ═══ */
        .login-wrap {
            width: 100%;
            max-width: 960px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: var(--surface);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 24px 80px rgba(79,70,229,.14), 0 4px 16px rgba(0,0,0,.06);
            position: relative;
            z-index: 1;
            animation: cardIn .5s cubic-bezier(.34,1.56,.64,1) both;
        }
        @keyframes cardIn {
            from { opacity: 0; transform: scale(.94) translateY(20px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }

        /* ═══ LEFT — Info panel ═══ */
        .info-panel {
            background: linear-gradient(155deg, var(--accent) 0%, #312e81 100%);
            padding: 52px 44px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        /* Decorative circles */
        .info-panel::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 280px; height: 280px;
            border-radius: 50%;
            border: 40px solid rgba(255,255,255,.07);
        }
        .info-panel::after {
            content: '';
            position: absolute;
            bottom: -100px; left: -60px;
            width: 320px; height: 320px;
            border-radius: 50%;
            border: 50px solid rgba(255,255,255,.05);
        }

        /* Small accent ring */
        .ring {
            position: absolute;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,.1);
            pointer-events: none;
        }
        .ring-1 { width:120px; height:120px; top:38%; left:60%; }
        .ring-2 { width:60px;  height:60px;  top:20%; left:72%; }

        .info-logo {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            position: relative; z-index: 2;
        }
        .info-logo-icon {
            width: 44px; height: 44px;
            background: rgba(255,255,255,.15);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: #fff;
            backdrop-filter: blur(10px);
        }
        .info-logo-name { font-size: 20px; font-weight: 800; color: #fff; }
        .info-logo-sub  { font-size: 10px; color: rgba(255,255,255,.55); letter-spacing: 2px; text-transform: uppercase; }

        .info-content { position: relative; z-index: 2; }
        .info-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.18);
            border-radius: 20px;
            padding: 5px 14px;
            font-size: 11px; font-weight: 700; color: rgba(255,255,255,.9);
            letter-spacing: 1px; text-transform: uppercase;
            margin-bottom: 18px;
        }
        .info-heading {
            font-size: clamp(22px, 2.5vw, 30px);
            font-weight: 800;
            color: #fff;
            line-height: 1.25;
            margin-bottom: 14px;
        }
        .info-heading em {
            font-style: normal;
            color: rgba(255,255,255,.55);
            font-weight: 400;
        }
        .info-desc {
            font-size: 14px;
            color: rgba(255,255,255,.6);
            line-height: 1.7;
            margin-bottom: 32px;
        }

        /* Profile previews */
        .profile-stack {
            display: flex;
            align-items: center;
            margin-bottom: 28px;
        }
        .profile-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(255,255,255,.3), rgba(255,255,255,.1));
            border: 2px solid rgba(255,255,255,.3);
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; color: #fff;
            margin-inline-end: -10px;
            position: relative;
        }
        .profile-avatar:nth-child(1) { background: linear-gradient(135deg, #667eea, #764ba2); z-index: 4; }
        .profile-avatar:nth-child(2) { background: linear-gradient(135deg, #f093fb, #f5576c); z-index: 3; }
        .profile-avatar:nth-child(3) { background: linear-gradient(135deg, #4facfe, #00f2fe); z-index: 2; }
        .profile-avatar:nth-child(4) { background: linear-gradient(135deg, #43e97b, #38f9d7); z-index: 1; }
        .profile-count {
            font-size: 12px; color: rgba(255,255,255,.65);
            margin-inline-start: 18px;
        }

        /* Info cards */
        .info-cards { display: flex; flex-direction: column; gap: 10px; }
        .info-card {
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 12px;
            padding: 13px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            backdrop-filter: blur(8px);
            animation: slideInfoCard .5s ease both;
        }
        .info-card:nth-child(1) { animation-delay: .15s; }
        .info-card:nth-child(2) { animation-delay: .25s; }
        .info-card:nth-child(3) { animation-delay: .35s; }
        @keyframes slideInfoCard {
            from { opacity: 0; transform: translateX(-12px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        html[dir="rtl"] .info-card { animation-name: slideInfoCardRtl; }
        @keyframes slideInfoCardRtl {
            from { opacity: 0; transform: translateX(12px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .info-card-icon {
            width: 34px; height: 34px;
            border-radius: 9px;
            background: rgba(255,255,255,.12);
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; color: #fff; flex-shrink: 0;
        }
        .info-card-text { font-size: 13px; font-weight: 500; color: rgba(255,255,255,.8); }
        .info-card-sub  { font-size: 11px; color: rgba(255,255,255,.45); margin-top: 1px; }

        .info-bottom { position: relative; z-index: 2; }
        .info-dept-row { display: flex; gap: 8px; flex-wrap: wrap; }
        .dept-tag {
            font-size: 11px; font-weight: 600;
            padding: 4px 12px; border-radius: 20px;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.15);
            color: rgba(255,255,255,.75);
        }

        /* ═══ RIGHT — Form ═══ */
        .form-panel {
            padding: 52px 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            background: var(--surface);
        }

        /* Top accent line */
        .form-panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent), transparent);
        }
        html[dir="rtl"] .form-panel::before {
            background: linear-gradient(270deg, var(--accent), transparent);
        }

        /* Lang switcher */
        .lang-row {
            position: absolute;
            top: 22px; right: 22px;
            display: flex; gap: 6px;
        }
        html[dir="rtl"] .lang-row { right: auto; left: 22px; }

        .lang-btn {
            display: flex; align-items: center; gap: 5px;
            padding: 5px 12px; border-radius: 8px;
            font-size: 11px; font-weight: 700;
            text-decoration: none;
            border: 1px solid var(--border);
            color: var(--muted);
            background: var(--surface2);
            transition: all .2s;
        }
        .lang-btn.active,
        .lang-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: rgba(79,70,229,.06);
        }

        .form-content { animation: fadeSlide .5s ease both; animation-delay: .1s; }
        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .form-eyebrow {
            display: inline-flex; align-items: center; gap: 7px;
            background: rgba(79,70,229,.07);
            border: 1px solid rgba(79,70,229,.15);
            border-radius: 20px;
            padding: 5px 14px;
            font-size: 11px; font-weight: 700; color: var(--accent);
            letter-spacing: 1px; text-transform: uppercase;
            margin-bottom: 18px;
        }
        .form-title {
            font-size: 28px;
            font-weight: 800;
            color: var(--text);
            line-height: 1.2;
            margin-bottom: 6px;
        }
        .form-subtitle {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 32px;
            line-height: 1.6;
        }

        /* Error banner */
        .error-banner {
            display: flex; align-items: flex-start; gap: 10px;
            background: #fff1f2;
            border: 1px solid #fecdd3;
            border-radius: 12px;
            padding: 13px 16px;
            margin-bottom: 22px;
            font-size: 13px; color: #be123c;
            animation: wobble .4s ease;
        }
        @keyframes wobble {
            0%,100%{ transform:translateX(0) }
            20%    { transform:translateX(-5px) }
            40%    { transform:translateX(5px) }
            60%    { transform:translateX(-3px) }
            80%    { transform:translateX(3px) }
        }
        .error-banner i { margin-top: 1px; flex-shrink: 0; }

        /* Form group */
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            font-size: 13px; font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
        }
        .input-wrap { position: relative; display: flex; align-items: center; }
        .input-icon {
            position: absolute; left: 14px;
            color: #9ca3af; font-size: 15px;
            pointer-events: none; z-index: 1;
            transition: color .2s;
        }
        html[dir="rtl"] .input-icon { left: auto; right: 14px; }

        .form-input {
            width: 100%;
            background: var(--surface2);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 13px 46px;
            font-size: 14px;
            color: var(--text);
            font-family: inherit;
            outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }
        .form-input::placeholder { color: #9ca3af; }
        .form-input:focus {
            border-color: var(--accent);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(79,70,229,.1);
        }
        .form-input.is-invalid { border-color: var(--accent2); }
        .form-input.is-invalid:focus { box-shadow: 0 0 0 4px rgba(225,29,72,.1); }
        .input-wrap:focus-within .input-icon { color: var(--accent); }

        .pw-toggle {
            position: absolute; right: 13px;
            background: none; border: none;
            color: #9ca3af; font-size: 15px;
            cursor: pointer; transition: color .2s; padding: 4px; z-index: 1;
        }
        html[dir="rtl"] .pw-toggle { right: auto; left: 13px; }
        .pw-toggle:hover { color: var(--accent); }

        .field-error {
            display: flex; align-items: center; gap: 5px;
            font-size: 12px; color: var(--accent2);
            margin-top: 6px;
        }

        /* Bottom row */
        .form-row {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 26px;
        }

        /* Custom checkbox */
        .checkbox-label {
            display: flex; align-items: center; gap: 9px;
            cursor: pointer; font-size: 13px; color: var(--muted);
            user-select: none;
        }
        .checkbox-label input { display: none; }
        .custom-cb {
            width: 18px; height: 18px;
            border: 1.5px solid var(--border);
            border-radius: 5px;
            background: var(--surface);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; transition: all .2s;
        }
        .checkbox-label input:checked ~ .custom-cb {
            background: var(--accent);
            border-color: var(--accent);
        }
        .checkbox-label input:checked ~ .custom-cb::after {
            content: '✓'; color: #fff; font-size: 11px; font-weight: 700;
        }

        /* Submit button */
        .btn-submit {
            width: 100%;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 13px;
            padding: 15px 24px;
            font-size: 15px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            transition: background .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 4px 18px rgba(79,70,229,.35);
            position: relative; overflow: hidden;
            letter-spacing: .2px;
        }
        .btn-submit::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,.12) 0%, transparent 60%);
        }
        .btn-submit:hover {
            background: var(--accent-h);
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(79,70,229,.45);
        }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit.loading .btn-txt { opacity: 0; }
        .btn-submit.loading .btn-spin { display: block; }
        .btn-spin {
            display: none; position: absolute;
            width: 20px; height: 20px;
            border: 2.5px solid rgba(255,255,255,.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Bottom info */
        .form-bottom {
            margin-top: 26px;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            font-size: 12px; color: var(--muted);
        }
        .form-bottom i { color: var(--accent3); }

        .contact-admin {
            margin-top: 16px; text-align: center;
            font-size: 12px; color: var(--muted);
        }
        .contact-admin a { color: var(--accent); font-weight: 600; text-decoration: none; }
        .contact-admin a:hover { text-decoration: underline; }

        /* ── Responsive ── */
        @media (max-width: 800px) {
            .login-wrap { grid-template-columns: 1fr; max-width: 480px; }
            .info-panel { padding: 36px 32px; }
            .info-cards { display: none; }
            .form-panel { padding: 40px 32px; }
        }
        @media (max-width: 480px) {
            body { padding: 16px; }
            .form-panel { padding: 32px 24px; }
            .info-panel { padding: 28px 24px; }
            .form-title { font-size: 22px; }
        }
    </style>
</head>
<body>

    <!-- Floating dots -->
    <div class="dot dot-1"></div>
    <div class="dot dot-2"></div>
    <div class="dot dot-3"></div>
    <div class="dot dot-4"></div>
    <div class="dot dot-5"></div>

    <div class="login-wrap">

        <!-- ═══ LEFT: Info Panel ═══ -->
        <div class="info-panel">
            <div class="ring ring-1"></div>
            <div class="ring ring-2"></div>

            <!-- Logo -->
            <div class="info-logo">
                <div class="info-logo-icon"><i class="fas fa-bolt"></i></div>
                <div>
                    <div class="info-logo-name">NovaCRM</div>
                    <div class="info-logo-sub">{{ app()->getLocale() === 'ar' ? 'بوابة الموظفين' : 'Employee Portal' }}</div>
                </div>
            </div>

            <!-- Content -->
            <div class="info-content">
                <div class="info-badge">
                    <i class="fas fa-users"></i>
                    {{ app()->getLocale() === 'ar' ? 'مساحتك الشخصية' : 'Your Workspace' }}
                </div>

                @if(app()->getLocale() === 'ar')
                    <div class="info-heading">مرحباً بك في<br><em>بوابة الموظفين</em></div>
                    <p class="info-desc">تابع مهامك، رواتبك، إجازاتك، وجدولك اليومي — كل شيء بمكان واحد.</p>
                @else
                    <div class="info-heading">Your work,<br><em>all in one place.</em></div>
                    <p class="info-desc">Track your tasks, salary, leave, and daily schedule — everything you need, right here.</p>
                @endif

                <!-- Profile stack -->
                <div class="profile-stack">
                    <div class="profile-avatar">A</div>
                    <div class="profile-avatar">S</div>
                    <div class="profile-avatar">O</div>
                    <div class="profile-avatar">L</div>
                    <span class="profile-count">+20 {{ app()->getLocale() === 'ar' ? 'موظف' : 'employees' }}</span>
                </div>

                <!-- Feature cards -->
                <div class="info-cards">
                    <div class="info-card">
                        <div class="info-card-icon"><i class="fas fa-tasks"></i></div>
                        <div>
                            <div class="info-card-text">{{ app()->getLocale() === 'ar' ? 'متابعة المهام' : 'Task Tracking' }}</div>
                            <div class="info-card-sub">{{ app()->getLocale() === 'ar' ? 'عرض وتحديث مهامك' : 'View & update your assignments' }}</div>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-icon"><i class="fas fa-wallet"></i></div>
                        <div>
                            <div class="info-card-text">{{ app()->getLocale() === 'ar' ? 'الراتب والعمولة' : 'Salary & Commission' }}</div>
                            <div class="info-card-sub">{{ app()->getLocale() === 'ar' ? 'تفاصيل راتبك الشهري' : 'Monthly payslip details' }}</div>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-icon"><i class="fas fa-plane-departure"></i></div>
                        <div>
                            <div class="info-card-text">{{ app()->getLocale() === 'ar' ? 'طلبات الإجازة' : 'Leave Requests' }}</div>
                            <div class="info-card-sub">{{ app()->getLocale() === 'ar' ? 'تقدم بطلب إجازة بسهولة' : 'Submit & track leave requests' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Departments -->
            <div class="info-bottom">
                <div class="info-dept-row">
                    @foreach(['design','development','sales','video','marketing','social_media'] as $d)
                        <span class="dept-tag">{{ __('admin.' . $d) }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- ═══ RIGHT: Form Panel ═══ -->
        <div class="form-panel">

            <!-- Language switcher -->
            <div class="lang-row">
                <a href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}"
                   class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">🇬🇧 EN</a>
                <a href="{{ LaravelLocalization::getLocalizedURL('ar', null, [], true) }}"
                   class="lang-btn {{ app()->getLocale() === 'ar' ? 'active' : '' }}">🇸🇦 AR</a>
            </div>

            <div class="form-content">

                <div class="form-eyebrow">
                    <i class="fas fa-id-badge"></i>
                    {{ app()->getLocale() === 'ar' ? 'بوابة الموظف' : 'Employee Portal' }}
                </div>

                <h1 class="form-title">
                    {{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Sign in to your account' }}
                </h1>
                <p class="form-subtitle">
                    {{ app()->getLocale() === 'ar'
                        ? 'استخدم رقم هاتفك وكلمة المرور للدخول'
                        : 'Use your phone number and password to continue.' }}
                </p>

                {{-- Error banner --}}
                @if($errors->any() || session('error'))
                <div class="error-banner">
                    <i class="fas fa-circle-exclamation"></i>
                    <span>{{ $errors->first() ?? session('error') }}</span>
                </div>
                @endif

                <form action="{{ route('employee.login') }}" method="POST" id="empLoginForm" novalidate>
                    @csrf

                    {{-- Phone --}}
                    <div class="form-group">
                        <label class="form-label" for="phone">
                            {{ app()->getLocale() === 'ar' ? 'رقم الهاتف' : 'Phone Number' }}
                        </label>
                        <div class="input-wrap">
                            <i class="fas fa-phone input-icon"></i>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                class="form-input {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                value="{{ old('phone') }}"
                                placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل رقم هاتفك...' : 'Enter your phone number...' }}"
                                autocomplete="username"
                                autofocus
                                required
                                dir="ltr"
                            >
                        </div>
                        @error('phone')
                            <div class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
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
                            <button type="button" class="pw-toggle" onclick="togglePw()" id="pwToggle">
                                <i class="fas fa-eye" id="pwIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Remember me --}}
                    <div class="form-row">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <div class="custom-cb"></div>
                            {{ app()->getLocale() === 'ar' ? 'تذكرني' : 'Remember me' }}
                        </label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <span class="btn-spin"></span>
                        <span class="btn-txt" style="display:flex;align-items:center;gap:9px;">
                            <i class="fas fa-arrow-right-to-bracket"></i>
                            {{ app()->getLocale() === 'ar' ? 'دخول' : 'Sign In' }}
                        </span>
                    </button>
                </form>

                <div class="form-bottom">
                    <i class="fas fa-shield-halved"></i>
                    {{ app()->getLocale() === 'ar' ? 'اتصالك آمن ومشفر' : 'Your connection is secure and encrypted' }}
                </div>

                <div class="contact-admin">
                    {{ app()->getLocale() === 'ar' ? 'مشكلة في الدخول؟' : 'Having trouble?' }}
                    <a href="mailto:admin@novacrm.com">
                        {{ app()->getLocale() === 'ar' ? 'تواصل مع المدير' : 'Contact your admin' }}
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script>
        function togglePw() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('pwIcon');
            input.type  = input.type === 'password' ? 'text' : 'password';
            icon.className = input.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        }

        document.getElementById('empLoginForm').addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');
            btn.classList.add('loading');
            btn.disabled = true;
            setTimeout(() => { btn.classList.remove('loading'); btn.disabled = false; }, 5000);
        });

        // Custom checkbox support
        document.querySelectorAll('.checkbox-label').forEach(label => {
            label.addEventListener('click', function(e) {
                if (e.target.tagName !== 'INPUT') {
                    const cb = this.querySelector('input');
                    cb.checked = !cb.checked;
                    cb.dispatchEvent(new Event('change'));
                }
            });
        });
    </script>
</body>
</html>