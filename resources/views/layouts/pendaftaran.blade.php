{{-- @push('style') --}}
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
{{-- @endpush --}}

{{-- @section('base') --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('partials.styles')
    @stack('style')
    {{-- @livewireStyles --}}

    <title>{{ $title }} | Absensi App</title>
    
</head>

<body id="page-top">

    <x-toast-container />

    @yield('base')

    @include('partials.scripts')
    @stack('script')
    {{-- @livewireScripts --}}
</body>

</html>



<div id="wrapper">
    {{-- @include('partials.sidebar') --}}
    @if (auth()->user()->isAdmin())
    @include('partials.sidebar')
    @elseif (auth()->user()->isPembimbing())
        @include('partials.sidebar-pembimbing')
    @else
        @include('partials.sidebar-peserta')
    @endif
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="main-content" >
            @include('partials.navbar')
            <div class="container-fluid bg-light-subtle">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">{{ $title }}</h1>
                    @yield('buttons')
                </div>
                <div class="py-4">
                    @yield('content')   
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @endsection --}}