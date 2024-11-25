<?php

namespace App\Http\Livewire;

use App\Models\Absensi;
use \Illuminate\Support\Str;

class AbsensiCreateForm extends AbsensiAbstract
{
    public function save()
    {
        // // filter value before validate
        // $this->position_ids = array_filter($this->position_ids, function ($id) {
        //     return is_numeric($id);
        // });

        // $position_ids = array_values($this->position_ids);

        $this->validate();

        if (array_key_exists("code", $this->absensi) && $this->absensi['code']) // jika menggunakan qrcode
            $this->absensi['code'] = Str::random();

        $absensi = Absensi::create($this->absensi);
        // $attendance->positions()->attach($position_ids);

        redirect()->route('absensi.index')->with('success', "Data absensi berhasil ditambahkan.");
    }

    public function render()
    {
        return view('livewire.absensi-create-form');
    }
}
