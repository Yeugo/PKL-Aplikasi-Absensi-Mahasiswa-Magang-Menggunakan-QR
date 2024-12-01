@extends('layouts.app')

@section('content')
@include('partials.alerts')

<div class="row">
    <div class="col-md-7">
        <ul class="list-group">
            @foreach ($absensis as $absensi)
            <a href="{{ route('kehadiran.show', $absensi->id) }}"
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
    
@endsection