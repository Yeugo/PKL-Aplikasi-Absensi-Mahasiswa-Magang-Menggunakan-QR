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
        'bidang_id'
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

}
