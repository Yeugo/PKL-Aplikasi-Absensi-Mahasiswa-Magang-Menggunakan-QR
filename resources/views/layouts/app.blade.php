{{-- @extends('layouts.base')

@push('style')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endpush

@section('base')

@include('partials.navbar')

<div class="container-fluid"> 
    <div class="row">
        @include('partials.sidebar')

        <main class="main-content col-md-9 ms-sm-auto col-lg-10 px-md-4 ml">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">{{ $title }}</h1>
                
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                        <span data-feather="calendar" class="align-text-bottom"></span>
                        This week
                    </button>
                </div>
                @yield('buttons')
            </div>

            <div class="py-4">
                @yield('content')
            </div>
        </main>
    </div>
</div>
@endsection --}}

@extends('layouts.base')

@push('style')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endpush

@section('base')



<div id="wrapper">
    {{-- @include('partials.sidebar') --}}
    @if (auth()->user()->isAdmin())
    @include('partials.sidebar')
    @elseif (auth()->user()->isPembimbing())
        @include('partials.sidebar-pembimbing')
    @else
        @include('partials.sidebar-default')
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
@endsection






            