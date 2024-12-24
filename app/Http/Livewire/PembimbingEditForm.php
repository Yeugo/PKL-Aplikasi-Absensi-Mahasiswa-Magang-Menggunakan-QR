<?php

namespace App\Http\Livewire;

use App\Models\Bidang;
use Livewire\Component;
use App\Models\Pembimbing;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\useUniqueValidation;
use Illuminate\Database\Eloquent\Collection;

class PembimbingEditForm extends Component
{
    use useUniqueValidation;
    use WithFileUploads;

    public $pembimbing;
    public Collection $bidangs;

    public function mount(Collection $pembimbing)
    {
        $this-> pembimbing = [];

        foreach ($pembimbing as $item) {
            $this->pembimbing[] = [
                'id' => $item->id,
                'name' => $item->name,
                'nip' => $item->nip,
                'phone' => $item->phone,
                'original_phone' => $item->phone,
                'alamat' => $item->alamat,
                'bidang_id' => $item->bidang_id,
                'foto' => null,
                'current_foto' => $item->foto,
            ];
        }
        
        $this->bidangs =Bidang::all();
    }

    public function savePembimbing()
    {
        $bidangIdRuleIn = join(',', $this->bidangs->pluck('id')->toArray());

        $this->validate([
            'pembimbing.*.name' => 'required',
            'pembimbing.*.nip' => 'required',
            'pembimbing.*.phone' => 'required',
            'pembimbing.*.alamat' => 'required',
            'pembimbing.*.bidang_id' => 'required|in:' . $bidangIdRuleIn,
            'pembimbing.*.foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if (!$this->isUniqueOnLocal('phone', $this->pembimbing)) {
            $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
            return session()->flash('failed', 'Pastikan input No. Telp tidak mangandung nilai yang sama dengan input lainnya.');
        }

        // alasan menggunakan create alih2 mengunakan ::insert adalah karena tidak looping untuk menambahkan created_at dan updated_at
        $affected = 0;

        foreach ($this->pembimbing as $pembimbing) {
            $pembimbingBeforeUpdated = Pembimbing::find($pembimbing['id']);

            if (!$this->isUniqueOnDatabase($pembimbingBeforeUpdated, $pembimbing, 'phone', Pembimbing::class)) {
                $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
                return session()->flash('failed', "No. Telp dari data pembimbing {$pembimbing['id']} sudah terdaftar. Silahkan masukan No. Telp yang berbeda!");
            }

            // Handle gambar baru
            if (isset($pembimbing['foto']) && $pembimbing['foto'] instanceof \Illuminate\Http\UploadedFile) {
                // Hapus gambar lama jika ada
                if ($pembimbing['current_foto']) {
                    Storage::disk('public')->delete($pembimbing['current_foto']);
                }

                // Simpan gambar baru
                $filename = $pembimbing['nip'] . '.' . $pembimbing['foto']->getClientOriginalExtension();
                $path = $pembimbing['foto']->storeAs('pembimbing_photos', $filename, 'public');
                $pembimbing['foto'] = $path;
            } else {
                // Tetap gunakan gambar lama
                $pembimbing['foto'] = $pembimbing['current_foto'];
            }

            $affected += $pembimbingBeforeUpdated->update([
                'name' => $pembimbing['name'],
                'nip' => $pembimbing['nip'],
                'phone' => $pembimbing['phone'],
                'alamat' => $pembimbing['alamat'],
                'bidang_id' => $pembimbing['bidang_id'],
                'foto' => $pembimbing['foto'],
            ]);
        }



        $message = $affected === 0 ?
            "Tidak ada data pembimbing yang diubah." :
            "Ada $affected data pembimbing yang berhasil diedit.";

        return redirect()->route('pembimbing.index')->with('success', $message);
    }

    public function render()
    {
        return view('livewire.pembimbing-edit-form');
    }
}
