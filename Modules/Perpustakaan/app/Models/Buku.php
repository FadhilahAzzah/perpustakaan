<?php

namespace Modules\Perpustakaan\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';

    protected $fillable = [
        'kode_buku',
        'judul',
        'penulis',
        'kategori',
        'stok',
    ];

    protected function casts(): array
    {
        return ['stok' => 'integer'];
    }
}
