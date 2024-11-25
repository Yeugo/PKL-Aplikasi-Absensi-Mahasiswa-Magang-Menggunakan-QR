<?php

namespace App\Http\Livewire;

use App\Models\Absensi;
use Illuminate\Support\Str;

class AbsensiEditForm extends AbsensiAbstract
{
    public $initialCode;

    public function mount()
    {
        parent::mount();
        // format time
        $this->absensi['start_time'] = substr($this->absensi['start_time'], 0, -3);
        $this->absensi['batas_start_time'] = substr($this->absensi['batas_start_time'], 0, -3);
        $this->absensi['end_time'] = substr($this->absensi['end_time'], 0, -3);
        $this->absensi['batas_end_time'] = substr($this->absensi['batas_end_time'], 0, -3);

        $this->initialCode = $this->absensi['code']; // ini untuk pengecekan/mengatasi update code
        $this->absensi['code'] = $this->initialCode ? true : false; // untuk kondisi apakah input code checked
    }

    public function save()
    {
        // // filter value before validate (ambil yang hanya checked)
        // $this->position_ids = array_filter($this->position_ids, function ($id) {
        //     return is_numeric($id);
        // });
        // $position_ids = array_values($this->position_ids);

        $this->validate();

        $absensi = [];
        if (!$this->absensi->code) {
            $this->absensi->code = null;
            $absensi = $this->absensi->toArray();
        } else {
            $absensi = $this->absensi->toArray();
            // generate code baru jika sebelumnya absensi menggunakan button (atau diubah)
            if (!$this->initialCode) {
                $absensi['code'] = Str::random();
            } else {
                $absensi['code'] = $this->initialCode;
            }
        }

        $this->absensi->update($absensi);
        // $this->attendance->positions()->sync($position_ids);

        redirect()->route('absensi.index')->with('success', "Data absensi berhasil diubah.");
    }

    public function render()
    {
        return view('livewire.absensi-edit-form');
    }
}
