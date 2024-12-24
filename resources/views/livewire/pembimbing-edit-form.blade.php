<div>
    <form wire:submit.prevent="savePembimbing" method="post" novalidate enctype="multipart/form-data">
        @include('partials.alerts')
        @foreach ($pembimbing as $pembimbing)

        <div class="mb-3">
            <div class="w-100">
                <div class="mb-3">
                    <x-form-label id="name{{ $pembimbing['id'] }}"
                        label="Nama Pembimbing {{ $loop->iteration }} (ID: {{ $pembimbing['id'] }})" />
                    <x-form-input id="name{{ $pembimbing['id'] }}" name="name{{ $pembimbing['id'] }}"
                        wire:model.defer="pembimbing.{{ $loop->index }}.name" />
                    <x-form-error key="pembimbing.{{ $loop->index }}.name" />
                </div>
                <div class="mb-3">
                    <x-form-label id="nip{{ $pembimbing['id'] }}"
                        label="NIP {{ $loop->iteration }} (ID: {{ $pembimbing['id'] }})" />
                    <x-form-input id="nip{{ $pembimbing['id'] }}" name="nip{{ $pembimbing['id'] }}"
                        wire:model.defer="pembimbing.{{ $loop->index }}.nip" />
                    <x-form-error key="pembimbing.{{ $loop->index }}.nip" />
                </div>
                <div class="mb-3">
                    <x-form-label id="phone{{ $pembimbing['id'] }}" label='No. Telp {{ $loop->iteration }}' />
                    <x-form-input id="phone{{ $pembimbing['id'] }}" name="phone{{ $pembimbing['id'] }}"
                        wire:model.defer="pembimbing.{{ $loop->index }}.phone" placeholder="Format: 08**" />
                    <x-form-error key="pembimbing.{{ $loop->index }}.phone" />
                </div>
                <div class="mb-3">
                    <x-form-label id="bidang_id{{ $pembimbing['id'] }}" label='Bidang {{ $loop->iteration }}' />
                    <select class="form-select" aria-label="Default select example" name="bidang_id"
                        wire:model.defer="pembimbing.{{ $loop->index }}.bidang_id">
                        <option selected disabled>-- Pilih Bidang/Divisi --</option>
                        @foreach ($bidangs as $bidang)
                        <option value="{{ $bidang->id }}">{{ ucfirst($bidang->name) }}</option>
                        @endforeach
                    </select>
                    <x-form-error key="pembimbing.{{ $loop->index }}.bidang_id" />
                </div>
                <div class="mb-3">
                    <x-form-label id="foto{{ $pembimbing['id'] }}" 
                        label="Foto Pembimbing {{ $loop->iteration }} (ID: {{ $pembimbing['id'] }})" />
                    <input type="file" id="foto{{ $pembimbing['id'] }}" 
                        wire:model="pembimbing.{{ $loop->index }}.foto"
                        class="form-control" accept="image/*">
                    <x-form-error key="pembimbing.{{ $loop->index }}.foto" />
                    
                    <!-- Preview Foto -->
                    @if (isset($pembimbing['foto']) && $pembimbing['foto'] instanceof \Livewire\TemporaryUploadedFile)
                        <div class="mt-2">
                            <img src="{{ $pembimbing['foto']->temporaryUrl() }}" alt="Preview Foto"
                                class="img-thumbnail" style="max-width: 150px;">
                        </div>
                    @endif
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
