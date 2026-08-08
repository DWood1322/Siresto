<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meja extends Model
{
    protected $table = 'meja';
    protected $primaryKey = 'no_meja';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['no_meja', 'kapasitas_kursi', 'status_meja'];

    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'no_meja', 'no_meja');
    }
}
