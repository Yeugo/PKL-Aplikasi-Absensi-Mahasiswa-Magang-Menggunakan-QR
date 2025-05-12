<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ADMIN_ROLE_ID = 1;
    const USER_ROLE_ID = 2;
    const PEMBIMBING_ROLE_ID = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'bidang_id',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    public function scopeOnlyEmployees($query)
    {
        return $query->where('role_id', self::USER_ROLE_ID);
    }

    public function isAdmin()
    {
        return $this->role_id === self::ADMIN_ROLE_ID;
    }


    public function isUser()
    {
        return $this->role_id === self::USER_ROLE_ID;
    }

    public function isPembimbing()
    {
        return $this->role_id === self::PEMBIMBING_ROLE_ID;
    }

    public function peserta(): HasOne
    {
        return $this->hasOne(Peserta::class);
    }

    public function pembimbing(): HasOne
    {
        return $this->hasOne(Pembimbing::class, 'user_id', 'id');
    }

    
}
