<?php

namespace App\Http\Livewire;

use App\Models\Bidang;
use Livewire\Component;

class BidangCreateForm extends Component
{
    public $bidangs;

    public function mount()
    {
        $this->bidangs = [
            ['name' => '']
        ];
    }

    public function addBidangInput(): void
    {
        $this->bidangs[] = ['name' => ''];
    }

    public function removeBidangInput(int $index): void
    {
        unset($this->bidangs[$index]);
        $this->bidangs = array_values($this->bidangs);
    }

    public function saveBidangs()
    {
        // setidaknya input pertama yang hanya required,
        // karena nanti akan difilter apakah input kedua dan input selanjutnya apakah berisi
        $this->validate([
            'bidangs.0.name' => 'required'
        ], ['bidangs.0.name.required' => 'Setidaknya input jabatan pertama wajib diisi.']);

        // ambil input/request dari position yang berisi
        $bidangs = array_filter($this->bidangs, function ($a) {
            return trim($a['name']) !== "";
        });

        // alasan menggunakan create alih2 mengunakan ::insert adalah karena tidak looping untuk menambahkan created_at dan updated_at
        foreach ($bidangs as $bidang) {
            Bidang::create($bidang);
        }

        redirect()->route('bidangs.index')->with('success', 'Data jabatan berhasil ditambahkan.');
    }

    public function render()
    {
        return view('livewire.bidang-create-form');
    }
}