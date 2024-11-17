<div>
    <div class="btn-group">
        <button type="button" class="btn btn-primary dropdown-toggle mb" data-bs-toggle="dropdown" aria-expanded="false">
            Export
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" wire:click.prevent="exportToExcel">Export ke Excel</a></li>
            <li><a class="dropdown-item" wire:click.prevent="$emit('exportPDF')">Export ke PDF</a></li>
        </ul>
    </div>
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

        @foreach ($bidangs as $i => $bidang)
        <div class="mb-3 position-relative">
            <x-form-label id="name{{ $i }}" label='Nama Bidang {{ $i + 1 }}' />
            <div class="d-flex align-items-center">
                <x-form-input id="name{{ $i }}" name="name{{ $i }}" wire:model.defer="bidangs.{{ $i }}.name" />
                @if ($i > 0)
                <button class="btn btn-danger ms-2" wire:click="removePositionInput({{ $i }})"
                    wire:target="removePositionInput({{ $i }})" type="button"
                    wire:loading.attr="disabled">Hapus</button>
                @endif
            </div>
        </div>
        @endforeach

        <div class="d-flex justify-content-between align-items-center">
            <button class="btn btn-primary">
                Simpan
            </button>
            <button class="btn btn-light" type="button" wire:click="addBidangInput" wire:loading.attr="disabled">
                Tambah Input
            </button>
        </div>
    </form>
</div>