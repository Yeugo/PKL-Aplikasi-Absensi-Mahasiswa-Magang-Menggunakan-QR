{{-- filepath: c:\laragon\www\absensi-mhs-magang\resources\views\nilai\show.blade.php --}}
@extends('layouts.app')

@push('style')
@powerGridStyles
@endpush

@section('buttons')
{{-- <div class="btn-toolbar d-flex justify-content-between mb-2 mb-md-2">
    <div class="ms-2">
        <a href="{{ route('peserta.index') }}" class="btn btn-sm btn-secondary">
            <span data-feather="arrow-left" class="align-text-bottom me-1"></span>
            Kembali ke Daftar Peserta
        </a>
        <a href="{{ route('nilai.create') }}" class="btn btn-sm btn-primary ms-2">
            <span data-feather="plus-circle" class="align-text-bottom me-1"></span>
            Tambah Nilai Peserta Magang
        </a>
    </div>
</div> --}}
@endsection

@section('content')
@include('partials.alerts')

<div class="container">
    {{-- <h2 class="mb-4">{{ $title }}</h2> --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <strong>Data Peserta</strong>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Nama:</strong> {{ $peserta->name ?? '-' }}</li>
                        <li class="list-group-item"><strong>NPM / NIM:</strong> {{ $peserta->npm ?? '-' }}</li>
                        <li class="list-group-item"><strong>Pembimbing:</strong> {{ $pembimbing->name ?? '-' }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <strong>Nilai Peserta</strong>
                </div>
                <div class="card-body">
                    @if($nilai)
                        <p>
                            <strong>Nilai Akhir:</strong>
                            <span class="badge bg-info fs-5">{{ $nilai->kepuasan ?? '-' }}</span>
                        </p>
                        <p>
                            <strong>Keterangan:</strong><br>
                            <span class="text-muted">{{ $nilai->catatan ?? '-' }}</span>
                        </p>
                        {{-- Tambahkan field lain sesuai kebutuhan --}}
                    @else
                        <div class="alert alert-warning mb-0">
                            Belum ada data nilai untuk peserta ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('jquery/jquery-3.6.0.min.js') }}"></script>
@powerGridScripts
@endpush