<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('partials.styles')
    @stack('style')
    @livewireStyles

    <title>{{ $title }} | Absensi App</title>
    
</head>

<body id="page-top">

    <x-toast-container />

    @yield('base')

    @include('partials.scripts')
    @stack('script')
    @livewireScripts
</body>

</html>