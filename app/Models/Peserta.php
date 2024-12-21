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
        'bidang_id',
        'pembimbing_id'
    ];

    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class);
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id', 'id');
    }
}
