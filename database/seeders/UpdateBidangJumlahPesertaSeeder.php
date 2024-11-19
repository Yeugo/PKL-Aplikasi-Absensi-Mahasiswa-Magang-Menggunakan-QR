<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bidang;

class UpdateBidangJumlahPesertaSeeder extends Seeder
{
    public function run()
    {
        Bidang::all()->each(function ($bidang) {
            $bidang->jumlah_peserta = $bidang->users()->count(); // Sesuaikan nama relasi
            $bidang->save();
        });
    }
}
