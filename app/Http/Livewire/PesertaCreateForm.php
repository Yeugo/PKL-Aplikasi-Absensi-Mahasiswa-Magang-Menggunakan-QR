<?php

namespace App\Http\Livewire;

use App\Models\Role;
use App\Models\Bidang;
use App\Models\Peserta;
use Livewire\Component;
use App\Models\Pembimbing;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;
use Livewire\WithFileUploads;

class PesertaCreateForm extends Component
{
    use WithFileUploads;
    
    public $peserta;
    public Collection $bidangs;
    public Collection $pembimbings;

    public function mount()
    {
        $this->bidangs = Bidang::all();
        $this->pembimbings = Pembimbing::all();
        $this->peserta = [
            ['name' => '', 
             'npm' => '',
             'phone' => '',  
             'univ' => '',
             'alamat' => '',
             'bidang_id' => $this->bidangs->first()->id,
             'pembimbing_id' => $this->pembimbings->first()->id,
             'foto' => null]
        ];
    }

    public function addPesertaInput(): void
    {
        $this->peserta[] = 
        ['name' => '', 
         'npm' => '',
         'phone' => '',  
         'univ' => '',
         'alamat' => '',
         'bidang_id' => $this->bidangs->first()->id,
         'pembimbing_id' => $this->pembimbings->first()->id,
         'foto' => null,];
    }

    public function removePesertaInput(int $index): void
    {
        unset($this->peserta[$index]);
        $this->peserta = array_values($this->peserta);
    }

    public function savePesertas()
    {
        // cara lebih cepat, dan kemungkinan data role tidak akan diubah/ditambah
        $bidangIdRuleIn = join(',', $this->bidangs->pluck('id')->toArray());
        $pembimbingIdRuleIn = join(',', $this->pembimbings->pluck('id')->toArray());

        // $roleIdRuleIn = join(',', Role::all()->pluck('id')->toArray());

        // setidaknya input pertama yang hanya required,
        // karena nanti akan difilter apakah input kedua dan input selanjutnya apakah berisi
        $this->validate([
            'peserta.*.name' => 'required',
            'peserta.*.npm' => 'required|unique:peserta,npm',
            'peserta.*.phone' => 'required|unique:peserta,phone',
            'peserta.*.univ' => 'required',
            'peserta.*.alamat' => 'required',
            'peserta.*.bidang_id' => 'required|in:' . $bidangIdRuleIn,
            'peserta.*.pembimbing_id' => 'required|in:' . $pembimbingIdRuleIn,
            'peserta.*.foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        // cek apakah no. telp yang diinput unique
        $phoneNumbers = array_map(function ($peserta) {
            return trim($peserta['phone']);
        }, $this->peserta);
        $uniquePhoneNumbers = array_unique($phoneNumbers);

        if (count($phoneNumbers) != count($uniquePhoneNumbers)) {
            // layar browser ke paling atas agar user melihat alert error
            $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
            return session()->flash('failed', 'Pastikan input No. Telp tidak mangandung nilai yang sama.');
        }

        // alasan menggunakan create alih2 mengunakan ::insert adalah karena tidak looping untuk menambahkan created_at dan updated_at
        $affected = 0;
        // foreach ($this->peserta as $peserta) {
        //     Peserta::create($peserta);
        //     $affected++;
        // }

        foreach ($this->peserta as $peserta) {
            // Menangani foto jika ada
            if (isset($peserta['foto']) && $peserta['foto'] instanceof \Illuminate\Http\UploadedFile) {
                $filename = $peserta['npm'] . '.' . $peserta['foto']->getClientOriginalExtension(); // Nama file berdasarkan npm
                $path = $peserta['foto']->storeAs('peserta_photos', $filename, 'public'); // Simpan foto di storage
                
                // Tambahkan path ke dalam data peserta
                $peserta['foto'] = $path;
            } else {
                // Pastikan field foto tetap null jika tidak ada foto
                $peserta['foto'] = null;
            }
        
            // Simpan data peserta ke database
            Peserta::create([
                'name' => $peserta['name'],
                'npm' => $peserta['npm'],
                'phone' => $peserta['phone'],
                'univ' => $peserta['univ'],
                'alamat' => $peserta['alamat'],
                'bidang_id' => $peserta['bidang_id'],
                'pembimbing_id' => $peserta['pembimbing_id'],
                'foto' => $peserta['foto'], // Simpan path foto jika ada
            ]);
            $affected++;
        }

        
        
        

        redirect()->route('peserta.index')->with('success', "Ada ($affected) data peserta magang yang berhasil ditambahkan.");
    }

    public function render()
    {
        return view('livewire.peserta-create-form');
    }
}

