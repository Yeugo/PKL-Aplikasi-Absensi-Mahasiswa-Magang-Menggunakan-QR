<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Bidang;
use Livewire\Component;
use App\Models\Pembimbing;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;

class PembimbingCreateForm extends Component
{
    use WithFileUploads;

    public $pembimbing;
    public Collection $bidangs;

    public function mount()
    {
        $this->bidangs = Bidang::all();
        $this->pembimbing = [
            ['name' => '',
             'nip' => '',
             'email' => '',
             'phone' => '',
             'alamat' => '',
             'bidang_id' => $this->bidangs->first()->id,
             'foto' => null]
        ];
    }

    public function addPembimbingInput(): void
    {
        $this->pembimbing[] =
        ['name' => '',
         'nip' => '',
         'email' => '',
         'phone' => '',
         'alamat' => '',
         'bidang_id' => $this->bidangs->first()->id,
         'foto' => null,];
    }

    public function removePembimbingInput(int $index): void
    {
        unset($this->pembimbing[$index]);
        $this->pembimbing = array_values($this->pembimbing);
    }

    public function savePembimbings()
    {
        // cara lebih cepat, dan kemungkinan data role tidak akan diubah/ditambah
        $bidangIdRuleIn = join(',', $this->bidangs->pluck('id')->toArray());

        // $roleIdRuleIn = join(',', Role::all()->pluck('id')->toArray());

        // setidaknya input pertama yang hanya required,
        // karena nanti akan difilter apakah input kedua dan input selanjutnya apakah berisi
        $this->validate([
            'pembimbing.*.name' => 'required',
            'pembimbing.*.nip' => 'required|unique:pembimbing,nip',
            'pembimbing.*.email' => 'required|email|unique:users,email',
            'pembimbing.*.phone' => 'required|unique:pembimbing,phone',
            'pembimbing.*.alamat' => 'required',
            'pembimbing.*.bidang_id' => 'required|in:' . $bidangIdRuleIn,
            'pembimbing.*.foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        // cek apakah no. telp yang diinput unique
        $phoneNumbers = array_map(function ($pembimbing) {
            return trim($pembimbing['phone']);
        }, $this->pembimbing);
        $uniquePhoneNumbers = array_unique($phoneNumbers);

        if (count($phoneNumbers) != count($uniquePhoneNumbers)) {
            // layar browser ke paling atas agar user melihat alert error
            $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
            return session()->flash('failed', 'Pastikan input No. Telp tidak mangandung nilai yang sama.');
        }

        // alasan menggunakan create alih2 mengunakan ::insert adalah karena tidak looping untuk menambahkan created_at dan updated_at
        $affected = 0;
        // foreach ($this->pembimbing as $pembimbing) {
        //     Pembimbing::create($pembimbing);
        //     $affected++;
        // }
        foreach ($this->pembimbing as $pembimbing) {
            // Menangani foto jika ada
            if (isset($pembimbing['foto']) && $pembimbing['foto'] instanceof \Illuminate\Http\UploadedFile) {
                $filename = $pembimbing['nip'] . '.' . $pembimbing['foto']->getClientOriginalExtension(); // Nama file berdasarkan nip
                $path = $pembimbing['foto']->storeAs('pembimbing_photos', $filename, 'public'); // Simpan foto di storage
                
                // Tambahkan path ke dalam data pembimbing
                $pembimbing['foto'] = $path;
            } else {
                // Pastikan field foto tetap null jika tidak ada foto
                $pembimbing['foto'] = null;
            }

            // Tetapkan password default
            $randomPassword = Str::random(12);

            // Enkripsi password default
            $password = Hash::make($randomPassword);

            // Buat User
            $user = User::create([
                'email' => $pembimbing['email'],
                'password' => $password, // Gunakan password default yang sudah dienkripsi
                'role_id' => 1, // ID role untuk pembimbing
            ]);
        
            // Simpan data peserta ke database
            // Peserta::create([
            $user->pembimbing()->create([
                'name' => $pembimbing['name'],
                'nip' => $pembimbing['nip'],
                'phone' => $pembimbing['phone'],
                'alamat' => $pembimbing['alamat'],
                'bidang_id' => $pembimbing['bidang_id'],
                'foto' => $pembimbing['foto'], // Simpan path foto jika ada
            ]);
            $affected++;
        }

        redirect()->route('pembimbing.index')->with('success', "Ada ($affected) data pembimbing magang yang berhasil ditambahkan.");
    }

    public function render()
    {
        return view('livewire.pembimbing-create-form');
    }
}
