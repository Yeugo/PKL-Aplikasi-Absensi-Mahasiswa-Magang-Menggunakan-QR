<?php

namespace App\Http\Livewire;

use App\Models\Peserta;
use Livewire\Component;
use Illuminate\Support\Carbon;

class SertifikatAdmin extends Component
{
    public $pendingApprovals; // Properti untuk menampung daftar pengajuan menunggu admin
    public $showRejectModal = false;
    public $pesertaToRejectId; // ID peserta yang akan ditolak
    public $rejectionReasonInput; // Input alasan penolakan

    protected $listeners = ['refreshAdminApprovals' => '$refresh']; // Listener untuk refresh

    public function mount()
    {
        $this->loadPendingApprovals(); // Muat daftar saat komponen diinisialisasi
    }

    // Metode untuk memuat daftar pengajuan yang menunggu persetujuan admin
    public function loadPendingApprovals()
    {
        // Otorisasi: Pastikan hanya admin yang bisa mengakses ini
        if (!auth()->check() || !auth()->user()->isAdmin()) { // Asumsi ada fungsi isAdmin() di model User
            abort(403, 'Akses ditolak. Anda bukan Admin.');
        }

        // Ambil semua peserta yang status sertifikatnya 'disetujui_oleh_pembimbing'
        // Eager load relasi yang dibutuhkan untuk tampilan tabel
        $this->pendingApprovals = Peserta::with(['sertifikat', 'pembimbing', 'bidang'])
                                        ->whereHas('sertifikat', function ($query) {
                                            $query->where('status', 'disetujui_oleh_pembimbing');
                                        })
                                        ->get();
    }

    // Aksi untuk menyetujui sertifikat oleh Admin
    public function approveCertificate($pesertaId)
    {
        // Otorisasi Admin
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        $peserta = Peserta::with('sertifikat')->findOrFail($pesertaId);
        $sertifikat = $peserta->sertifikat;

        // Validasi status pengajuan sebelum menyetujui
        if (!$sertifikat || $sertifikat->status !== 'disetujui_oleh_pembimbing') {
            session()->flash('error', 'Sertifikat tidak bisa disetujui pada status ini.');
            return;
        }

        // Perbarui status sertifikat ke 'disetujui_oleh_admin'
        $sertifikat->update([
            'status' => 'disetujui_oleh_admin',
            'tgl_disetujui_admin' => Carbon::now(),
            'alasan_ditolak' => null, // Bersihkan alasan penolakan
        ]);

        // session()->flash('message', 'Sertifikat berhasil disetujui dan siap diunduh oleh peserta.');
        // $this->loadPendingApprovals(); 

        $this->dispatchBrowserEvent('showToast', [
        'success' => true,
        'message' => 'Pendaftaran yang dipilih telah diterima dan peserta telah dibuat.'
        ]);

        $this->loadPendingApprovals();
    }

    // Aksi untuk membuka modal penolakan oleh Admin
    public function openRejectModal($pesertaId)
    {
        // Otorisasi Admin
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        $this->showRejectModal = true;
        $this->pesertaToRejectId = $pesertaId;
        $this->rejectionReasonInput = ''; // Reset input
    }

    // Aksi untuk menolak sertifikat oleh Admin
    public function rejectCertificate()
    {
        // Otorisasi Admin
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            session()->flash('error', 'Akses ditolak.');
            $this->closeRejectModal();
            return;
        }

        $peserta = Peserta::with('sertifikat')->findOrFail($this->pesertaToRejectId);
        $sertifikat = $peserta->sertifikat;

        // Validasi status pengajuan sebelum menolak
        if (!$sertifikat || $sertifikat->status !== 'disetujui_oleh_pembimbing') {
            session()->flash('error', 'Sertifikat tidak bisa ditolak pada status ini.');
            $this->closeRejectModal();
            return;
        }

        // Perbarui status sertifikat ke 'ditolak_oleh_admin'
        $sertifikat->update([
            'status' => 'ditolak_oleh_admin',
            'alasan_ditolak' => $this->rejectionReasonInput,
            'tgl_disetujui_admin' => null, // Reset tanggal persetujuan
        ]);

        session()->flash('message', 'Pengajuan sertifikat berhasil ditolak oleh Admin.');
        $this->closeRejectModal();
        $this->loadPendingApprovals(); // Refresh daftar setelah aksi
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
        return view('livewire.sertifikat-admin');
    }
}
