@extends('layouts.home')

@section('content')

<div class="container py-5">
    <div class="row">
        <!-- Card Profil Peserta Magang -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-2">
                <div class="card-header">
                    Profil Peserta Magang
                </div>
                <div class="card-body">
                    <p class="card-text"><strong>Nama:</strong> {{ $peserta->name }}</p>
                    <p class="card-text"><strong>NPM:</strong> {{ $peserta->npm }}</p>
                    <p class="card-text"><strong>Telepon:</strong> {{ $peserta->phone }}</p>
                    <p class="card-text"><strong>Universitas:</strong> {{ $peserta->univ }}</p>
                    <p class="card-text"><strong>Alamat:</strong> {{ $peserta->alamat }}</p>
                    <p class="card-text"><strong>Bidang:</strong> {{ $peserta->bidang->name ?? 'N/A' }}</p>
                    <p class="card-text"><strong>Pembimbing:</strong> {{ $peserta->pembimbing->name ?? 'belum ada pembimbing' }}</p>
                    <p class="card-text"><strong>Kontak Pembimbing:</strong> 
                        @if($peserta->pembimbing)
                            <a href="https://wa.me/{{ $peserta->pembimbing->phone }}" target="_blank">{{ $peserta->pembimbing->phone }}</a>
                        @else
                            belum ada pembimbing
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm mb-2">
                <div class="card-header">
                    Daftar Absensi Hari Ini
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($absensis as $absensi)
                        <a href="{{ route('home.show', $absensi->id) }}"
                            class="list-group-item d-flex justify-content-between align-items-start py-3">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">{{ $absensi->title }}</div>
                                <p class="mb-0">{{ $absensi->description }}</p>
                            </div>
                            @include('partials.absensi-badges')
                        </a>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h6 class="fs-6 fw-light">Jumlah Absensi Bulan Ini</h6>
                    <h4 class="fw-bold">{{ $kehadiranCount }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
