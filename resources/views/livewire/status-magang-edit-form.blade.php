<div>
    <form wire:submit.prevent="savePeserta" method="post" novalidate enctype="multipart/form-data">
        @include('partials.alerts')
        @foreach ($peserta as $peserta)

        <div class="mb-3">
            <div class="w-100">
                <div class="mb-3">
                    <x-form-label id="name{{ $peserta['id'] }}"
                        label="Nama Peserta {{ $loop->iteration }} " />
                    <x-form-input id="name{{ $peserta['id'] }}" name="name{{ $peserta['id'] }}"
                        wire:model.defer="peserta.{{ $loop->index }}.name" readonly />
                    <x-form-error key="peserta.{{ $loop->index }}.name" />
                </div>
                <div class="mb-3">
                    <x-form-label id="npm{{ $peserta['id'] }}"
                        label="NPM {{ $loop->iteration }} " />
                    <x-form-input id="npm{{ $peserta['id'] }}" name="npm{{ $peserta['id'] }}"
                        wire:model.defer="peserta.{{ $loop->index }}.npm" readonly />
                    <x-form-error key="peserta.{{ $loop->index }}.npm" />
                </div>
                <div class="mb-3">
                    <x-form-label id="univ{{ $peserta['id'] }}" label='Universitas {{ $loop->iteration }}' />
                    <x-form-input id="univ{{ $peserta['id'] }}" name="univ{{ $peserta['id'] }}"
                        wire:model.defer="peserta.{{ $loop->index }}.univ" readonly />
                    <x-form-error key="peserta.{{ $loop->index }}.univ" />
                </div>
                <div class="mb-3">
                    <x-form-label id="tgl_mulai_magang{{ $peserta['id'] }}" label='Tanggal Mulai Magang {{ $loop->iteration }}' />
                    <x-form-input type="date" id="tgl_mulai_magang{{ $peserta['id'] }}" name="tgl_mulai_magang{{ $peserta['id'] }}"
                        wire:model.defer="peserta.{{ $loop->index }}.tgl_mulai_magang" />
                    <x-form-error key="peserta.{{ $loop->index }}.tgl_mulai_magang" />
                </div>
                <div class="mb-3">
                    <x-form-label id="tgl_selesai_magang_rencana{{ $peserta['id'] }}" label='Tanggal Selesai Magang Rencana {{ $loop->iteration }}' />
                    <x-form-input type="date" id="tgl_selesai_magang_rencana{{ $peserta['id'] }}" name="tgl_selesai_magang_rencana{{ $peserta['id'] }}"
                        wire:model.defer="peserta.{{ $loop->index }}.tgl_selesai_magang_rencana" />
                    <x-form-error key="peserta.{{ $loop->index }}.tgl_selesai_magang_rencana" />
                </div>
                <div class="mb-3">
                    <x-form-label id="status_penyelesaian{{ $peserta['id'] }}" label="Status Penyelesaian {{ $loop->iteration }}" />
                    <select
                        id="status_penyelesaian{{ $peserta['id'] }}"
                        name="status_penyelesaian{{ $peserta['id'] }}"
                        class="form-control"
                        wire:model.defer="peserta.{{ $loop->index }}.status_penyelesaian"
                    >
                        <option value="">-- Pilih Status --</option>
                        <option value="Belum Dimulai">Belum Dimulai</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Diberhentikan">Diberhentikan</option>
                        <option value="Mengundurkan Diri">Mengundurkan Diri</option>
                    </select>
                    <x-form-error key="peserta.{{ $loop->index }}.status_penyelesaian" />
                </div>

                <div class="mb-3">
                    <x-form-label id="tanggal_penyelesaian_aktual{{ $peserta['id'] }}" label='Tanggal Selesai Magang Aktual {{ $loop->iteration }}' />
                    <x-form-input type="date" id="tanggal_penyelesaian_aktual{{ $peserta['id'] }}" name="tanggal_penyelesaian_aktual{{ $peserta['id'] }}"
                        wire:model.defer="peserta.{{ $loop->index }}.tanggal_penyelesaian_aktual" />
                    <x-form-error key="peserta.{{ $loop->index }}.tanggal_penyelesaian_aktual" />
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
