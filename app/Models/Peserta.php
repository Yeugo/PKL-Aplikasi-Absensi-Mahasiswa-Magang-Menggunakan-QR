<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    use HasFactory;

    protected $table = 'peserta';

    protected $fillable = [
        'name',
        'npm',
        'phone',
        'alamat',
        'univ',
        'peserta_bidang_id',
        'pembimbing_id',
        'user_id',
        'foto',
        'tgl_mulai_magang',
        'tgl_selesai_magang_rencana',
        'status_penyelesaian',
        'tanggal_penyelesaian_aktual',
    ];

    // protected $casts = [
    //     'tgl_mulai_magang' => 'date',
    //     'tgl_selesai_magang_rencana' => 'date',
    //     'tanggal_penyelesaian_aktual' => 'date',
    // ];

    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class);
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'peserta_bidang_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class);
    }

    public function nilai()
    {
        return $this->hasOne(Nilai::class);
    } 

    public function sertifikat()
    {
        return $this->hasOne(Sertifikat::class, 'peserta_id');
    }
}
