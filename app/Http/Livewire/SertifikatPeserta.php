<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Peserta;
use Carbon\Carbon;
use App\Models\Sertifikat; // Pastikan ini diimpor

class SertifikatPeserta extends Component
{
    public $peserta;
    public $rejectionReasonInput; // Properti untuk input alasan penolakan jika diperlukan

    // Variabel untuk modal penolakan (opsional)
    public $showRejectModal = false;

    public function mount($pesertaId = null)
    {
        // Jika pesertaId diberikan, gunakan itu (misal jika admin melihat status peserta lain)
        // Jika tidak, asumsikan peserta adalah user yang sedang login
        $this->peserta = $pesertaId
                        ? Peserta::with('sertifikat')->findOrFail($pesertaId)
                        : auth()->user()->peserta->load('sertifikat'); // Sesuaikan cara mendapatkan peserta login

        // Pastikan record sertifikat ada. Jika tidak, buatkan dulu.
        if (!$this->peserta->sertifikat) {
            $this->peserta->sertifikat()->create(['status' => 'pending']);
            $this->peserta->load('sertifikat'); // Reload relasi setelah membuat
        }
    }

    public function submitCertificateRequest()
    {
        // Pastikan hanya bisa submit jika statusnya pending atau ditolak
        if (!in_array($this->peserta->sertifikat->status, ['pending', 'ditolak_oleh_pembimbing', 'ditolak_oleh_admin'])) {
            session()->flash('error', 'Sertifikat tidak bisa diajukan saat ini.');
            return;
        }

        $this->peserta->sertifikat->update([
            'status' => 'submitted',
            'tgl_pengajuan' => Carbon::now(),
            'alasan_ditolak' => null, // Bersihkan alasan penolakan jika mengajukan ulang
            'tgl_disetujui_pembimbing' => null, // Reset persetujuan jika mengajukan ulang
            'tgl_disetujui_admin' => null,      // Reset persetujuan jika mengajukan ulang
        ]);

        session()->flash('message', 'Pengajuan sertifikat berhasil diajukan ke pembimbing. Mohon tunggu proses verifikasi.');
    }

    public function render()
    {
        return view('livewire.sertifikat-peserta');
    }
}