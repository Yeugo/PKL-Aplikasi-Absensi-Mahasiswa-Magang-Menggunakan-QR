<?php

namespace App\Http\Livewire;

use App\Models\Nilai;
use App\Models\Peserta;
use Livewire\Component;
use App\Models\Kegiatan;
use App\Models\Pembimbing;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\Types\This;
use Illuminate\Database\Eloquent\Collection;

class KegiatanCreateForm extends Component
{
    public $kegiatan;
    public $peserta_id;
    public $peserta_name;
    public $pembimbing_id;
    public $pembimbing_name;


    public function mount()
    {
        $peserta = Peserta::where('user_id', auth()->id())->first();
        $this->peserta_id = $peserta?->id;

        $this->kegiatan = [
            'judul' => '',
            'deskripsi' => '',
            'tgl_kegiatan' => '',
            'waktu_mulai' => '',
            'waktu_selesai' => '',
            'peserta_id' => $this->peserta_id,
        ];
    }

    public function saveKegiatan()
        {
            $this->validate([
                'kegiatan.judul' => 'required|string|max:255',
                'kegiatan.deskripsi' => 'required|string|max:255',
                'kegiatan.tgl_kegiatan' => 'required|date',
                'kegiatan.waktu_mulai' => 'required|date_format:H:i',
                'kegiatan.waktu_selesai' => 'required|date_format:H:i|after:kegiatan.waktu_mulai',
            ]);

            Kegiatan::updateOrCreate(
                $this->kegiatan
            );

            session()->flash('success', 'Catatan Kegiatan berhasil disimpan.');
            return redirect()->route('kegiatan.index');
        }
        

}
