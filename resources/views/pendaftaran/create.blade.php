@extends('layouts.guest')

@section('buttons')
<div class="btn-toolbar mb-2 mb-md-0">
    <div>
        {{-- <a href="{{ route('peserta.index') }}" class="btn btn-sm btn-light"> --}}
            <span data-feather="arrow-left-circle" class="align-text-bottom"></span>
            Kembali
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-7">
        <livewire:pendaftaran-create-form />
    </div>
</div>
@endsection