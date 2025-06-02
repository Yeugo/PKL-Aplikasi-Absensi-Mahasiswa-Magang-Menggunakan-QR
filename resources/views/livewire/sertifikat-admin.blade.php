<div>
    @if($pendingApprovals->isEmpty())
        <div class="alert alert-info">Tidak ada pengajuan sertifikat yang menunggu persetujuan Admin.</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center py-3">No</th>
                        <th class="py-3">Nama Peserta</th>
                        <th class="py-3">Pembimbing</th>
                        <th class="py-3">Tanggal Disetujui Pembimbing</th>
                        <th class="text-center py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingApprovals as $peserta)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $peserta->name }}</td>
                            <td>{{ $peserta->pembimbing->name ?? '-' }}</td>
                            <td>
                                {{ $peserta->sertifikat->tgl_disetujui_pembimbing ? $peserta->sertifikat->tgl_disetujui_pembimbing->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="text-center">
                                <button
                                    onclick="if(!confirm('Apakah Anda yakin ingin menyetujui sertifikat ini?')) { event.stopImmediatePropagation(); return false; }"
                                    wire:click="approveCertificate({{ $peserta->id }})" class="btn btn-sm btn-success me-1">
                                    <i class="bi bi-check-circle"></i> Setujui
                                </button>
                                <button wire:click="openRejectModal({{ $peserta->id }})" class="btn btn-sm btn-danger">
                                    <i class="bi bi-x-circle"></i> Tolak
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Modal Penolakan Admin --}}
    @if ($showRejectModal)
        <div class="modal d-block" tabindex="-1" role="dialog"> {{-- Hapus style="background-color: rgba(0,0,0,0.5);" di sini --}}
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
        </div>
        <div class="modal-backdrop fade show"></div> {{-- Ini tetap ada untuk overlay latar belakang --}}
    @endif
</div>