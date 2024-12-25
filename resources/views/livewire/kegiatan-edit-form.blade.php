<div>
    <form wire:submit.prevent="saveKegiatan" method="post" novalidate>
        @include('partials.alerts')
        @foreach ($kegiatan as $kegiatan)

        <div class="mb-3">
            <div class="w-100">
                <div class="mb-3">
                    <x-form-label id="judul{{ $kegiatan['id'] }}"
                        label="Kegiatan {{ $loop->iteration }} (ID: {{ $kegiatan['id'] }})" />
                    <x-form-input id="judul{{ $kegiatan['id'] }}" name="judul{{ $kegiatan['id'] }}"
                        wire:model.defer="kegiatan.{{ $loop->index }}.judul" />
                    <x-form-error key="kegiatan.{{ $loop->index }}.judul" />
                </div>
                <div class="mb-3">
                    <x-form-label id="deskripsi{{ $kegiatan['id'] }}"
                        label="Deskripsi Kegiatan {{ $loop->iteration }} (ID: {{ $kegiatan['id'] }})" />
                    <x-form-input id="deskripsi{{ $kegiatan['id'] }}" name="deskripsi{{ $kegiatan['id'] }}"
                        wire:model.defer="kegiatan.{{ $loop->index }}.deskripsi" />
                    <x-form-error key="kegiatan.{{ $loop->index }}.deskripsi" />
                </div>
                <div class="mb-3">
                    <x-form-label id="tgl_kegiatan{{ $kegiatan['id'] }}"
                        label="Tanggal Kegiatan {{ $loop->iteration }} (ID: {{ $kegiatan['id'] }})" />
                    <x-form-input type id="tgl_kegiatan{{ $kegiatan['id'] }}" name="tgl_kegiatan{{ $kegiatan['id'] }}"
                        type="date" wire:model.defer="kegiatan.{{ $loop->index }}.tgl_kegiatan" />
                    <x-form-error key="kegiatan.{{ $loop->index }}.tgl_kegiatan" />
                </div>
                <div class="mb-3">
                    <x-form-label id="waktu_mulai{{ $kegiatan['id'] }}"
                        label="Waktu mulai kegiatan {{ $loop->iteration }} (ID: {{ $kegiatan['id'] }})" />
                    <x-form-input type id="waktu_mulai{{ $kegiatan['id'] }}" name="waktu_mulai{{ $kegiatan['id'] }}"
                        type="time" wire:model.defer="kegiatan.{{ $loop->index }}.waktu_mulai" />
                    <x-form-error key="kegiatan.{{ $loop->index }}.waktu_mulai" />
                </div>
                <div class="mb-3">
                    <x-form-label id="waktu_selesai{{ $kegiatan['id'] }}"
                        label="Waktu selesai kegiatan {{ $loop->iteration }} (ID: {{ $kegiatan['id'] }})" />
                    <x-form-input type id="waktu_selesai{{ $kegiatan['id'] }}" name="waktu_selesai{{ $kegiatan['id'] }}"
                        type="time" wire:model.defer="kegiatan.{{ $loop->index }}.waktu_selesai" />
                    <x-form-error key="kegiatan.{{ $loop->index }}.waktu_selesai" />
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
