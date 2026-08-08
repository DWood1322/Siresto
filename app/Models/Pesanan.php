<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'no_pesanan';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['no_pesanan', 'tgl_pesanan', 'no_meja', 'status_pesanan', 'id_pelayan', 'id_pelanggan'];

    public function meja(): BelongsTo
    {
        return $this->belongsTo(Meja::class, 'no_meja', 'no_meja');
    }

    public function pelayan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pelayan', 'id_user');
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailPesanan::class, 'no_pesanan', 'no_pesanan');
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'no_pesanan', 'no_pesanan');
    }
}
