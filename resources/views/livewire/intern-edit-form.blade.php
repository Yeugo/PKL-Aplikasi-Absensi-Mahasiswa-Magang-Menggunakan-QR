<div>
    <form wire:submit.prevent="saveInterns" method="post" novalidate>
        @include('partials.alerts')
        @foreach ($interns as $intern)

        <div class="mb-3">
            <div class="w-100">
                <div class="mb-3">
                    <x-form-label id="name{{ $intern['id'] }}"
                        label="Nama Intern {{ $loop->iteration }} (ID: {{ $intern['id'] }})" />
                    <x-form-input id="name{{ $intern['id'] }}" name="name{{ $intern['id'] }}"
                        wire:model.defer="interns.{{ $loop->index }}.name" />
                    <x-form-error key="interns.{{ $loop->index }}.name" />
                </div>
                <div class="mb-3">
                    <x-form-label id="email{{ $intern['id'] }}" label='Email Intern {{ $loop->iteration }}' />
                    <x-form-input id="email{{ $intern['id'] }}" name="email{{ $intern['id'] }}" type="email"
                        wire:model.defer="interns.{{ $loop->index }}.email" placeholder="Email aktif" />
                    <x-form-error key="interns.{{ $loop->index }}.email" />
                </div>
                <div class="mb-3">
                    <x-form-label id="phone{{ $intern['id'] }}" label='No. Telp {{ $loop->iteration }}' />
                    <x-form-input id="phone{{ $intern['id'] }}" name="phone{{ $intern['id'] }}"
                        wire:model.defer="interns.{{ $loop->index }}.phone" placeholder="Format: 08**" />
                    <x-form-error key="interns.{{ $loop->index }}.phone" />
                </div>
                <div class="mb-3">
                    <x-form-label id="password{{ $intern['id'] }}" label='Password hanya bisa diubah oleh peserta magang'
                        required="false" />
                    <x-form-input id="password{{ $intern['id'] }}" name="password{{ $intern['id'] }}" disabled
                        required="false" />
                </div>
                <div class="mb-3">
                    <x-form-label id="bidang_id{{ $intern['id'] }}" label='Bidang {{ $loop->iteration }}' />
                    <select class="form-select" aria-label="Default select example" name="bidang_id"
                        wire:model.defer="interns.{{ $loop->index }}.bidang_id">
                        <option selected disabled>-- Pilih Bidang/Divisi --</option>
                        @foreach ($bidangs as $bidang)
                        <option value="{{ $bidang->id }}">{{ ucfirst($bidang->name) }}</option>
                        @endforeach
                    </select>
                    <x-form-error key="bidangs.{{ $loop->index }}.bidang_id" />
                </div>
                <div class="mb-3">
                    <x-form-label id="role_id{{ $intern['id'] }}" label='Role {{ $loop->iteration }}' />
                    <select class="form-select" aria-label="Default select example" name="role_id"
                        wire:model.defer="interns.{{ $loop->index }}.role_id">
                        <option selected disabled>-- Pilih Role --</option>
                        @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                    <x-form-error key="interns.{{ $loop->index }}.role_id" />
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