@extends('layouts.base')

@push('style')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endpush

@section('base')



<div id="wrapper">
    @include('partials.sidebar-pembimbing')
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
@endsection