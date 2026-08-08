<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanan';
    protected $fillable = ['no_pesanan', 'kode_menu', 'jumlah', 'subtotal', 'catatan', 'status_item'];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'no_pesanan', 'no_pesanan');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'kode_menu', 'kode_menu');
    }
}
