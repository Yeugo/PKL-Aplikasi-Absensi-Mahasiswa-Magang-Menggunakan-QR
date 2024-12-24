<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Storage;
use App\Models\Bidang;
use App\Models\Peserta;
use Livewire\Component;
use App\Models\Pembimbing;
use Livewire\WithFileUploads;
use App\Http\Traits\useUniqueValidation;
use Illuminate\Database\Eloquent\Collection;

class PesertaEditForm extends Component
{
    use useUniqueValidation;
    use WithFileUploads;

    public $peserta;
    public Collection $pembimbings;
    public Collection $bidangs;

    public function mount(Collection $peserta)
    {
        $this-> peserta = [];

        foreach ($peserta as $item) {
            $this->peserta[] = [
                'id' => $item->id,
                'name' => $item->name,
                'npm' => $item->npm,
                'phone' => $item->phone,
                'original_phone' => $item->phone,
                'univ' => $item->univ,
                'alamat' => $item->alamat,
                'peserta_bidang_id' => $item->peserta_bidang_id,
                'pembimbing_id' => $item->pembimbing_id,
                'foto' => null,
                'current_foto' => $item->foto,
            ];
        }
        
        $this->bidangs =Bidang::all();
        $this->pembimbings = Pembimbing::all();
    }

    public function savePeserta()
    {
        $bidangIdRuleIn = join(',', $this->bidangs->pluck('id')->toArray());
        $pembimbingIdRuleIn = join(',', $this->pembimbings->pluck('id')->toArray());

        $this->validate([
            'peserta.*.name' => 'required',
            'peserta.*.npm' => 'required',
            'peserta.*.phone' => 'required',
            'peserta.*.univ' => 'required',
            'peserta.*.alamat' => 'required',
            'peserta.*.peserta_bidang_id' => 'required|in:' . $bidangIdRuleIn,
            'peserta.*.pembimbing_id' => 'required|in:' . $pembimbingIdRuleIn,
            'peserta.*.foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if (!$this->isUniqueOnLocal('phone', $this->peserta)) {
            $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
            return session()->flash('failed', 'Pastikan input No. Telp tidak mangandung nilai yang sama dengan input lainnya.');
        }

        // alasan menggunakan create alih2 mengunakan ::insert adalah karena tidak looping untuk menambahkan created_at dan updated_at
        $affected = 0;
        // foreach ($this->peserta as $peserta) {
        //     // cek unique validasi
        //     $pesertaBeforeUpdated = Peserta::find($peserta['id']);

        //     if (!$this->isUniqueOnDatabase($pesertaBeforeUpdated, $peserta, 'phone', Peserta::class)) {
        //         $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
        //         return session()->flash('failed', "No. Telp dari data peserta {$peserta['id']} sudah terdaftar. Silahkan masukan No. Telp yang berbeda!");
        //     }

        //     $affected += $pesertaBeforeUpdated->update([
        //         'name' => $peserta['name'],
        //         'npm' => $peserta['npm'],
        //         'phone' => $peserta['phone'],
        //         'univ' => $peserta['univ'],
        //         'alamat' => $peserta['alamat'],
        //         'peserta_bidang_id' => $peserta['peserta_bidang_id'],
        //         'pembimbing_id' => $peserta['pembimbing_id'],
        //     ]);
        // }

        foreach ($this->peserta as $peserta) {
            $pesertaBeforeUpdated = Peserta::find($peserta['id']);

            if (!$this->isUniqueOnDatabase($pesertaBeforeUpdated, $peserta, 'phone', Peserta::class)) {
                $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
                return session()->flash('failed', "No. Telp dari data peserta {$peserta['id']} sudah terdaftar. Silahkan masukan No. Telp yang berbeda!");
            }

            // Handle gambar baru
            if (isset($peserta['foto']) && $peserta['foto'] instanceof \Illuminate\Http\UploadedFile) {
                // Hapus gambar lama jika ada
                if ($peserta['current_foto']) {
                    Storage::disk('public')->delete($peserta['current_foto']);
                }

                // Simpan gambar baru
                $filename = $peserta['npm'] . '.' . $peserta['foto']->getClientOriginalExtension();
                $path = $peserta['foto']->storeAs('peserta_photos', $filename, 'public');
                $peserta['foto'] = $path;
            } else {
                // Tetap gunakan gambar lama
                $peserta['foto'] = $peserta['current_foto'];
            }

            $affected += $pesertaBeforeUpdated->update([
                'name' => $peserta['name'],
                'npm' => $peserta['npm'],
                'phone' => $peserta['phone'],
                'univ' => $peserta['univ'],
                'alamat' => $peserta['alamat'],
                'peserta_bidang_id' => $peserta['peserta_bidang_id'],
                'pembimbing_id' => $peserta['pembimbing_id'],
                'foto' => $peserta['foto'],
            ]);
        }



        $message = $affected === 0 ?
            "Tidak ada data peserta yang diubah." :
            "Ada $affected data peserta yang berhasil diedit.";

        return redirect()->route('peserta.index')->with('success', $message);
    }


    public function render()
    {
        return view('livewire.peserta-edit-form');
    }
}
