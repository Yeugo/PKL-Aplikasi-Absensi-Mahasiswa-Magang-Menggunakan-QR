@extends('layouts.home')

@section('content')
<div class="container py-5">
    <div class="row">
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
