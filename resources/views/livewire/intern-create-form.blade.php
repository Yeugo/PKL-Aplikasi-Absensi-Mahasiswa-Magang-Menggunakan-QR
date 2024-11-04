<div>
    <form wire:submit.prevent="saveInterns" method="post" novalidate>
        @include('partials.alerts')
        @foreach ($interns as $i => $intern)
        <div class="mb-3">
            <div class="w-100">
                <div class="mb-3">
                    <x-form-label id="name{{ $i }}" label='Nama Intern {{ $i + 1 }}' />
                    <x-form-input id="name{{ $i }}" name="name{{ $i }}" wire:model.defer="interns.{{ $i }}.name" />
                    <x-form-error key="interns.{{ $i }}.name" />
                </div>
                <div class="mb-3">
                    <x-form-label id="email{{ $i }}" label='Email Intern {{ $i + 1 }}' />
                    <x-form-input id="email{{ $i }}" name="email{{ $i }}" type="email"
                        wire:model.defer="interns.{{ $i }}.email" placeholder="Email aktif" />
                    <x-form-error key="interns.{{ $i }}.email" />
                </div>
                <div class="mb-3">
                    <x-form-label id="password{{ $i }}"
                        label='Password Karyawaan {{ $i + 1 }} (default: "123" jika tidak diisi)' required="false" />
                    <x-form-input id="password{{ $i }}" name="password{{ $i }}"
                        wire:model.defer="interns.{{ $i }}.password" required="false" />
                    <x-form-error key=" interns.{{ $i }}.password" />
                </div>
                <div class="mb-3">
                    <x-form-label id="phone{{ $i }}" label='No. Telp {{ $i + 1 }}' />
                    <x-form-input id="phone{{ $i }}" name="phone{{ $i }}" wire:model.defer="interns.{{ $i }}.phone"
                        placeholder="Format: 08**" />
                    <x-form-error key="interns.{{ $i }}.phone" />
                </div>
                <div class="mb-3">
                    <x-form-label id="bidang_id{{ $i }}" label='Bidang / Department {{ $i + 1 }}' />
                    <select class="form-select" aria-label="Default select example" name="bidang_id"
                        wire:model.defer="interns.{{ $i }}.bidang_id">
                        <option selected disabled>-- Pilih Bidang --</option>
                        @foreach ($bidangs as $bidang)
                        <option value="{{ $bidang->id }}">{{ ucfirst($bidang->name) }}</option>
                        @endforeach
                    </select>
                    <x-form-error key="interns.{{ $i }}.bidang_id" />
                </div>
                <div class="mb-3">
                    <x-form-label id="role_id{{ $i }}" label='Role {{ $i + 1 }}' />
                    <select class="form-select" aria-label="Default select example" name="role_id"
                        wire:model.defer="interns.{{ $i }}.role_id">
                        <option selected disabled>-- Pilih Role --</option>
                        @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                    <x-form-error key="interns.{{ $i }}.role_id" />
                </div>
            </div>
            @if ($i > 0)
            <button class="btn btn-sm btn-danger mt-2" wire:click="removeInternInput({{ $i }})"
                wire:target="removeInternInput({{ $i }})" type="button" wire:loading.attr="disabled">Hapus</button>
            @endif
        </div>
        <hr>
        @endforeach

        <div class="d-flex justify-content-between align-items-center mb-5">
            <button class="btn btn-primary">
                Simpan
            </button>
            <button class="btn btn-light" type="button" wire:click="addInternInput" wire:loading.attr="disabled">
                Tambah Input
            </button>
        </div>
    </form>
</div>