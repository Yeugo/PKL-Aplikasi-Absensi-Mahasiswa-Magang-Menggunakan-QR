<div>
    <form wire:submit.prevent="savePeserta" method="post" novalidate>
        @include('partials.alerts')
        @foreach ($peserta as $peserta)

        <div class="mb-3">
            <div class="w-100">
                <div class="mb-3">
                    <x-form-label id="name{{ $peserta['id'] }}"
                        label="Nama Peserta {{ $loop->iteration }} (ID: {{ $peserta['id'] }})" />
                    <x-form-input id="name{{ $peserta['id'] }}" name="name{{ $peserta['id'] }}"
                        wire:model.defer="peserta.{{ $loop->index }}.name" />
                    <x-form-error key="peserta.{{ $loop->index }}.name" />
                </div>
                <div class="mb-3">
                    <x-form-label id="npm{{ $peserta['id'] }}"
                        label="NIM / NPM {{ $loop->iteration }} (ID: {{ $peserta['id'] }})" />
                    <x-form-input id="npm{{ $peserta['id'] }}" name="npm{{ $peserta['id'] }}"
                        wire:model.defer="peserta.{{ $loop->index }}.npm" />
                    <x-form-error key="peserta.{{ $loop->index }}.npm" />
                </div>
                <div class="mb-3">
                    <x-form-label id="phone{{ $peserta['id'] }}" label='No. Telp {{ $loop->iteration }}' />
                    <x-form-input id="phone{{ $peserta['id'] }}" name="phone{{ $peserta['id'] }}"
                        wire:model.defer="peserta.{{ $loop->index }}.phone" placeholder="Format: 08**" />
                    <x-form-error key="peserta.{{ $loop->index }}.phone" />
                </div>
                <div class="mb-3">
                    <x-form-label id="univ{{ $peserta['id'] }}"
                        label="Universitas {{ $loop->iteration }} (ID: {{ $peserta['id'] }})" />
                    <x-form-input id="univ{{ $peserta['id'] }}" name="univ{{ $peserta['id'] }}"
                        wire:model.defer="peserta.{{ $loop->index }}.univ" />
                    <x-form-error key="peserta.{{ $loop->index }}.univ" />
                </div>
                <div class="mb-3">
                    <x-form-label id="bidang_id{{ $peserta['id'] }}" label='Bidang {{ $loop->iteration }}' />
                    <select class="form-select" aria-label="Default select example" name="bidang_id"
                        wire:model.defer="peserta.{{ $loop->index }}.bidang_id">
                        <option selected disabled>-- Pilih Bidang/Divisi --</option>
                        @foreach ($bidangs as $bidang)
                        <option value="{{ $bidang->id }}">{{ ucfirst($bidang->name) }}</option>
                        @endforeach
                    </select>
                    <x-form-error key="bidangs.{{ $loop->index }}.bidang_id" />
                </div>
                <div class="mb-3">
                    <x-form-label id="pembimbing_id{{ $peserta['id'] }}" label='Role {{ $loop->iteration }}' />
                    <select class="form-select" aria-label="Default select example" name="pembimbing_id"
                    wire:model.defer="peserta.{{ $loop->index }}.pembimbing_id">
                        <option selected>-- Pilih Pembimbing --</option>
                        @foreach ($pembimbings as $pembimbing)
                        <option value="{{ $pembimbing->id }}">{{ ucfirst($pembimbing->name) }}</option>
                        @endforeach
                    </select>
                    <x-form-error key="users.{{ $loop->index }}.pembimbing_id" />
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
