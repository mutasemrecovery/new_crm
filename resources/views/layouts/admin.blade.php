<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('admin.dashboard')) — NovaCRM</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Flash Messages for JS Toast --}}
    @if(session('success')) <meta name="flash-success" content="{{ session('success') }}"> @endif
    @if(session('error'))   <meta name="flash-error"   content="{{ session('error') }}">   @endif
    @if(session('warning')) <meta name="flash-warning" content="{{ session('warning') }}"> @endif
    @if(session('info'))    <meta name="flash-info"    content="{{ session('info') }}">    @endif

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    @if(app()->getLocale() === 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    @endif

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/styles.css') }}">

    @stack('styles')
</head>
<body class="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

    @include('admin.includes.sidebar')

    <div class="main">
        @include('admin.includes.navbar')

        <main class="content">
            @yield('content')
        </main>

        @include('admin.includes.footer')
    </div>

    {{-- Toast container --}}
    <div id="toast-container"></div>

    <script src="{{ asset('assets/admin/js/app.js') }}"></script>
    @stack('scripts')

    {{-- Toast JS --}}
    <script>
    (function () {
        const types = ['success', 'error', 'warning', 'info'];
        const icons = { success: 'fa-check-circle', error: 'fa-times-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };
        types.forEach(type => {
            const el = document.querySelector(`meta[name="flash-${type}"]`);
            if (el) showToast(type, el.getAttribute('content'));
        });

        function showToast(type, message) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `<i class="fas ${icons[type]}"></i><span>${message}</span><button onclick="this.parentElement.remove()">×</button>`;
            container.appendChild(toast);
            setTimeout(() => { toast.classList.add('show'); }, 10);
            setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 400); }, 4500);
        }
        window.showToast = showToast;
    })();
    </script>
</body>
</html>