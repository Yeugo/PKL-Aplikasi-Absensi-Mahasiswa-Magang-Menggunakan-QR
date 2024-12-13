@extends('layouts.home')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="mb-2">
                @include('partials.absensi-badges')
            </div>
            @include('partials.alerts')

            <h1 class="fs-2">{{ $absensi->title }}</h1>
            <p class="text-muted">{{ $absensi->description }}</p>

            <div class="mb-4">
                <span class="badge text-bg-light border shadow-sm">Masuk : {{
                    substr($absensi->data->start_time, 0 , -3) }} - {{
                    substr($absensi->data->batas_start_time,0,-3 )}}</span>
                <span class="badge text-bg-light border shadow-sm">Pulang : {{
                    substr($absensi->data->end_time, 0 , -3) }} - {{
                    substr($absensi->data->batas_end_time,0,-3 )}}</span>
            </div>

            @if (!$absensi->data->is_using_qrcode)
            <livewire:kehadiran-form :absensi="$absensi" :data="$data" :holiday="$holiday">
                @else
                @include('home.partials.qrcode-presence')
                @endif
        </div>
        <div class="col-md-6">
            <h5 class="mb-3">Histori 30 Hari Terakhir</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Jam Masuk</th>
                            <th scope="col">Jam Pulang</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($priodDate as $date)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            {{-- not presence / tidak hadir --}}
                            @php
                            $histo = $history->where('tgl_hadir', $date)->first();
                            @endphp
                            @if (!$histo)
                            <td>{{ $date }}</td>
                            <td colspan="3">
                                @if($date == now()->toDateString())
                                <div class="badge text-bg-info">Belum Hadir</div>
                                @else
                                <div class="badge text-bg-danger">Tidak Hadir</div>
                                @endif
                            </td>
                            @else
                            <td>{{ $histo->tgl_hadir }}</td>
                            <td>{{ $histo->absen_masuk }}</td>
                            <td>@if($histo->absen_keluar)
                                {{ $histo->absen_keluar }}
                                @else
                                <span class="badge text-bg-danger">Belum Absensi Pulang</span>
                                @endif
                            </td>
                            <td>
                                @if ($histo->izin)
                                <div class="badge text-bg-warning">Izin</div>
                                @else
                                <div class="badge text-bg-success">Hadir</div>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection