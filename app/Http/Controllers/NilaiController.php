<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class NilaiController extends Controller
{
    public function index()
    {
        return view('nilai.index', [
            "title" => "Data Nilai Peserta Magang"
        ]);
    }

    public function create()
    {
        return view('nilai.create', [
            "title" => "Data Nilai Peserta Magang"
        ]);
    }

    public function show($peserta_id)
    {
        $peserta = Peserta::with('nilai','pembimbing')->findOrFail($peserta_id);

        return view('nilai.show', [
            'title' => 'Detail Penilaian Kinerja Peserta Magang',
            'peserta' => $peserta,
            'nilai' => $peserta->nilai,
            'pembimbing' => $peserta->pembimbing,
        ]);
    }

    public function exportPdf($peserta_id)
    {
        // Ambil data peserta dan relasinya (nilai, pembimbing, bidang)
        // Pastikan semua relasi yang digunakan di PDF di-eager load di sini
        $peserta = Peserta::with('nilai', 'pembimbing', 'bidang')->findOrFail($peserta_id);

        // Ambil data nilai
        // Karena nilai adalah relasi one-to-one dari Peserta, bisa diakses langsung dari $peserta->nilai
        $nilai = $peserta->nilai;

        // --- Bagian Gambar Base64 (salin dari Livewire sebelumnya) ---
        $imagePath = public_path('storage/assets/logobjm.png');
        $base64Image = '';

        if (file_exists($imagePath)) {
            $imageData = file_get_contents($imagePath);
            $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
            $base64Image = 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
        } else {
            // \Log::warning('Gambar logo tidak ditemukan untuk PDF: ' . $imagePath);
        }
        // --- Akhir Bagian Gambar Base64 ---

        // Load view PDF dan kirim data yang diperlukan
        // Pastikan nama view ('exports.LaporanPesertaPdf') sesuai dengan file Blade PDF Anda
        $pdf = Pdf::loadView('exports.NilaiPeserta', compact('peserta', 'nilai', 'base64Image'))
                  ->setPaper('a4', 'portrait');

        // Mengunduh PDF
        return $pdf->download('Laporan Penilaian Peserta - ' . ($peserta->name ?? 'Peserta') . '.pdf');
    }

}
