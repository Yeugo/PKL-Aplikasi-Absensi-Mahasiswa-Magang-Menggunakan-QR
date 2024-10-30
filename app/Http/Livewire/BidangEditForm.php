<?php

namespace App\Http\Livewire;

use App\Models\Bidang;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class BidangEditForm extends Component
{
    public $bidangs = [];

    public function mount(Collection $bidangs)
    {
        $this->bidangs = []; // hapus bidangs collection
        foreach ($bidangs as $bidang) {
            $this->bidangs[] = ['id' => $bidang->id, 'name' => $bidang->name];
        }
    }

    public function saveBidangs()
    {
        // tidak mengimplementasikan validasi, karena jika input kosong berarti data tersebut tidak akan diubah
        // ambil input/request dari position yang berisi
        $bidangs = array_filter($this->bidangs, function ($a) {
            return trim($a['name']) !== "";
        });

        $affected = 0;
        foreach ($bidangs as $bidang) {
            $affected += Bidang::find($bidang['id'])->update(['name' => $bidang['name']]);
        }

        $message = $affected === 0 ?
            "Tidak ada data bidang yang diubah." :
            "Ada $affected data bidang yang berhasil diedit.";

        return redirect()->route('bidangs.index')->with('success', $message);
    }

    public function render()
    {
        return view('livewire.bidang-edit-form');
    }
}
