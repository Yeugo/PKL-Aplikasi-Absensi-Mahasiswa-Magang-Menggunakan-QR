<?php

namespace App\Http\Livewire;

use App\Models\Bidang;
use Livewire\Component;
use App\Http\Traits\useUniqueValidation;
use App\Models\Pembimbing;
use App\Models\Peserta;
use Illuminate\Database\Eloquent\Collection;

class PesertaEditForm extends Component
{
    use useUniqueValidation;

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
                'bidang_id' => $item->bidang_id,
                'pembimbing_id' => $item->pembimbing_id,
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
            'peserta.*.bidang_id' => 'required|in:' . $bidangIdRuleIn,
            'peserta.*.pembimbing_id' => 'required|in:' . $pembimbingIdRuleIn,
        ]);

        if (!$this->isUniqueOnLocal('phone', $this->peserta)) {
            $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
            return session()->flash('failed', 'Pastikan input No. Telp tidak mangandung nilai yang sama dengan input lainnya.');
        }

        // alasan menggunakan create alih2 mengunakan ::insert adalah karena tidak looping untuk menambahkan created_at dan updated_at
        $affected = 0;
        foreach ($this->peserta as $peserta) {
            // cek unique validasi
            $pesertaBeforeUpdated = Peserta::find($peserta['id']);

            if (!$this->isUniqueOnDatabase($pesertaBeforeUpdated, $peserta, 'phone', Peserta::class)) {
                $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
                return session()->flash('failed', "No. Telp dari data peserta {$peserta['id']} sudah terdaftar. Silahkan masukan No. Telp yang berbeda!");
            }

            $affected += $pesertaBeforeUpdated->update([
                'name' => $peserta['name'],
                'npm' => $peserta['npm'],
                'phone' => $peserta['phone'],
                'univ' => $peserta['univ'],
                'alamat' => $peserta['alamat'],
                'bidang_id' => $peserta['bidang_id'],
                'pembimbing_id' => $peserta['pembimbing_id'],
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
