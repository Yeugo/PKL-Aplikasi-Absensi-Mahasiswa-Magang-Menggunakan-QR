<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'kepala_bidang'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'bidang_id', 'id');
    }
}
