@extends('layouts.base')

@push('style')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endpush

@section('base')



<div id="wrapper">
    {{-- @include('partials.sidebar-peserta') --}}
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="main-content" >
            {{-- @include('partials.home-navbar') --}}
            <div class="container-fluid bg-light-subtle">
                <div class="py-0">
                    @yield('content')   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection