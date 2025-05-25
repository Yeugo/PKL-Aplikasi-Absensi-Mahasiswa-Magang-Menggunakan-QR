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

{{-- <div class="container">
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
                    @else
                        <div class="alert alert-warning mb-0">
                            Belum ada data nilai untuk peserta ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div> --}}

<div class="container mt-4">
    {{-- <h2 class="mb-4">Detail Penilaian Kinerja Peserta Magang</h2> --}}

    <!-- Informasi Umum -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Informasi Peserta</div>
        <div class="card-body">
            <p><strong>Nama:</strong> {{ $peserta->name }}</p>
            <p><strong>NIM:</strong> {{ $peserta->npm ?? '-' }}</p>
            <p><strong>Universitas:</strong> {{ $peserta->univ ?? '-' }}</p>
            <p><strong>Pembimbing:</strong> {{ $peserta->pembimbing->name }}</p>
            <p><strong>Bidang:</strong> {{ $peserta->bidang->name ?? '-' }}</p>
            {{-- <p><strong>Periode Magang:</strong> {{ $peserta->periode ?? '-' }}</p> --}}
        </div>
    </div>

    <!-- Tabel Nilai -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">Penilaian</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kriteria</th>
                        <th>Nilai (1-100)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $listNilai = [
                            'Sikap' => $nilai->sikap,
                            'Kedisiplinan' => $nilai->kedisiplinan,
                            'Kesungguhan' => $nilai->kesungguhan,
                            'Mandiri' => $nilai->mandiri,
                            'Kerjasama' => $nilai->kerjasama,
                            'Teliti' => $nilai->teliti,
                            'Pendapat' => $nilai->pendapat,
                            'Hal Baru' => $nilai->hal_baru,
                            'Inisiatif' => $nilai->inisiatif,
                            'Kepuasan' => $nilai->kepuasan,
                        ];
                        $total = array_sum($listNilai);
                        $rata2 = $total / count($listNilai);

                        // Menentukan kategori berdasarkan rata-rata
                        if ($rata2 >= 85) {
                            $kategori = 'Sangat Baik';
                        } elseif ($rata2 >= 70) {
                            $kategori = 'Baik';
                        } elseif ($rata2 >= 55) {
                            $kategori = 'Cukup';
                        } else {
                            $kategori = 'Kurang';
                        }
                    @endphp

                    @foreach ($listNilai as $kriteria => $nilaiItem)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $kriteria }}</td>
                        <td>{{ $nilaiItem }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                <p><strong>Total Nilai:</strong> {{ $total }}</p>
                <p><strong>Rata-rata:</strong> {{ number_format($rata2, 2) }} ({{ $kategori }})</p>
            </div>
        </div>
    </div>

    <!-- Catatan -->
    <div class="card mb-4">
        <div class="card-header bg-warning">Catatan</div>
        <div class="card-body">
            <p>{{ $nilai->catatan ?? 'Tidak ada catatan.' }}</p>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <a href="{{ route('nilai.index') }}" class="btn btn-secondary">⬅ Kembali</a>
    <a href="##" class="btn btn-outline-primary" target="_blank">🖨 Cetak PDF</a>
    @can('update', $nilai)
    <a href="##" class="btn btn-warning">✏ Edit</a>
    @endcan
</div>
@endsection

@push('script')
<script src="{{ asset('jquery/jquery-3.6.0.min.js') }}"></script>
@powerGridScripts
@endpush