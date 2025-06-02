<?php

namespace App\Http\Livewire;

use App\Models\Bidang;
use App\Models\Peserta;
use Livewire\Component;
use App\Models\Pembimbing;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\useUniqueValidation;
use Illuminate\Database\Eloquent\Collection;

class StatusMagangEditForm extends Component
{
    use useUniqueValidation;
    use WithFileUploads;

    public $peserta;
    public Collection $bidangs;

    public function mount(Collection $peserta)
    {
        $this->peserta = [];

        foreach ($peserta as $item) {
            $this->peserta[] = [
                'id' => $item->id,
                'name' => $item->name,
                'npm' => $item->npm,
                'univ' => $item->univ,
                'tgl_mulai_magang' => $item->tgl_mulai_magang,
                'tgl_selesai_magang_rencana' => $item->tgl_selesai_magang_rencana,
                'status_penyelesaian' => $item->status_penyelesaian,
                'tanggal_penyelesaian_aktual' => $item->tanggal_penyelesaian_aktual,
            ];
        }
    }

    public function savePeserta()
    {
        $this->validate([
            'peserta.*.tgl_mulai_magang' => 'required|date',
            'peserta.*.tgl_selesai_magang_rencana' => 'required|date|after_or_equal:peserta.*.tgl_mulai_magang',
            'peserta.*.status_penyelesaian' => 'required|string|in:Belum Dimulai,Aktif,Selesai,Diberhentikan,Mengundurkan Diri',
            'peserta.*.tanggal_penyelesaian_aktual' => 'nullable|date|required_if:peserta.*.status_penyelesaian,Selesai',
        ]);

        // alasan menggunakan create alih2 mengunakan ::insert adalah karena tidak looping untuk menambahkan created_at dan updated_at
        $affected = 0;

        foreach ($this->peserta as $peserta) {
            $pesertaBeforeUpdated = Peserta::find($peserta['id']);

            $affected += $pesertaBeforeUpdated->update([
                'tgl_mulai_magang' => $peserta['tgl_mulai_magang'],
                'tgl_selesai_magang_rencana' => $peserta['tgl_selesai_magang_rencana'],
                'status_penyelesaian' => $peserta['status_penyelesaian'],
                'tanggal_penyelesaian_aktual' => $peserta['tanggal_penyelesaian_aktual'],
            ]);
        }



        $message = $affected === 0 ?
            "Tidak ada data status magang yang diubah." :
            "Ada $affected data status magang yang berhasil diedit.";

        return redirect()->route('status_magang.index')->with('success', $message);
    }

    public function render()
    {
        return view('livewire.status-magang-edit-form');
    }
}
