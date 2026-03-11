<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('emp.dashboard')) — NovaCRM</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(session('success')) <meta name="flash-success" content="{{ session('success') }}"> @endif
    @if(session('error'))   <meta name="flash-error"   content="{{ session('error') }}">   @endif
    @if(session('warning')) <meta name="flash-warning" content="{{ session('warning') }}"> @endif
    @if(session('info'))    <meta name="flash-info"    content="{{ session('info') }}">    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    @if(app()->getLocale() === 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets_front/css/styles.css') }}">

    @stack('styles')
</head>
<body class="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

    @include('employee.includes.sidebar')

    <div class="main">
        @include('employee.includes.navbar')

        <main class="content">
            @yield('content')
        </main>

        @include('employee.includes.footer')
    </div>

    <div id="toast-container"></div>

    <script src="{{ asset('assets/employee/js/app.js') }}"></script>
    @stack('scripts')

    <script>
    (function () {
        const types = ['success','error','warning','info'];
        const icons = { success:'fa-check-circle', error:'fa-times-circle', warning:'fa-exclamation-triangle', info:'fa-info-circle' };
        types.forEach(t => {
            const el = document.querySelector(`meta[name="flash-${t}"]`);
            if (el) showToast(t, el.getAttribute('content'));
        });
        function showToast(type, msg) {
            const c = document.getElementById('toast-container');
            const d = document.createElement('div');
            d.className = `toast toast-${type}`;
            d.innerHTML = `<i class="fas ${icons[type]}"></i><span>${msg}</span><button onclick="this.parentElement.remove()">×</button>`;
            c.appendChild(d);
            setTimeout(() => d.classList.add('show'), 10);
            setTimeout(() => { d.classList.remove('show'); setTimeout(() => d.remove(), 400); }, 4500);
        }
        window.showToast = showToast;
    })();
    </script>
</body>
</html>