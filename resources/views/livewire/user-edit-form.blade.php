<div>
    <form wire:submit.prevent="saveUsers" method="post" novalidate>
        @include('partials.alerts')
        @foreach ($users as $user)

        <div class="mb-3">
            <div class="w-100">
                <div class="mb-3">
                    <x-form-label id="name{{ $user['id'] }}"
                        label="Nama User {{ $loop->iteration }} (ID: {{ $user['id'] }})" />
                    <x-form-input id="name{{ $user['id'] }}" name="name{{ $user['id'] }}"
                        wire:model.defer="users.{{ $loop->index }}.name" />
                    <x-form-error key="users.{{ $loop->index }}.name" />
                </div>
                <div class="mb-3">
                    <x-form-label id="email{{ $user['id'] }}" label='Email User {{ $loop->iteration }}' />
                    <x-form-input id="email{{ $user['id'] }}" name="email{{ $user['id'] }}" type="email"
                        wire:model.defer="users.{{ $loop->index }}.email" placeholder="Email aktif" />
                    <x-form-error key="users.{{ $loop->index }}.email" />
                </div>
                <div class="mb-3">
                    <x-form-label id="phone{{ $user['id'] }}" label='No. Telp {{ $loop->iteration }}' />
                    <x-form-input id="phone{{ $user['id'] }}" name="phone{{ $user['id'] }}"
                        wire:model.defer="users.{{ $loop->index }}.phone" placeholder="Format: 08**" />
                    <x-form-error key="users.{{ $loop->index }}.phone" />
                </div>
                <div class="mb-3">
                    <x-form-label id="password{{ $user['id'] }}" label='Password hanya bisa diubah oleh peserta magang'
                        required="false" />
                    <x-form-input id="password{{ $user['id'] }}" name="password{{ $user['id'] }}" disabled
                        required="false" />
                </div>
                <div class="mb-3">
                    <x-form-label id="bidang_id{{ $user['id'] }}" label='Bidang {{ $loop->iteration }}' />
                    <select class="form-select" aria-label="Default select example" name="bidang_id"
                        wire:model.defer="users.{{ $loop->index }}.bidang_id">
                        <option selected disabled>-- Pilih Bidang/Divisi --</option>
                        @foreach ($bidangs as $bidang)
                        <option value="{{ $bidang->id }}">{{ ucfirst($bidang->name) }}</option>
                        @endforeach
                    </select>
                    <x-form-error key="bidangs.{{ $loop->index }}.bidang_id" />
                </div>
                <div class="mb-3">
                    <x-form-label id="role_id{{ $user['id'] }}" label='Role {{ $loop->iteration }}' />
                    <select class="form-select" aria-label="Default select example" name="role_id"
                        wire:model.defer="users.{{ $loop->index }}.role_id">
                        <option selected disabled>-- Pilih Role --</option>
                        @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                    <x-form-error key="users.{{ $loop->index }}.role_id" />
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