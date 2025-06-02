<div>
    <div class="card">
        <div class="card-header bg-dark text-white">Status Sertifikat Anda</div>
        <div class="card-body">
            <p>Status Pengajuan: <strong>{{ Str::title(str_replace('_', ' ', $peserta->sertifikat->status ?? 'N/A')) }}</strong></p>

            @if (in_array($peserta->sertifikat->status, ['ditolak_oleh_pembimbing', 'ditolak_oleh_admin']))
                <div class="alert alert-danger mt-3">
                    Pengajuan sertifikat Anda ditolak.
                    @if ($peserta->sertifikat->alasan_ditolak)
                        <p class="mb-0">Catatan: {{ $peserta->sertifikat->alasan_ditolak }}</p>
                    @endif
                </div>
                <button wire:click="submitCertificateRequest()" class="btn btn-sm btn-warning mt-2">
                    Ajukan Ulang Sertifikat
                </button>
            @elseif ($peserta->sertifikat->status === 'pending')
                <p class="text-secondary mt-2">Anda belum mengajukan permintaan sertifikat.</p>
                <button wire:click="submitCertificateRequest()" class="btn btn-primary btn-sm mt-2">
                    Ajukan Sertifikat
                </button>
            @elseif ($peserta->sertifikat->status === 'disetujui_oleh_admin')
                <div class="alert alert-success mt-3">
                    Sertifikat Anda telah disetujui dan siap diunduh!
                </div>
                <a href=" {{ route('peserta.sertifikat.download', $peserta->id) }} " class="btn btn-success btn-sm" target="_blank">
                    <i class="bi bi-download me-2"></i> Unduh Sertifikat
                </a>
            @else {{-- submitted, approved_by_pembimbing --}}
                <p class="text-info mt-2">Permintaan sertifikat Anda sedang dalam proses verifikasi.</p>
            @endif
        </div>
    </div>
</div>
{{-- {{ route('peserta.sertifikat.download', $peserta->id) }} --}}

