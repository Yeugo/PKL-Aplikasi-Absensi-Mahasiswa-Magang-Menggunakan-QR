<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Peserta;
use App\Models\Sertifikat; // Pastikan ini diimpor (Model Sertifikat)
use Carbon\Carbon;
use Illuminate\Support\Str; // Untuk Str::title() dan Str::contains() di PHP

class SertifikatPembimbing extends Component
{
    public $pesertaList;
    public $showRejectModal = false;
    public $pesertaToRejectId;
    public $rejectionReasonInput;

    // Listener untuk merefresh daftar jika ada event dari luar (misal setelah aksi)
    protected $listeners = ['refreshApprovals' => '$refresh'];

    public function mount()
    {
        // Panggil metode untuk memuat daftar peserta saat komponen pertama kali dimuat
        $this->loadPesertaApprovals();
    }

    // Metode untuk memuat daftar peserta dan memastikan record sertifikat ada
    public function loadPesertaApprovals()
    {
        $pembimbingId = auth()->user()->pembimbing->id;

        // Ambil semua peserta yang dibimbing oleh pembimbing ini
        // dan pastikan mereka memiliki record sertifikat yang eager loaded
        $pesertaQuery = Peserta::with(['sertifikat', 'nilai', 'bidang', 'pembimbing'])
                                ->where('pembimbing_id', $pembimbingId);

        // --- Perubahan Utama Ada di Sini ---
        // Kita hanya akan mengambil peserta yang memiliki status 'submitted'
        $pesertaQuery->whereHas('sertifikat', function ($query) {
            $query->where('status', 'submitted');
        });
        // --- Akhir Perubahan Utama ---

        $this->pesertaList = $pesertaQuery->get();
        
    }

    // Aksi untuk menyetujui sertifikat oleh pembimbing
    public function approveCertificate($pesertaId)
    {
        $peserta = Peserta::with('sertifikat')->findOrFail($pesertaId);
        $sertifikat = $peserta->sertifikat;

        // Validasi status pengajuan sebelum menyetujui
        if (!$sertifikat || $sertifikat->status !== 'submitted') {
            session()->flash('error', 'Sertifikat tidak bisa disetujui pada status ini.');
            return;
        }

        // Otorisasi: Pastikan pembimbing yang menyetujui adalah pembimbing yang bersangkutan
        if ($peserta->pembimbing_id !== auth()->user()->pembimbing->id) {
            session()->flash('error', 'Anda tidak berhak menyetujui sertifikat peserta ini.');
            return;
        }

        // Perbarui status sertifikat
        $sertifikat->update([
            'status' => 'disetujui_oleh_pembimbing',
            'tgl_disetujui_pembimbing' => Carbon::now(),
            'alasan_ditolak' => null, // Bersihkan alasan penolakan jika sebelumnya ditolak
        ]);

        session()->flash('message', 'Pengajuan sertifikat berhasil diteruskan ke Admin untuk persetujuan akhir.');
        $this->loadPesertaApprovals(); // Refresh daftar setelah aksi
        // Opsional: Kirim notifikasi ke Admin (misal dengan event/email)
    }

    // Aksi untuk membuka modal penolakan
    public function openRejectModal($pesertaId)
    {
        $this->showRejectModal = true;
        $this->pesertaToRejectId = $pesertaId;
        $this->rejectionReasonInput = ''; // Reset input
    }

    // Aksi untuk menolak sertifikat
    public function rejectCertificate()
    {
        $peserta = Peserta::with('sertifikat')->findOrFail($this->pesertaToRejectId);
        $sertifikat = $peserta->sertifikat;

        // Validasi status pengajuan sebelum menolak
        // Bisa ditolak jika statusnya 'submitted' atau sudah pernah 'ditolak_oleh_pembimbing' (ajukan ulang)
        if (!$sertifikat || !in_array($sertifikat->status, ['submitted', 'ditolak_oleh_pembimbing'])) {
            session()->flash('error', 'Sertifikat tidak bisa ditolak pada status ini.');
            $this->closeRejectModal();
            return;
        }

        // Otorisasi: Pastikan pembimbing yang menolak adalah pembimbing yang bersangkutan
        if ($peserta->pembimbing_id !== auth()->user()->pembimbing->id) {
            session()->flash('error', 'Anda tidak berhak menolak sertifikat peserta ini.');
            $this->closeRejectModal();
            return;
        }

        // Perbarui status sertifikat
        $sertifikat->update([
            'status' => 'ditolak_oleh_pembimbing',
            'alasan_ditolak' => $this->rejectionReasonInput,
            'tgl_disetujui_pembimbing' => null, // Reset tanggal persetujuan jika ditolak
        ]);

        session()->flash('message', 'Pengajuan sertifikat berhasil ditolak.');
        $this->closeRejectModal();
        $this->loadPesertaApprovals(); // Refresh daftar setelah aksi
    }

    // Aksi untuk menutup modal penolakan
    public function closeRejectModal()
    {
        $this->showRejectModal = false;
        $this->pesertaToRejectId = null;
        $this->rejectionReasonInput = '';
    }

    public function render()
    {
        return view('livewire.sertifikat-pembimbing');
    }
}