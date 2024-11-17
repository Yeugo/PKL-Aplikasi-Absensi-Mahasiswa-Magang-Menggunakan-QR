<div>
    <form wire:submit.prevent="saveBidangs" method="post" novalidate>
        @include('partials.alerts')
        @foreach ($bidangs as $bidang)

        <div class="mb-3">
            <div class="w-100">
                <div class="mb-3">
                    <x-form-label id="name{{ $bidang['id'] }}"
                        label="Nama Bidang / Divisi {{ $loop->iteration }} (ID: {{ $bidang['id'] }})" />
                    <x-form-input id="name{{ $bidang['id'] }}" name="name{{ $bidang['id'] }}"
                        wire:model.defer="bidangs.{{ $loop->index }}.name" />
                    <x-form-error key="bidangs.{{ $loop->index }}.name" />
                </div>
                <div class="mb-3">
                    <x-form-label id="kepala_bidang{{ $bidang['id'] }}" label='Kepala Bidang {{ $loop->iteration }}' />
                    <x-form-input id="kepala_bidang{{ $bidang['id'] }}" name="kepala_bidang{{ $bidang['id'] }}"
                        wire:model.defer="bidangs.{{ $loop->index }}.kepala_bidang" />
                    <x-form-error key="bidangs.{{ $loop->index }}.kepala_bidang" />
                </div>
            </div>
        </div>
        <hr>
        @endforeach

        <div class="d-flex justify-content-between align-items-center mb-5">
            <button class="btn btn-primary">
                Simpan
            </button>
        </div>
    </form>
</div>