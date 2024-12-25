<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Http\Traits\useUniqueValidation;
use App\Models\Kegiatan;
use Illuminate\Database\Eloquent\Collection;

class KegiatanEditForm extends Component
{
    use useUniqueValidation;

    public $kegiatan;

    public function mount(Collection $kegiatan)
    {
        $this-> kegiatan = [];

        foreach ($kegiatan as $item) {
            $this->kegiatan[] = [
                'id' => $item->id,
                'judul' => $item->judul,
                'deskripsi' => $item->deskripsi,
                'tgl_kegiatan' => $item->tgl_kegiatan,
                'waktu_mulai' => $item->waktu_mulai,
                'waktu_selesai' => $item->waktu_selesai,
                'jenis_kegiatan' => $item->jenis_kegiatan,
            ];
        }
    }

    public function saveKegiatan()
    {

        $this->validate([
            'kegiatan.*.judul' => 'required',
            'kegiatan.*.deskripsi' => 'required',
            'kegiatan.*.tgl_kegiatan' => 'required|date',
            'kegiatan.*.waktu_mulai' => 'required|',
            'kegiatan.*.waktu_selesai' => 'required|after:kegiatan.*.waktu_mulai',
        ]);

        $affected = 0;

        foreach ($this->kegiatan as $kegiatan) {
            $kegiatanBeforeUpdated = Kegiatan::find($kegiatan['id']);


            $affected += $kegiatanBeforeUpdated->update([
                'judul' => $kegiatan['judul'],
                'deskripsi' => $kegiatan['deskripsi'],
                'tgl_kegiatan' => $kegiatan['tgl_kegiatan'],
                'waktu_mulai' => $kegiatan['waktu_mulai'],
                'waktu_selesai' => $kegiatan['waktu_selesai'],
            ]);
        }

        $message = $affected === 0 ?
            "Tidak ada data kegiatan yang diubah." :
            "Ada $affected data kegiatan yang berhasil diedit.";

        return redirect()->route('kegiatan.index')->with('success', $message);
    }


    public function render()
    {
        return view('livewire.kegiatan-edit-form');
    }
}
