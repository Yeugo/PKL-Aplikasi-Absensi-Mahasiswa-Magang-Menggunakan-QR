<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembimbing extends Model
{
    use HasFactory;

    protected $table = 'pembimbing';

    protected $fillable = [
        'name',
        'nip',
        'phone',
        'alamat',
        'bidang_id',
        'user_id',
        'foto'

    ];

    public function peserta()
    {
        return $this->hasMany(Peserta::class, 'pembimbing_id', 'id');
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id', 'id');
    }

    public function getPesertaNamesAttribute()
    {
        return $this->peserta->pluck('name')->join(', ');
    }

    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }

}
