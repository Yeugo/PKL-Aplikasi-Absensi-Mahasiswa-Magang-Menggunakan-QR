<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';
    protected $fillable = [
        'pembimbing_id',
        'peserta_id',
        'sikap',
        'kedisiplinan',
        'kesungguhan',
        'mandiri',
        'kerjasama',
        'teliti',
        'pendapat',
        'hal_baru',
        'inisiatif', 
        'kepuasan',
        'catatan'
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
