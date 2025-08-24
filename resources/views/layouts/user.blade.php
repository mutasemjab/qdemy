<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Battle</title>
    <link rel="stylesheet" href="{{ asset('assets_front/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets_front/css/login.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets_front/css/menu.css') }}" />
    @if (App::getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('assets_front/css/rtl.css') }}">
    @endif
    <script src="{{ asset('assets_front/js/main.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @yield('css')
    @yield('script')
</head>

<body class="{{ App::getLocale() == 'ar' ? 'rtl' : '' }}">

    <!-- Navbar -->
    @include('user.includes.navbar')

    @include('user.includes.content')

    <!-- Footer -->
    @include('user.includes.footer')

    @stack('scripts')
</body>

</html>
