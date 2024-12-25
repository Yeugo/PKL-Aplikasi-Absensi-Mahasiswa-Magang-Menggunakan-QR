<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan';

    protected $fillable =
    [
        'judul',
        'deskripsi',
        'tgl_kegiatan',
        'waktu_mulai',
        'waktu_selesai',
        'jenis_kegiatan',
        'feedback',
        'peserta_id',
        'pembimbing_id',
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class);
    }

}
