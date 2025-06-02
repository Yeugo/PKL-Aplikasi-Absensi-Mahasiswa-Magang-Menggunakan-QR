<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class SertifikatController extends Controller
{
    public function indexPeserta()
    {
        return view('sertifikat.indexPeserta', [
            "title" => "Pengajuan Sertifikat Peserta"
        ]);
    }

    public function indexPembimbing()
    {
        return view('sertifikat.indexPembimbing', [
            "title" => "Daftar Pengajuan Sertifikat Peserta"
        ]);
    }

    public function indexAdmin()
    {
        return view('sertifikat.indexAdmin', [
            "title" => "Daftar Pengajuan Sertifikat Peserta"
        ]);
    }

    public function downloadCertificate($peserta_id)
    {
        // 1. Eager load semua relasi yang diperlukan untuk sertifikat
        $peserta = Peserta::with([
            'nilai',        // Jika nilai/kategori nilai ditampilkan di sertifikat
            'pembimbing',   // Untuk nama pembimbing
            'bidang',       // Untuk nama bidang
            'sertifikat'    // Untuk status sertifikat, tanggal persetujuan admin
        ])->findOrFail($peserta_id);

        $certificateRecord = $peserta->sertifikat; // Dapatkan objek sertifikatnya

        // 2. Otorisasi Unduh Sertifikat
        // Hanya izinkan unduh jika status sudah disetujui admin
        // ATAU jika user yang mencoba mengunduh adalah admin (untuk debugging/internal)
        if (!$certificateRecord || $certificateRecord->status !== 'disetujui_oleh_admin') {
            // Asumsi ada fungsi isAdmin() di model User Anda
            if (!auth()->check() || !auth()->user()->isAdmin()) {
                abort(403, 'Sertifikat belum disetujui atau Anda tidak memiliki akses untuk mengunduhnya.');
            }
        }

        // 3. Ambil data nilai dan hitung rata-rata/kategori (jika relevan untuk sertifikat)
        $nilai = $peserta->nilai;
        $rata2 = 0;
        $kategori = '';
        if ($nilai) {
            $listNilai = [
                'Sikap' => $nilai->sikap,
                'Kedisiplinan' => $nilai->kedisiplinan,
                'Kesungguhan' => $nilai->kesungguhan,
                'Mandiri' => $nilai->mandiri,
                'Kerjasama' => $nilai->kerjasama,
                'Teliti' => $nilai->teliti,
                'Pendapat' => $nilai->pendapat,
                'Hal Baru' => $nilai->hal_baru,
                'Inisiatif' => $nilai->inisiatif,
                'Kepuasan' => $nilai->kepuasan,
            ];
            $total = array_sum($listNilai);
            $jumlahKriteria = count($listNilai);
            $rata2 = ($jumlahKriteria > 0) ? $total / $jumlahKriteria : 0;

            if ($rata2 >= 85) { $kategori = 'Sangat Baik'; }
            elseif ($rata2 >= 70) { $kategori = 'Baik'; }
            elseif ($rata2 >= 55) { $kategori = 'Cukup'; }
            else { $kategori = 'Kurang'; }
        }

        // 4. Siapkan semua gambar ke format Base64 untuk disisipkan ke PDF
        //    Ini adalah cara paling andal untuk gambar di Dompdf
        // $logoBase64 = $this->getImageBase64(public_path('storage/assets/logobjm.png'));
        // $signaturePembimbingBase64 = $this->getImageBase64(public_path('storage/assets/signatures/pembimbing_signature.png'));
        // $signatureAdminBase64 = $this->getImageBase64(public_path('storage/assets/signatures/admin_signature.png'));
        // $stampBase64 = $this->getImageBase64(public_path('storage/assets/signatures/dinas_stamp.png'));

        $logoBase64 = ''; // Di sini
        $signaturePembimbingBase64 = ''; // Di sini
        $signatureAdminBase64 = ''; // Di sini
        $stampBase64 = ''; // Di sini
        $borderImageBase64 = $this->getImageBase64(public_path('storage/assets/border.png'));
        // Jika ada background sertifikat:
        // $certificateBackgroundBase64 = $this->getImageBase64(public_path('storage/assets/certificate_bg.png'));

        // 5. Tentukan tanggal terbit sertifikat (biasanya tanggal persetujuan admin)
        $issueDate = $certificateRecord->tgl_disetujui_admin ? $certificateRecord->tgl_disetujui_admin->translatedFormat('d F Y') : Carbon::now('Asia/Makassar')->translatedFormat('d F Y');

        // 6. Muat view Blade sertifikat ke Dompdf dan atur ukuran kertas
        $pdf = Pdf::loadView('exports.SertifikatPdf', compact(
            'peserta',
            'nilai', // Kirim objek nilai jika perlu di sertifikat
            'rata2', // Kirim rata-rata nilai jika perlu
            'kategori', // Kirim kategori nilai jika perlu
            'logoBase64',
            'signaturePembimbingBase64',
            'signatureAdminBase64',
            'stampBase64',
            'borderImageBase64',
            'issueDate'
            // 'certificateBackgroundBase64'
        ))
        ->setPaper('a4', 'landscape'); // Sertifikat umumnya orientasi landscape

        // 7. Unduh file PDF
        return $pdf->download('Sertifikat_Magang_' . Str::slug($peserta->name) . '.pdf');
    }

    // Helper function untuk mengonversi gambar ke Base64 (penting untuk Dompdf)
    private function getImageBase64($path)
    {
        if (file_exists($path)) {
            $imageData = file_get_contents($path);
            $imageType = pathinfo($path, PATHINFO_EXTENSION);
            return 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
        }
        // Jika gambar tidak ditemukan, log peringatan dan kembalikan string kosong
        \Log::warning('Gambar tidak ditemukan saat konversi Base64: ' . $path);
        return '';
    }
}
