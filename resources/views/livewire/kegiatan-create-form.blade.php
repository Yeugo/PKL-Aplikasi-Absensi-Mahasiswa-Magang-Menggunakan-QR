<div>
    <form wire:submit.prevent="saveKegiatan" method="post" novalidate enctype="multipart/form-data">
        @include('partials.alerts')

        <div class="mb-3">
            <x-form-label id="judul" label="Judul Kegiatan" />
            <x-form-input id="judul" name="judul" wire:model.defer="kegiatan.judul" />
            <x-form-error key="kegiatan.judul" />
        </div>

        <div class="mb-3">
            <x-form-label id="deskripsi" label="Deskripsi Kegiatan" />
            <x-form-input id="deskripsi" name="deskripsi" wire:model.defer="kegiatan.deskripsi" />
            <x-form-error key="kegiatan.deskripsi" />
        </div>

        <div class="mb-3">
            <x-form-label id="tgl_kegiatan" label="Tanggal Kegiatan" />
            <x-form-input id="tgl_kegiatan" name="tgl_kegiatan" type="date" wire:model.defer="kegiatan.tgl_kegiatan" />
            <x-form-error key="kegiatan.tgl_kegiatan" />
        </div>

        <div class="mb-3">
            <x-form-label id="waktu_mulai" label="Waktu Mulai" />
            <x-form-input id="waktu_mulai" name="waktu_mulai" type="time" wire:model.defer="kegiatan.waktu_mulai" />
            <x-form-error key="kegiatan.waktu_mulai" />
        </div>

        <div class="mb-3">
            <x-form-label id="waktu_selesai" label="Waktu selesai" />
            <x-form-input id="waktu_selesai" name="waktu_selesai" type="time" wire:model.defer="kegiatan.waktu_selesai" />
            <x-form-error key="kegiatan.waktu_selesai" />
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
