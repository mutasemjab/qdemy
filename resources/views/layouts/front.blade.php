<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>خليليو - حقيبتك التعليمية</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets_front/css/style.css') }}">
</head>
<body>
    <div class="animated-bg"></div>
    
    <div class="container">
        @include('includes.logo')
        
        @yield('content')
        
        @include('includes.logout-button')
    </div>

    @include('includes.scripts')
</body>
</html>