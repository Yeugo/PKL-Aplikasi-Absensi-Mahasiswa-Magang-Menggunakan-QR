<div>
    <form wire:submit.prevent="savePembimbings" method="post" novalidate enctype="multipart/form-data">
        @include('partials.alerts')
        @foreach ($pembimbing as $i => $pembimbing)
        <div class="mb-3">
            <div class="w-100">
                <div class="mb-3">
                    <x-form-label id="name{{ $i }}" label='Nama Pembimbing {{ $i + 1 }}' />
                    <x-form-input id="name{{ $i }}" name="name{{ $i }}" wire:model.defer="pembimbing.{{ $i }}.name" />
                    <x-form-error key="pembimbing.{{ $i }}.name" />
                </div>
                <div class="mb-3">
                    <x-form-label id="nip{{ $i }}" label='NIP {{ $i + 1 }}' />
                    <x-form-input id="nip{{ $i }}" name="nip{{ $i }}"
                     wire:model.defer="pembimbing.{{ $i }}.nip" />
                    <x-form-error key="pembimbing.{{ $i }}.nip" />
                </div>
                <div class="mb-3">
                    <x-form-label id="email{{ $i }}" label='Email Pembimbing {{ $i + 1 }}' />
                    <x-form-input id="email{{ $i }}" name="email{{ $i }}" type="email"
                        wire:model.defer="pembimbing.{{ $i }}.email" placeholder="Email aktif" />
                    <x-form-error key="pembimbing.{{ $i }}.email" />
                </div>
                <div class="mb-3">
                    <x-form-label id="phone{{ $i }}" label='No. Telp {{ $i + 1 }}' />
                    <x-form-input id="phone{{ $i }}" name="phone{{ $i }}" wire:model.defer="pembimbing.{{ $i }}.phone"
                        placeholder="Format: 08**" />
                    <x-form-error key="pembimbing.{{ $i }}.phone" />
                </div>
                <div class="mb-3">
                    <x-form-label id="alamat{{ $i }}" label='Alamat {{ $i + 1 }}' />
                    <x-form-input id="alamat{{ $i }}" name="alamat{{ $i }}"
                     wire:model.defer="pembimbing.{{ $i }}.alamat" />
                    <x-form-error key="pembimbing.{{ $i }}.alamat" />
                </div>
                <div class="mb-3">
                    <x-form-label id="bidang_id{{ $i }}" label='Bidang / Divisi {{ $i + 1 }}' />
                    <select class="form-select" aria-label="Default select example" name="bidang_id"
                        wire:model.defer="pembimbing.{{ $i }}.bidang_id">
                        <option selected>-- Pilih Bidang --</option>
                        @foreach ($bidangs as $bidang)
                        <option value="{{ $bidang->id }}">{{ ucfirst($bidang->name) }}</option>
                        @endforeach
                    </select>
                    <x-form-error key="pembimbing.{{ $i }}.bidang_id" />
                </div>
                <div class="mb-3">
                    <x-form-label id="foto{{ $i }}" label="Foto Pembimbing {{ $i + 1 }}" />
                    <input type="file" class="form-control" id="foto{{ $i }}" wire:model="pembimbing.{{ $i }}.foto">
                    <x-form-error key="pembimbing.{{ $i }}.foto" />
                </div>
                
                
            </div>
            @if ($i > 0)
            <button class="btn btn-sm btn-danger mt-2" wire:click="removePembimbingInput({{ $i }})"
                wire:target="removePembimbingInput({{ $i }})" type="button" wire:loading.attr="disabled">Hapus</button>
            @endif
        </div>
        <hr>
        @endforeach

        <div class="d-flex justify-content-between align-items-center mb-5">
            <button class="btn btn-primary">
                Simpan
            </button>
            <button class="btn btn-secondary" type="button" wire:click="addPembimbingInput" wire:loading.attr="disabled">
                Tambah Input
            </button>
        </div>
    </form>
</div>
