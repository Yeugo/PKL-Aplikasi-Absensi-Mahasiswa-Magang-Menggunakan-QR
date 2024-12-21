<?php

namespace App\Http\Livewire;

use App\Models\Bidang;
use App\Models\Pembimbing;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class PembimbingCreateForm extends Component
{
    public $pembimbing;
    public Collection $bidangs;

    public function mount()
    {
        $this->bidangs = Bidang::all();
        $this->pembimbing = [
            ['name' => '',
             'nip' => '',
             'phone' => '',
             'alamat' => '',
             'bidang_id' => $this->bidangs->first()->id]
        ];
    }

    public function addPembimbingInput(): void
    {
        $this->pembimbing[] =
        ['name' => '',
         'nip' => '',
         'phone' => '',
         'alamat' => '',
         'bidang_id' => $this->bidangs->first()->id];
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
            'pembimbing.*.phone' => 'required|unique:pembimbing,phone',
            'pembimbing.*.alamat' => 'required',
            'pembimbing.*.bidang_id' => 'required|in:' . $bidangIdRuleIn,
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
        foreach ($this->pembimbing as $pembimbing) {
            Pembimbing::create($pembimbing);
            $affected++;
        }

        redirect()->route('pembimbing.index')->with('success', "Ada ($affected) data pembimbing magang yang berhasil ditambahkan.");
    }

    public function render()
    {
        return view('livewire.pembimbing-create-form');
    }
}
