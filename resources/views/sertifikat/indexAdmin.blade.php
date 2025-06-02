@extends('layouts.app')

@push('style')
@powerGridStyles
@endpush

@section('buttons')

@endsection

@section('content')
@include('partials.alerts')

@livewire('sertifikat-admin')

@endsection

@push('script')
<script src="{{ asset('jquery/jquery-3.6.0.min.js') }}"></script>
@powerGridScripts
@endpush