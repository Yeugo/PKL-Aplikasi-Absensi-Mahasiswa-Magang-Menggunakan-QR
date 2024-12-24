<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Peserta;

class PesertaObserver
{
    // Ketika Peserta dibuat
    public function created(Peserta $peserta)
    {
        // Menyinkronkan jumlah peserta pada bidang terkait
        $this->syncJumlahPeserta($peserta->bidang);
    }

    // Ketika Peserta dihapus
    public function deleted(Peserta $peserta)
    {
        // Menyinkronkan jumlah peserta pada bidang terkait
        $this->syncJumlahPeserta($peserta->bidang);

        if ($peserta->user_id) {
            User::where('id', $peserta->user_id)->delete();
        }
    }

    // Fungsi untuk menyinkronkan jumlah peserta
    private function syncJumlahPeserta($bidang)
    {
        if ($bidang) {
            // Hitung jumlah peserta di bidang ini
            $jumlahPeserta = $bidang->peserta()->count();

            // Update kolom jumlah_peserta
            $bidang->update(['jumlah_peserta' => $jumlahPeserta]);
        }
    }
}
