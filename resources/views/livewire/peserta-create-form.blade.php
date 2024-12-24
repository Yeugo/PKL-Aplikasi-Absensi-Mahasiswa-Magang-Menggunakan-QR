<div>
    <form wire:submit.prevent="savePesertas" method="post" novalidate enctype="multipart/form-data">
        @include('partials.alerts')
        @foreach ($peserta as $i => $peserta)
        <div class="mb-3">
            <div class="w-100">
                <div class="mb-3">
                    <x-form-label id="name{{ $i }}" label='Nama Peserta {{ $i + 1 }}' />
                    <x-form-input id="name{{ $i }}" name="name{{ $i }}" wire:model.defer="peserta.{{ $i }}.name" />
                    <x-form-error key="peserta.{{ $i }}.name" />
                </div>
                <div class="mb-3">
                    <x-form-label id="npm{{ $i }}" label='NIM / NPM {{ $i + 1 }}' />
                    <x-form-input id="npm{{ $i }}" name="npm{{ $i }}"
                     wire:model.defer="peserta.{{ $i }}.npm" />
                    <x-form-error key="peserta.{{ $i }}.npm" />
                </div>
                <div class="mb-3">
                    <x-form-label id="email{{ $i }}" label='Email User {{ $i + 1 }}' />
                    <x-form-input id="email{{ $i }}" name="email{{ $i }}" type="email"
                        wire:model.defer="peserta.{{ $i }}.email" placeholder="Email aktif" />
                    <x-form-error key="peserta.{{ $i }}.email" />
                </div>
                <div class="mb-3">
                    <x-form-label id="phone{{ $i }}" label='No. Telp {{ $i + 1 }}' />
                    <x-form-input id="phone{{ $i }}" name="phone{{ $i }}" wire:model.defer="peserta.{{ $i }}.phone"
                        placeholder="Format: 08**" />
                    <x-form-error key="peserta.{{ $i }}.phone" />
                </div>
                <div class="mb-3">
                    <x-form-label id="univ{{ $i }}" label='Universitas {{ $i + 1 }}' />
                    <x-form-input id="univ{{ $i }}" name="univ{{ $i }}"
                     wire:model.defer="peserta.{{ $i }}.univ" />
                    <x-form-error key="peserta.{{ $i }}.univ" />
                </div>
                <div class="mb-3">
                    <x-form-label id="alamat{{ $i }}" label='Alamat {{ $i + 1 }}' />
                    <x-form-input id="alamat{{ $i }}" name="alamat{{ $i }}"
                     wire:model.defer="peserta.{{ $i }}.alamat" />
                    <x-form-error key="peserta.{{ $i }}.alamat" />
                </div>
                <div class="mb-3">
                    <x-form-label id="peserta_bidang_id{{ $i }}" label='Bidang / Divisi {{ $i + 1 }}' />
                    <select class="form-select" aria-label="Default select example" name="peserta_bidang_id"
                        wire:model.defer="peserta.{{ $i }}.peserta_bidang_id">
                        <option selected>-- Pilih Bidang --</option>
                        @foreach ($bidangs as $bidang)
                        <option value="{{ $bidang->id }}">{{ ucfirst($bidang->name) }}</option>
                        @endforeach
                    </select>
                    <x-form-error key="peserta.{{ $i }}.peserta_bidang_id" />
                </div>
                <div class="mb-3">
                    <x-form-label id="pembimbing_id{{ $i }}" label='Pembimbing Magang {{ $i + 1 }}' />
                    <select class="form-select" aria-label="Default select example" name="pembimbing_id"
                        wire:model.defer="peserta.{{ $i }}.pembimbing_id">
                        <option selected>-- Pilih Pembimbing --</option>
                        @foreach ($pembimbings as $pembimbing)
                        <option value="{{ $pembimbing->id }}">{{ ucfirst($pembimbing->name) }}</option>
                        @endforeach
                    </select>
                    <x-form-error key="peserta.{{ $i }}.pembimbing_id" />
                </div>
                <div class="mb-3">
                    <x-form-label id="foto{{ $i }}" label="Foto Peserta {{ $i + 1 }}" />
                    <input type="file" class="form-control" id="foto{{ $i }}" wire:model="peserta.{{ $i }}.foto">
                    <x-form-error key="peserta.{{ $i }}.foto" />
                </div>
            </div>
            
            @if ($i > 0)
            <button class="btn btn-sm btn-danger mt-2" wire:click="removePesertaInput({{ $i }})"
                wire:target="removePesertaInput({{ $i }})" type="button" wire:loading.attr="disabled">Hapus</button>
            @endif
        </div>
        <hr>
        @endforeach

        <div class="d-flex justify-content-between align-items-center mb-5">
            <button class="btn btn-primary">
                Simpan
            </button>
            <button class="btn btn-light" type="button" wire:click="addPesertaInput" wire:loading.attr="disabled">
                Tambah Input
            </button>
        </div>
    </form>
</div>
