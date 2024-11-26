<div>
    <form wire:submit.prevent="save" method="post" novalidate>
        @include('partials.alerts')
        <div class="w-100">
            <div class="mb-3">
                <x-form-label id="title" label='Nama/Judul Absensi' />
                <x-form-input id="title" name="title" wire:model.defer="absensi.title" />
                <x-form-error key="absensi.title" />
            </div>
            <div class="mb-3">
                <x-form-label id="description" label='Keterangan' />
                <textarea id="description" name="description" class="form-control"
                    wire:model.defer="absensi.description"></textarea>
                <x-form-error key="absensi.description" />
            </div>
            <div class="mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <x-form-label id="start_time" label='Waktu Absen Masuk' />
                        <x-form-input type="text" maxlength="5" id="start_time" name="start_time"
                            wire:model.defer="absensi.start_time" placeholder="07:00" />
                        <x-form-error key="absensi.start_time" />
                    </div>
                    <div class="col-md-6">
                        <x-form-label id="batas_start_time" label='Batas Waktu Absen Masuk' />
                        <x-form-input type="text" maxlength="5" id="batas_start_time" name="batas_start_time"
                            wire:model.defer="absensi.batas_start_time" />
                        <x-form-error key="absensi.batas_start_time" />
                    </div>
                </div>
                <small class="text-muted d-block mt-1">Masukan dengan format 24:00.</small>
            </div>
            <div class="mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <x-form-label id="end_time" label='Waktu Absen Pulang' />
                        <x-form-input type="text" maxlength="5" id="end_time" name="end_time"
                            wire:model.defer="absensi.end_time" />
                        <x-form-error key="absensi.end_time" />
                    </div>
                    <div class="col-md-6">
                        <x-form-label id="batas_end_time" label='Batas Waktu Absen Pulang' />
                        <x-form-input type="text" maxlength="5" id="batas_end_time" name="batas_end_time"
                            wire:model.defer="absensi.batas_end_time" />
                        <x-form-error key="absensi.batas_end_time" />
                    </div>
                </div>
                <small class="text-muted d-block mt-1">Masukan dengan format 24:00.</small>
            </div>

            {{-- <div class="mb-3">
                <x-form-label id="positions" label='Posisi Karyawaan' />
                <div class="row ms-1">
                    @foreach ($positions as $position)
                    <div class="form-check col-sm-4">
                        <input class="form-check-input" type="checkbox" value="{{ $position->id }}"
                            wire:model.defer="position_ids.{{ $position->id }}"
                            id="flexCheckPosition{{ $loop->index }}">
                        <label class="form-check-label" for="flexCheckPosition{{ $loop->index }}">
                            {{ $position->name }}
                        </label>
                    </div>
                    @endforeach
                </div>
                <small class="text-muted d-block mt-1">Pilih posisi karyawaan yang akan menggunakan absensi ini.</small>
                <x-form-error key="position_ids" />
                //tom-select init script ada di create.blade.php absensis
            </div> --}}

            <div class="mb-3">
                <x-form-label id="flexCheckCode" label="Ingin Menggunakan QRCode (default menggunakan tombol)" />
                <div class=" form-check">
                    <input class="form-check-input" type="checkbox" value="" wire:model="absensi.code"
                        id="flexCheckCode">
                    <label class="form-check-label" for="flexCheckCode">
                        Menggunakan QRCode
                    </label>
                    <small class="text-muted d-block mt-1">Jika checkbox tersebut diubah maka kemungkinan kode qrcode
                        berubah.</small>
                    <x-form-error key="absensi.code" />
                </div>
            </div>

        </div>

        <div class="d-flex justify-content-between align-items-center mb-5">
            <button class="btn btn-primary">
                Simpan
            </button>
        </div>
    </form>
</div>