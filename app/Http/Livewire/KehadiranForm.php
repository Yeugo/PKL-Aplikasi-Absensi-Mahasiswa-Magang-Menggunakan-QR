<?php

namespace App\Http\Livewire;

use App\Models\Absensi;
use App\Models\Kehadiran;
use Livewire\Component;
use PHPUnit\Framework\MockObject\Stub\ReturnReference;

class KehadiranForm extends Component
{
    public Absensi $absensi;
    public $holiday;
    public $data;

    public function mount(Absensi $absensi)
    {
        $this->absensi = $absensi;
    }

    // NOTED: setiap method send presence agar lebih aman seharusnya menggunakan if statement seperti diviewnya

    public function sendEnterPresence()
    {
        if ($this->absensi->data->is_start && !$this->absensi->data->is_using_qrcode) { // sama (harus) dengan view
            Kehadiran::create([
                "user_id" => auth()->user()->id,
                "absensi_id" => $this->absensi->id,
                "tgl_hadir" => now()->toDateString(),
                "absen_masuk" => now()->toTimeString(),
                "absen_keluar" => null
            ]);

            // untuk refresh if statement
            $this->data['is_has_enter_today'] = true;
            $this->data['is_not_out_yet'] = true;

            return $this->dispatchBrowserEvent('showToast', ['success' => true, 'message' => "Kehadiran atas nama '" . auth()->user()->peserta->name . "' berhasil dikirim."]);
        }
    }

    public function sendOutPresence()
    {
        // jika absensi sudah jam pulang (is_end) dan tidak menggunakan qrcode (kebalikan)
        if (!$this->absensi->data->is_end && $this->absensi->data->is_using_qrcode) // sama (harus) dengan view
            return false;

        $kehadiran = Kehadiran::query()
            ->where('user_id', auth()->user()->id)
            ->where('absensi_id', $this->absensi->id)
            ->where('tgl_hadir', now()->toDateString())
            ->where('absen_keluar', null)
            ->first();

        if (!$kehadiran) // hanya untuk sekedar keamanan (kemungkinan)
            return $this->dispatchBrowserEvent('showToast', ['success' => false, 'message' => "Terjadi masalah pada saat melakukan absensi."]);

        // untuk refresh if statement
        $this->data['is_not_out_yet'] = false;
        $kehadiran->update(['presence_out_time' => now()->toTimeString()]);
        return $this->dispatchBrowserEvent('showToast', ['success' => true, 'message' => "Atas nama '" . auth()->user()->peserta->name . "' berhasil melakukan absensi pulang."]);
    }

    public function render()
    {
        return view('livewire.kehadiran-form');
    }
}
