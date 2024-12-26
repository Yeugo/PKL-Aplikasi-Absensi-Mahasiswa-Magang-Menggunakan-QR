<div>
    <form wire:submit.prevent="saveUsers" method="post" novalidate>
        @include('partials.alerts')
        @foreach ($users as $i => $user)
        <div class="mb-3">
            <div class="w-100">
                <div class="mb-3">
                    <x-form-label id="email{{ $i }}" label='Email User {{ $i + 1 }}' />
                    <x-form-input id="email{{ $i }}" name="email{{ $i }}" type="email"
                        wire:model.defer="users.{{ $i }}.email" placeholder="Email aktif" />
                    <x-form-error key="users.{{ $i }}.email" />
                </div>
                <div class="mb-3">
                    <x-form-label id="password{{ $i }}"
                        label='Password User {{ $i + 1 }} (default: "123" jika tidak diisi)' required="false" />
                    <x-form-input id="password{{ $i }}" name="password{{ $i }}"
                        wire:model.defer="users.{{ $i }}.password" required="false" />
                    <x-form-error key=" users.{{ $i }}.password" />
                </div>
                <div class="mb-3">
                    <x-form-label id="role_id{{ $i }}" label='Role {{ $i + 1 }}' />
                    <select class="form-select" aria-label="Default select example" name="role_id"
                        wire:model.defer="users.{{ $i }}.role_id">
                        <option selected disabled>-- Pilih Role --</option>
                        @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                    <x-form-error key="users.{{ $i }}.role_id" />
                </div>
            </div>
            @if ($i > 0)
            <button class="btn btn-sm btn-danger mt-2" wire:click="removeUserInput({{ $i }})"
                wire:target="removeUserInput({{ $i }})" type="button" wire:loading.attr="disabled">Hapus</button>
            @endif
        </div>
        <hr>
        @endforeach

        <div class="d-flex justify-content-between align-items-center mb-5">
            <button class="btn btn-primary">
                Simpan
            </button>
            <button class="btn btn-light" type="button" wire:click="addUserInput" wire:loading.attr="disabled">
                Tambah Input
            </button>
        </div>
    </form>
</div>