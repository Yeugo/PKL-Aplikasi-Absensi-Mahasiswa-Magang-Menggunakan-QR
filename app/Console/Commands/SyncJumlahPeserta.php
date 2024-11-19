<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bidang;

class SyncJumlahPeserta extends Command
{
    protected $signature = 'sync:jumlah-peserta';
    protected $description = 'Sinkronkan jumlah peserta ke kolom jumlah_peserta di tabel bidangs';

    public function handle()
    {
        $bidangs = Bidang::withCount('users')->get();

        foreach ($bidangs as $bidang) {
            $bidang->update(['jumlah_peserta' => $bidang->users_count]);
        }

        $this->info('Jumlah peserta berhasil disinkronkan.');
    }
}
