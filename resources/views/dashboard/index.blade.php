@extends('layouts.app')

@section('content')
<div>
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h6 class="fs-6 fw-light">Jumlah User</h6>
                    <h4 class="fw-bold">{{ $userCount }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h6 class="fs-6 fw-light">Jumlah Peserta Magang</h6>
                    <h4 class="fw-bold">{{ $pesertaCount }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h6 class="fs-6 fw-light">Jumlah Pembimbing</h6>
                    <h4 class="fw-bold">{{ $pembimbingCount }}</h4>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h6 class="fs-6 fw-light">Jumlah Kehadiran Minggu Ini</h6>
                    <h4 class="fw-bold">{{ $kehadiranPerminggu }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection