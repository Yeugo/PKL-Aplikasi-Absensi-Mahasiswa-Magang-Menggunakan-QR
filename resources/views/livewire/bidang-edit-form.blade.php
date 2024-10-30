<div>
    <form wire:submit.prevent="saveBidangs" method="post">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="m-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @foreach ($bidangs as $bidang)
        <div class="mb-3 bidang-relative">
            <x-form-label id="name{{ $bidang['id'] }}"
                label="Nama Bidang {{ $loop->iteration }} (ID: {{ $bidang['id'] }})" />
            <div class="d-flex align-items-center">
                <x-form-input id="name{{ $bidang['id'] }}" name="name{{ $bidang['id'] }}"
                    wire:model.defer="bidangs.{{ $loop->index }}.name" value="{{ $bidang['name'] }}" />
            </div>
        </div>
        @endforeach

        <div class="d-flex justify-content-between align-items-center">
            <button class="btn btn-primary">
                Simpan
                <div wire:loading>
                    ...
                </div>
            </button>
        </div>
    </form>
</div>