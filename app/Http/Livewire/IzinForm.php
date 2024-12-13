<?php

namespace App\Http\Livewire;

use App\Models\Izin;
use Livewire\Component;

class IzinForm extends Component
{
    public $izin;
    public $absensiId;

    protected $rules = [
        'izin.title' => 'required|string|min:6',
        'izin.description' => 'required|string|max:500',
    ];

    public function save()
    {
        $this->validate();

        Izin::create([
            "user_id" => auth()->user()->id,
            "absensi_id" => $this->absensiId,
            "title" => $this->izin['title'],
            "description" => $this->izin['description'],
            "tgl_izin" => now()->toDateString()
        ]);

        return redirect()->route('home.show', $this->absensiId)->with('success', 'Permintaan izin sedang diproses. Silahkan tunggu...');
    }

    public function render()
    {
        return view('livewire.izin-form');
    }
}
