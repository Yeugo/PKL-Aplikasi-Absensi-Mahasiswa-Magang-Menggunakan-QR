@extends('layouts.auth')

@push('style')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endpush

@section('content')
<div class="form-container">
    <img src="{{ asset('storage/assets/logobjm.png') }}" alt="Logo" class="logo" style="width: 50px; height: auto; margin-right: -10px">
    <h1>Selamat Datang di Aplikasi Absensi</h1>
    <p class="mb-2">Silahkan masuk untuk melanjutkan absensi</p>

    <form method="POST" action="{{ route('auth.login') }}" id="login-form">
        @csrf
        <div class="form-floating">
            <input type="email" class="form-control" id="floatingInputEmail" name="email"
                placeholder="name@example.com" required>
            <label for="floatingInputEmail">Email</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="floatingPassword" name="password"
                placeholder="Password" required>
            <label for="floatingPassword">Kata Sandi</label>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="flexCheckRemember">
            <label class="form-check-label" for="flexCheckRemember">
                Ingatkan Saya di Perangkat ini
            </label>
        </div>

        <div class="mt-3 text-center">
            <p style="font-size: 1rem; font-style: initial">Menerima Mahasiswa Magang <a href="{{ route('pendaftaran.create') }}">Klik Disini</a></p>
        </div>

        <button class="w-100 btn btn-primary" type="submit" id="login-form-button">Masuk</button>
        <p class="mt-5 mb-3 text-muted">&copy; DKP3 KOTA BANJARMASIN 2024</p>
    </form>

    
</div>

@endsection

@push('script')
<script type="module" src="{{ asset('js/auth/login.js') }}"></script>
@endpush
