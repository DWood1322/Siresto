<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    protected $primaryKey = 'id_user';
    protected $fillable = ['nama_user', 'username', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];

    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'id_pelayan', 'id_user');
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'id_kasir', 'id_user');
    }
}
