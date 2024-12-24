<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'kepala_bidang',
        'jumlah_peserta'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'bidang_id', 'id');
    }

    public function pembimbing()
    {
        return $this->hasMany(Pembimbing::class, 'bidang_id', 'id');
    }

    public function peserta()
    {
        return $this->hasMany(Peserta::class,
        'peserta_bidang_id', 'id');

    }
}
