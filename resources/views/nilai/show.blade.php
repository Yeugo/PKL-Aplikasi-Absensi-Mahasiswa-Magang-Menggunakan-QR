@extends('layouts.app')

@push('style')
    @powerGridStyles

@section('buttons')
    {{-- Anda bisa menambahkan tombol Tambah di sini jika ini adalah halaman utama list nilai --}}
@endsection

@section('content')
    @include('partials.alerts')

    <div class="container-fluid mt-2"> {{-- Gunakan container-fluid untuk lebar penuh atau container untuk lebar tetap --}}

        <div class="d-flex justify-content-between align-items-center mb-2">
            <h1 class="h2 text-dark">{{ $peserta->name }}</h1> {{-- Judul utama adalah nama peserta --}}
            <div class="d-flex gap-2"> {{-- Gunakan flexbox gap untuk jarak antar tombol --}}
                {{-- Tombol Aksi - Pindahkan ke sini untuk konsistensi --}}
                <a href="{{ route('nilai.index') }}" class="btn btn-secondary btn-sm px-4">
                    <i class="bi bi-arrow-left me-2"></i> Kembali
                </a>
                <a href="{{ route('nilai.exportPdf', $peserta->id) }}" class="btn btn-outline-primary btn-sm px-4" target="_blank">
                    <i class="bi bi-printer me-2"></i> Cetak PDF
                </a>
                @can('update', $nilai)
                    <a href="##" class="btn btn-warning btn-sm px-4">
                        <i class="bi bi-pencil-square me-2"></i> Edit
                    </a>
                @endcan
            </div>
        </div>

        <div class="row">
            <div class="col-lg-5 col-md-12 mb-4"> {{-- Kolom untuk Informasi Umum dan Catatan --}}
                <div class="card shadow-sm border-0 h-100"> {{-- Tambahkan shadow-sm dan border-0 --}}
                    <div class="card-header bg-primary text-white border-bottom-0 rounded-top"> {{-- Gunakan gradient --}}
                        <h5 class="mb-0 text-light">Informasi Peserta</h5> {{-- Gunakan h5 atau h6 untuk judul card --}}
                    </div>
                    <div class="card-body">
                        <div class="info-item mb-2">
                            <strong>Nama:</strong> {{ $peserta->name }}
                        </div>
                        <div class="info-item mb-2">
                            <strong>NIM:</strong> {{ $peserta->npm ?? '-' }}
                        </div>
                        <div class="info-item mb-2">
                            <strong>Universitas:</strong> {{ $peserta->univ ?? '-' }}
                        </div>
                        <div class="info-item mb-2">
                            <strong>Pembimbing:</strong> {{ $peserta->pembimbing->name }}
                        </div>
                        <div class="info-item mb-2">
                            <strong>Bidang:</strong> {{ $peserta->bidang->name ?? '-' }}
                        </div>
                        {{-- <div class="info-item mb-0"> --}}
                            {{-- <strong>Periode Magang:</strong> {{ $peserta->periode ?? '-' }} --}}
                        {{-- </div> --}}
                    </div>
                </div>
            </div>

            <div class="col-lg-7 col-md-12 mb-4"> {{-- Kolom untuk Penilaian --}}
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-success text-white border-bottom-0 rounded-top">
                        <h5 class="mb-0 text-light">Penilaian Kinerja</h5>
                    </div>
                    <div class="card-body p-0"> {{-- Hapus padding dari card-body agar tabel mengambil penuh --}}
                        <table class="table table-striped table-hover mb-0"> {{-- Gunakan table-striped dan table-hover --}}
                            <thead class="bg-light"> {{-- Gunakan bg-light untuk thead --}}
                                <tr>
                                    <th class="py-3 px-4">No</th>
                                    <th class="py-3 px-4">Kriteria</th>
                                    <th class="py-3 px-4 text-center">Nilai (1-100)</th>
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
                                    $jumlahKriteria = count($listNilai);
                                    $rata2 = ($jumlahKriteria > 0) ? $total / $jumlahKriteria : 0;

                                    // Menentukan kategori berdasarkan rata-rata
                                    if ($rata2 >= 85) {
                                        $kategori = 'Sangat Baik';
                                        $kategoriClass = 'text-success'; // Warna teks untuk kategori
                                    } elseif ($rata2 >= 70) {
                                        $kategori = 'Baik';
                                        $kategoriClass = 'text-info';
                                    } elseif ($rata2 >= 55) {
                                        $kategori = 'Cukup';
                                        $kategoriClass = 'text-warning';
                                    } else {
                                        $kategori = 'Kurang';
                                        $kategoriClass = 'text-danger';
                                    }
                                @endphp

                                @foreach ($listNilai as $kriteria => $nilaiItem)
                                    <tr>
                                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-2">{{ $kriteria }}</td>
                                        <td class="px-4 py-2 text-center">{{ $nilaiItem }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                        <div class="text-start">
                            <p class="mb-0"><strong>Total Nilai:</strong> {{ $total }}</p>
                        </div>
                        <div class="text-end">
                            <p class="mb-0"><strong>Rata-rata:</strong> <span class="fw-bold {{ $kategoriClass }}">{{ number_format($rata2, 2) }}</span> (<span class="fw-bold {{ $kategoriClass }}">{{ $kategori }}</span>)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div> {{-- End row --}}

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-warning text-white border-bottom-0 rounded-top">
                <h5 class="mb-0">Catatan Pembimbing</h5>
            </div>
            <div class="card-body">
                <p class="mb-0 text-muted">{{ $nilai->catatan ?? 'Tidak ada catatan.' }}</p>
            </div>
        </div>

    </div>
@endsection

@push('script')
<script src="{{ asset('jquery/jquery-3.6.0.min.js') }}"></script>
@powerGridScripts
@endpush