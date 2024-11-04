@extends('layouts.app')

@push('style')
@powerGridStyles
@endpush

@section('buttons')
<div class="btn-toolbar mb-2 mb-md-0">
    <div>
        <a href="{{ route('interns.create') }}" class="btn btn-sm btn-primary">
            <span data-feather="plus-circle" class="align-text-bottom me-1"></span>
            Tambah Data Anak Magang
        </a>
    </div>
    {{-- <div>
        <a href="{{ route('employees.print') }}" class="btn btn-sm btn-danger">
            <span data-feather="plus-circle" class="align-text-bottom me-1"></span>
            Cetak Data
        </a>
    </div> --}}
</div>
@endsection

@section('content')
@include('partials.alerts')
<livewire:intern-table />
@endsection

@push('script')
<script src="{{ asset('jquery/jquery-3.6.0.min.js') }}"></script>
@powerGridScripts
@endpush