<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $settings['site_name'] ?? config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', $settings['meta_description'] ?? '')">
    <meta property="og:title"       content="@yield('title', $settings['site_name'] ?? config('app.name'))">
    <meta property="og:description" content="@yield('meta_description', $settings['meta_description'] ?? '')">
    <meta property="og:type"        content="website">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- CSS only di head --}}
    @vite(['resources/css/app.css'])

    @stack('head')
</head>
<body class="min-h-screen flex flex-col">

    @include('components.navbar')

    <main class="flex-1">
        @yield('content')
    </main>

    @include('components.footer')

    {{-- JS di bawah body agar semua function sudah terdefinisi --}}
    @vite(['resources/js/app.js'])

    @stack('scripts')

</body>
</html>