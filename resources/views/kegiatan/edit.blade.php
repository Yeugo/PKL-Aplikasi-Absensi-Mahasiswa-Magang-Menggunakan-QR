@extends('layouts.app')

@section('buttons')
<div class="btn-toolbar mb-2 mb-md-0">
    <div>
        <a href="{{ route('kegiatan.index') }}" class="btn btn-sm btn-light">
            <span data-feather="arrow-left-circle" class="align-text-bottom"></span>
            Kembali
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-7">
        <livewire:kegiatan-edit-form :kegiatan="$kegiatan" />
    </div>
</div>
@endsection