<?php

namespace App\Http\Livewire;

use App\Models\Nilai;
use App\Models\Peserta;
use Livewire\Component;
use App\Models\Pembimbing;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\Types\This;
use Illuminate\Database\Eloquent\Collection;

class NilaiCreateForm extends Component
{
    public $nilai;
    public $peserta_id;
    public $peserta_name;
    public $pembimbing_id;
    public $pembimbing_name;


    public function mount()
    {
        $peserta_id = request()->query('peserta_id');
        $this->peserta_id = $peserta_id;
        $this->peserta_name = Peserta::find($peserta_id)?->name ?? '-';

        $pembimbing = Pembimbing::where('user_id', auth()->id())->first();
        $this->pembimbing_id = $pembimbing?->id;
        $this->pembimbing_name = $pembimbing?->name ?? '-';

        // $this->peserta_name = Peserta::find(request()->query('peserta_id'))?->name ?? '-';
        // $this->pembimbing_name = Pembimbing::where('user_id', auth()->id())->first()->name;

        $this->nilai = [
            'peserta_id' => $this->peserta_id,
            'pembimbing_id' => $this->pembimbing_id,
            'sikap' => '',
            'kedisiplinan' => '',
            'kesungguhan' => '',
            'mandiri' => '',
            'kerjasama' => '',
            'teliti' => '',
            'pendapat' => '',
            'hal_baru' => '',
            'inisiatif' => '',
            'kepuasan' => '',
            'catatan' => '',
        ];
    }

    public function saveNilai()
        {
            $this->validate([
                'nilai.sikap' => 'required|numeric|min:0|max:100',
                'nilai.kedisiplinan' => 'required|numeric|min:0|max:100',
                'nilai.kesungguhan' => 'required|numeric|min:0|max:100',
                'nilai.mandiri' => 'required|numeric|min:0|max:100',
                'nilai.kerjasama' => 'required|numeric|min:0|max:100',
                'nilai.teliti' => 'required|numeric|min:0|max:100',
                'nilai.pendapat' => 'required|numeric|min:0|max:100',
                'nilai.hal_baru' => 'required|numeric|min:0|max:100',
                'nilai.inisiatif' => 'required|numeric|min:0|max:100',
                'nilai.kepuasan' => 'required|numeric|min:0|max:100',
                'nilai.catatan' => 'nullable|string|max:255',
            ]);

            $this->nilai['pembimbing_id'] = $this->pembimbing_id;
            $this->nilai['peserta_id'] = $this->peserta_id;

            Nilai::updateOrCreate(
                ['peserta_id' => $this->peserta_id, 
                 'pembimbing_id' => $this->pembimbing_id],
                $this->nilai
            );

            session()->flash('success', 'Nilai Peserta berhasil disimpan.');
            return redirect()->route('nilai.index');
        }
        

}
