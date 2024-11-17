<div>
    <form wire:submit.prevent="saveBidangs" method="post" novalidate>
        @include('partials.alerts')
        @foreach ($bidangs as $i => $bidang)
        <div class="mb-3">
            <div class="w-100">
                <div class="mb-3">
                    <x-form-label id="name{{ $i }}" label='Nama Bidang {{ $i + 1 }}' />
                    <x-form-input id="name{{ $i }}" name="name{{ $i }}" wire:model.defer="bidangs.{{ $i }}.name" />
                    <x-form-error key="bidangs.{{ $i }}.name" />
                </div>
                <div class="mb-3">
                    <x-form-label id="kepala_bidang{{ $i }}" label='Kepala Bidang / Divisi {{ $i + 1 }}' />
                    <x-form-input id="kepala_bidang{{ $i }}" name="kepala_bidang{{ $i }}" wire:model.defer="bidangs.{{ $i }}.kepala_bidang" />
                    <x-form-error key="bidangs.{{ $i }}.kepala_bidang" />
                </div>   
            </div>
            @if ($i > 0)
            <button class="btn btn-sm btn-danger mt-2" wire:click="removeBidangInput({{ $i }})"
                wire:target="removeBidangInput({{ $i }})" type="button" wire:loading.attr="disabled">Hapus</button>
            @endif
        </div>
        <hr>
        @endforeach

        <div class="d-flex justify-content-between align-items-center mb-5">
            <button class="btn btn-primary">
                Simpan
            </button>
            <button class="btn btn-light" type="button" wire:click="addBidangInput" wire:loading.attr="disabled">
                Tambah Input
            </button>
        </div>
    </form>
</div>