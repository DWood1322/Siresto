<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'kode_menu';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['kode_menu', 'nama_menu', 'kategori', 'harga', 'status_ketersediaan'];

    public function detailPesanan(): HasMany
    {
        return $this->hasMany(DetailPesanan::class, 'kode_menu', 'kode_menu');
    }
}
