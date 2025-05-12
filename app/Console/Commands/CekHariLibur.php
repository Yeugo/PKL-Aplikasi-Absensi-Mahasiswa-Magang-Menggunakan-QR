<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Absensi;
use App\Models\Peserta;
use App\Models\Kehadiran;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CekHariLibur extends Command
{
    protected $signature = 'cek:harilibur';
    protected $description = 'Cek apakah hari ini libur dan isi absensi otomatis jika ya';

    public function handle()
    {
        $tanggal = now()->toDateString();
        $apiKey = '1ea87bd53f26ca5e'; // Ganti dengan API key asli kamu
        $url = "https://kalenderindonesia.com/api/$apiKey/libur/masehi/$tanggal";

        // $response = Http::get($url);
        $response = Http::withoutVerifying()->get($url);

        if ($response->successful()) {
            $data = $response->json();

            if (!empty($data['data']) && count($data['data']) > 0) {
                $deskripsi = $data['data'][0]['deskripsi'] ?? 'Hari Libur';
                $this->info("Hari ini libur: $deskripsi");

                // $users = User::where('role', 'peserta')->get();

                // foreach ($users as $user) {
                //     Kehadiran::firstOrCreate([
                //         'user_id' => $user->id,
                //         'tanggal' => $tanggal,
                //     ], [
                //         'izin' => 2, // Khusus libur
                //     ]);
                // }

               
                $pesertas = Peserta::with('user')->get(); // Eager load relasi user
                $absensis = Absensi::all(); // Ambil semua data absensi

                foreach ($pesertas as $peserta) {
                    foreach ($absensis as $absensi) {
                        Kehadiran::firstOrCreate([
                            'user_id' => $peserta->user_id, // Ambil user_id dari tabel peserta
                            'absensi_id' => $absensi->id, // Ambil absensi_id dari tabel absensi
                            'tgl_hadir' => $tanggal,
                        ], [
                            'absen_masuk' => now()->toTimeString(),
                            'izin' => 2, // Khusus libur
                        ]);
                    }
                }

                $this->info("Absensi otomatis status LIBUR berhasil dimasukkan.");
            } else {
                $this->info("Hari ini bukan hari libur.");
            }
        } else {
            $this->error("Gagal mengakses API hari libur.");
        }
    }
}
