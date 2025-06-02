<div>
    @include('partials.alerts') {{-- Pastikan ini di sini untuk flash messages --}}

    <div class="card shadow-sm border-0">
        {{-- <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0">Daftar Pengajuan Sertifikat Magang</h5>
        </div> --}}
        <div class="card-body p-0">
            @if($pesertaList->isEmpty())
                <div class="alert alert-info m-3">
                    Tidak ada peserta yang dibimbing oleh Anda, atau tidak ada pengajuan sertifikat yang menunggu tindakan.
                </div>
            @else
                <div class="table-responsive"> {{-- Tambahkan table-responsive untuk mobile --}}
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="text-center py-3">No</th>
                                <th class="py-3">Nama Peserta</th>
                                <th class="py-3">Universitas</th>
                                <th class="py-3">Bidang</th>
                                <th class="py-3">Status Sertifikat</th>
                                <th class="text-center py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesertaList as $peserta)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $peserta->name }}</td>
                                    <td>{{ $peserta->univ ?? '-' }}</td>
                                    <td>{{ $peserta->bidang->name ?? '-' }}</td>
                                    <td>
                                        @php
                                            $status = $peserta->sertifikat->status ?? 'pending'; // Default 'pending' jika belum ada record
                                            $statusClass = '';
                                            if ($status === 'submitted') $statusClass = 'badge bg-warning text-dark'; // Teks gelap untuk warning
                                            elseif ($status === 'disetujui_oleh_pembimbing') $statusClass = 'badge bg-info text-dark'; // Teks gelap untuk info
                                            elseif ($status === 'disetujui_oleh_admin') $statusClass = 'badge bg-success';
                                            elseif (Str::contains($status, 'ditolak')) $statusClass = 'badge bg-danger';
                                            else $statusClass = 'badge bg-secondary';
                                        @endphp
                                        <span class="{{ $statusClass }}">{{ Str::title(str_replace('_', ' ', $status)) }}</span>
                                        @if(Str::contains($status, 'ditolak') && $peserta->sertifikat->alasan_ditolak)
                                            <br><small class="text-muted">({{ $peserta->sertifikat->alasan_ditolak }})</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($status === 'submitted')
                                            <button 
                                                onclick="if(!confirm('Apakah Anda yakin ingin menyetujui sertifikat ini?')) return false;"
                                                wire:click="approveCertificate({{ $peserta->id }})" class="btn btn-sm btn-success me-1">
                                                <i class="bi bi-check-circle"></i> Setujui
                                            </button>
                                            <button wire:click="openRejectModal({{ $peserta->id }})" class="btn btn-sm btn-danger">
                                                <i class="bi bi-x-circle"></i> Tolak
                                            </button>
                                        @elseif($status === 'disetujui_oleh_admin')
                                            <a href="{{ route('peserta.sertifikat.download', $peserta->id) }}" class="btn btn-sm btn-primary" target="_blank">
                                                <i class="bi bi-download"></i> Unduh Sertifikat
                                            </a>
                                        @else
                                            <span class="text-muted">Menunggu Pengajuan</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Penolakan --}}
    @if ($showRejectModal)
        <div class="modal d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tolak Pengajuan Sertifikat</h5>
                        <button type="button" class="btn-close" wire:click="closeRejectModal"></button>
                    </div>
                    <div class="modal-body">
                        <label for="rejectionReason">Alasan Penolakan:</label>
                        <textarea wire:model.defer="rejectionReasonInput" id="rejectionReason" class="form-control" rows="3" placeholder="Masukkan alasan penolakan..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeRejectModal">Batal</button>
                        <button type="button" class="btn btn-danger" wire:click="rejectCertificate()">Tolak</button>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div> {{-- Untuk overlay --}}
        @endif
</div>