<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'npm', 
        'phone', 
        'univ', 
        'jenis_kelamin', 
        'alamat', 
        'email', 
        'bidang_id',
        'tgl_mulai_magang',
        'tgl_selesai_magang_rencana',
        'status_penyelesaian', 
        'surat_pengantar', 
        'dokumen_lain'
    ];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }
}
