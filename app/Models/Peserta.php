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
        'foto'
    ];

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
}
