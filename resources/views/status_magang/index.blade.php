@extends('layouts.app')

@push('style')
@powerGridStyles
@endpush

@section('buttons')

<div class="btn-toolbar d-flex justify-content-between mb-2 mb-md-2">
    {{-- <div class="ms-2">
        <a href="{{ route('peserta.create') }}" class="btn btn-sm btn-primary">
            <span data-feather="plus-circle" class="align-text-bottom me-1"></span>
            Tambah Data Peserta Magang
        </a>
    </div> --}}
</div>
@endsection

@section('content')
@include('partials.alerts')

<livewire:status-magang-table />
@endsection

@push('script')
<script src="{{ asset('jquery/jquery-3.6.0.min.js') }}"></script>
@powerGridScripts
@endpush