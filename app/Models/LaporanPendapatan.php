<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanPendapatan extends Model
{
    protected $table = 'laporan_pendapatan';
    protected $primaryKey = 'no_laporan';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'no_laporan', 'periode_laporan', 'tgl_awal', 'tgl_akhir',
        'total_pendapatan', 'tgl_cetak', 'id_kasir', 'status_validasi'
    ];

    public function kasir(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_kasir', 'id_user');
    }
}
