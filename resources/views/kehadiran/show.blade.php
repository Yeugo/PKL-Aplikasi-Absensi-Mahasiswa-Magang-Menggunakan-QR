@extends('layouts.app')

@push('style')
@powerGridStyles
@endpush

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3 mb-md-0">
                <h5 class="card-title">{{ $absensi->title }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">{{ $absensi->description }}</h6>
                <div class="d-flex align-items-center gap-2">
                    @include('partials.absensi-badges')
                    <a href="{{ route('kehadiran.izin', $absensi->id) }}" class="badge text-bg-info">Mahasiswa Izin</a>
                    <a href="{{ route('kehadiran.not-present', $absensi->id) }}" class="badge text-bg-danger">Belum
                        Absen</a>
                    @if ($absensi->code)
                    <a href="{{ route('kehadiran.qrcode', ['code' => $absensi->code]) }}"
                        class="badge text-bg-success">QRCode</a>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-2">
                            <small class="fw-bold text-muted d-block">Range Jam Masuk</small>
                            <span>{{ $absensi->start_time }} - {{ $absensi->batas_start_time }}</span>
                        </div>
                        <div class="mb-2">
                            <small class="fw-bold text-muted d-block">Range Jam Pulang</small>
                            <span>{{ $absensi->end_time }} - {{ $absensi->batas_end_time }}</span>
                        </div>
                    </div>
                    <div class="col-md-8">
                        {{-- <div class="mb-2">
                            <small class="fw-bold text-muted d-block">Jabatan / Posisi</small>
                            <div>
                                @foreach ($attendance->positions as $position)
                                <span class="badge text-bg-success d-inline-block me-1">{{ $position->name }}</span>
                                @endforeach
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    <livewire:kehadiran-table absensiId="{{ $absensi->id }}" />
</div>

@endsection

@push('script')
<script src="{{ asset('jquery/jquery-3.6.0.min.js') }}"></script>
@powerGridScripts
@endpush