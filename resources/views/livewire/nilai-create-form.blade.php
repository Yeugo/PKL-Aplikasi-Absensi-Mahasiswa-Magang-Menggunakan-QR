<div>
    <form wire:submit.prevent="saveNilai" method="post" novalidate enctype="multipart/form-data">
        @include('partials.alerts')

        <div class="mb-3">
            <x-form-label id="peserta_name" label="Nama Peserta" />
            <x-form-input id="peserta_name" name="peserta_name" wire:model.defer="peserta_name" readonly />
            <x-form-error key="peserta_name" />
        </div>

        <div class="mb-3">
            <x-form-label id="pembimbing_name" label="Nama Pembimbing" />
            <x-form-input id="pembimbing_name" name="pembimbing_name" wire:model.defer="pembimbing_name" readonly />
            <x-form-error key="pembimbing_name" />
        </div>

        <div class="mb-3">
            <x-form-label id="sikap" label="Sikap" />
            <x-form-input id="sikap" name="sikap" wire:model.defer="nilai.sikap" />
            <small class="form-text text-muted">*1-100</small>
            <x-form-error key="nilai.sikap" />
        </div>

        <div class="mb-3">
            <x-form-label id="kedisiplinan" label="Kedisiplinan" />
            <x-form-input id="kedisiplinan" name="kedisiplinan" type="kedisiplinan" wire:model.defer="nilai.kedisiplinan" />
            <small class="form-text text-muted">*1-100</small>
            <x-form-error key="nilai.kedisiplinan" />
        </div>

        <div class="mb-3">
            <x-form-label id="kesungguhan" label="Kesungguhan" />
            <x-form-input id="kesungguhan" name="kesungguhan" wire:model.defer="nilai.kesungguhan"/>
            <small class="form-text text-muted">*1-100</small>
            <x-form-error key="nilai.kesungguhan" />
        </div>

        <div class="mb-3">
            <x-form-label id="mandiri" label="Mandiri" />
            <x-form-input id="mandiri" name="mandiri" wire:model.defer="nilai.mandiri"/>
            <small class="form-text text-muted">*1-100</small>
            <x-form-error key="nilai.mandiri" />
        </div>

        <div class="mb-3">
            <x-form-label id="kerjasama" label="Kerjasama" />
            <x-form-input id="kerjasama" name="kerjasama" wire:model.defer="nilai.kerjasama"/>
            <small class="form-text text-muted">*1-100</small>
            <x-form-error key="nilai.kerjasama" />
        </div>

        <div class="mb-3">
            <x-form-label id="teliti" label="Ketelitian" />
            <x-form-input id="teliti" name="teliti" wire:model.defer="nilai.teliti"/>
            <small class="form-text text-muted">*1-100</small>
            <x-form-error key="nilai.teliti" />
        </div>

        <div class="mb-3">
            <x-form-label id="pendapat" label="Kemampuan Mengemukakan Pendapatat" />
            <x-form-input id="pendapat" name="pendapat" wire:model.defer="nilai.pendapat"/>
            <small class="form-text text-muted">*1-100</small>
            <x-form-error key="nilai.pendapat" />
        </div>

        <div class="mb-3">
            <x-form-label id="hal_baru" label="Kemampuan Menguasai Hal Baru" />
            <x-form-input id="hal_baru" name="hal_baru" wire:model.defer="nilai.hal_baru"/>
            <small class="form-text text-muted">*1-100</small>
            <x-form-error key="nilai.hal_baru" />
        </div>

        <div class="mb-3">
            <x-form-label id="inisiatif" label="Inisiatif" />
            <x-form-input id="inisiatif" name="inisiatif" wire:model.defer="nilai.inisiatif"/>
            <small class="form-text text-muted">*1-100</small>
            <x-form-error key="nilai.inisiatif" />
        </div>

        <div class="mb-3">
            <x-form-label id="kepuasan" label="Kepuasan Pembimbing" />
            <x-form-input id="kepuasan" name="kepuasan" wire:model.defer="nilai.kepuasan"/>
            <small class="form-text text-muted">*1-100</small>
            <x-form-error key="nilai.kepuasan" />
        </div>

        <div class="mb-3">
            <x-form-label id="catatan" label="Catatan Pembimbing" />
            <x-form-input type="text" id="catatan" name="catatan" wire:model.defer="nilai.catatan" />
            <small class="form-text text-muted">*Tidak wajib diisi</small>
            <x-form-error key="nilai.catatan" />
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
