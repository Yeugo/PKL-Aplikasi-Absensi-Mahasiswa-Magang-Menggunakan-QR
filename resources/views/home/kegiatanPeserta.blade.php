@extends('layouts.home')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12"> <!-- Menjadikan kolom ini 12 untuk menggunakan seluruh lebar -->
            <div class="card shadow-sm mb-2">
                <div class="card-header">
                    Daftar Kegiatan Peserta Magang
                </div>
                <div class="card-body">
                    <!-- Pesan Sukses -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Tombol untuk menambah kegiatan -->
                    <div class="mb-3">
                        <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahKegiatanModal">
                            <i class="bi bi-plus-circle"></i> Tambah Kegiatan
                        </button>
                    </div>

                    <!-- Daftar Kegiatan -->
                    <table class="table table-striped table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kegiatan</th>
                                <th>Deskripsi</th>
                                <th>Tanggal Kegiatan</th>
                                <th>Waktu Mulai</th>
                                <th>Waktu Selesai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kegiatans as $kegiatan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $kegiatan->judul }}</td>
                                    <td>{{ $kegiatan->deskripsi }}</td>
                                    <td>{{ $kegiatan->tgl_kegiatan }}</td>
                                    <td>{{ $kegiatan->waktu_mulai }}</td>
                                    <td>{{ $kegiatan->waktu_selesai }}</td>
                                    <td>
                                        <a href="#" class="btn btn-warning btn-sm">Edit</a>

                                        <form action="#" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Kegiatan -->
<div class="modal fade" id="tambahKegiatanModal" tabindex="-1" aria-labelledby="tambahKegiatanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahKegiatanModalLabel">Tambah Kegiatan Peserta Magang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulir Tambah Kegiatan -->
                <form action="#" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul Kegiatan</label>
                        <input type="text" class="form-control" id="judul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi Kegiatan</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_kegiatan" class="form-label">Tanggal Kegiatan</label>
                        <input type="date" class="form-control" id="tanggal_kegiatan" name="tanggal_kegiatan" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah Kegiatan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
