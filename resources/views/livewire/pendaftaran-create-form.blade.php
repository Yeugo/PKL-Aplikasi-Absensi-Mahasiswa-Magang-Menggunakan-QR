<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Form Pendaftaran Peserta Magang</h4>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <form wire:submit.prevent="save">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" wire:model="name" placeholder="Masukkan nama lengkap">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label for="npm" class="form-label">NPM</label>
                    <input type="text" class="form-control" id="npm" wire:model="npm" placeholder="Masukkan NPM">
                    @error('npm') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" wire:model="email" placeholder="Masukkan email">
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Telepon</label>
                    <input type="text" class="form-control" id="phone" wire:model="phone" placeholder="Masukkan nomor telepon">
                    @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label for="univ" class="form-label">Universitas</label>
                    <input type="text" class="form-control" id="univ" wire:model="univ" placeholder="Masukkan nama universitas">
                    @error('univ') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select class="form-select" id="jenis_kelamin" wire:model="jenis_kelamin">
                        <option selected>-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                    @error('jenis_kelamin') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea class="form-control" id="alamat" wire:model="alamat" placeholder="Masukkan alamat lengkap"></textarea>
                    @error('alamat') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label for="bidang_id" class="form-label">Bidang</label>
                    <select class="form-select" id="bidang_id" wire:model="bidang_id">
                        <option selected>-- Pilih Bidang --</option>
                        @foreach ($bidangs as $bidang)
                            <option value="{{ $bidang->id }}">{{ $bidang->name }}</option>
                        @endforeach
                    </select>
                    @error('bidang_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label for="surat_pengantar" class="form-label">Surat Pengantar</label>
                    <input type="file" class="form-control" id="surat_pengantar" wire:model="surat_pengantar">
                    @error('surat_pengantar') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label for="dokumen_lain" class="form-label">Dokumen Lain (Foto)</label>
                    <input type="file" class="form-control" id="dokumen_lain" wire:model="dokumen_lain">
                    @error('dokumen_lain') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn btn-primary">Daftar</button>
                <a href="{{ route('auth.login') }}" class="btn btn-danger">Keluar</a>
            </form>
        </div>
    </div>
</div>