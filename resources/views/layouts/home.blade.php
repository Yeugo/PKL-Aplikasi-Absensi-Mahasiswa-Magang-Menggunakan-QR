{{-- @extends('layouts.base')

@section('base')

@include('partials.home-navbar')

@yield('content')

@endsection --}}
@extends('layouts.base')

@push('style')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endpush

@section('base')

@include('partials.navbar')

<div class="container-fluid">
    <div class="row">
        {{-- @include('partials.sidebar') --}}
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <!-- Profil User -->
                <div class="d-flex flex-column align-items-center pb-3 border-bottom profile-bg-dark">
                    @php
                        // Mengambil relasi peserta berdasarkan user yang login
                        $peserta = auth()->user()->peserta;
                    @endphp
        
                    <!-- Menampilkan Foto Profil -->
                    <img 
                        src="{{ $peserta && $peserta->foto ? asset('storage/' . $peserta->foto) : asset('storage/default-profile.png') }}" 
                        alt="Profile Photo" 
                        class="rounded-circle mb-2" 
                        style="width: 80px; height: 80px; object-fit: cover;">
                    
                    <!-- Menampilkan Nama Pengguna -->
                    <h6 class="text-center fw-bold">
                        {{ $peserta ? $peserta->name : 'Nama Tidak Tersedia' }}
                    </h6>
                    <span class="text-muted">
                        {{ auth()->user()->email }}
                    </span>
                </div>
                <ul class="nav flex-column">
                    @if (auth()->user()->isUser())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home.index') ? 'active' : '' }}" aria-current="page"
                            href="{{ route('home.index') }}">
                            <span data-feather="clipboard" class="align-text-bottom"></span>
                            Absensi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home.kegiatanPeserta') ? 'active' : '' }}"
                            href="{{ route('home.kegiatanPeserta') }}">
                            <span data-feather="file-text" class="align-text-bottom"></span>
                            Catatan Kegiatan
                        </a>
                    </li>
                </li>
                    @endif
                </ul>
        
                <form action="{{ route('auth.logout') }}" method="post"
                    onsubmit="return confirm('Apakah anda yakin ingin keluar?')">
                    @method('DELETE')
                    @csrf
                    <button class="w-full mt-4 d-block bg-transparent border-0 fw-bold text-danger px-3">Keluar</button>
                </form>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">{{ $title }}</h1>
                {{-- <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                        <span data-feather="calendar" class="align-text-bottom"></span>
                        This week
                    </button>
                </div> --}}
                @yield('buttons')
            </div>

            <div class="py-4">
                @yield('content')
            </div>
        </main>
    </div>
</div>
@endsection