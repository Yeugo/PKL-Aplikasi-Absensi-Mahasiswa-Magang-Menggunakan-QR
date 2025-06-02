<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;

    protected $table = 'sertifikat';

    protected $fillable = [
        'peserta_id',
        'status',
        'tgl_pengajuan',
        'tgl_disetujui_pembimbing',
        'tgl_disetujui_admin',
        'alasan_ditolak',
    ];

    protected $casts = [
        'tgl_pengajuan' => 'datetime',
        'tgl_disetujui_pembimbing' => 'datetime',
        'tgl_disetujui_admin' => 'datetime',
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }
}
