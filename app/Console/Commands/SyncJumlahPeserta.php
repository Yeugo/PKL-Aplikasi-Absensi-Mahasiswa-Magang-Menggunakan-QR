<?php

namespace App\Console\Commands;

use App\Models\Bidang;
use App\Models\Peserta;
use Illuminate\Console\Command;

class SyncJumlahPeserta extends Command
{
    // Nama dan deskripsi command
    protected $signature = 'sync:jumlah-peserta';
    protected $description = 'Sinkronisasi jumlah peserta ke kolom jumlah_peserta di tabel bidangs';

    // Logika command
    public function handle()
    {
        // Ambil semua bidang
        $bidangs = Bidang::all();

        foreach ($bidangs as $bidang) {
            // Hitung jumlah peserta berdasarkan bidang_id
            $jumlahPeserta = Peserta::where('peserta_bidang_id', $bidang->id)->count();
            
            // Update jumlah_peserta di tabel bidangs
            $bidang->update(['jumlah_peserta' => $jumlahPeserta]);

            // Menampilkan pesan sukses
            $this->info("Bidang {$bidang->id} telah disinkronkan dengan jumlah peserta {$jumlahPeserta}");
        }

        $this->info('Sinkronisasi jumlah peserta selesai.');
    }
}
