<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemakaianDana extends Model
{
    use HasFactory;
    protected $fillable = [
        'dana_id',
        'uraian',
        'no_bukti',
        'jumlah_pemakaian',
        'file_bukti',
        'tgl_bukti',
        'desk_belanja',
        'jenis_belanja',
        'user_name',
        'user_unit',
        'thn_anggaran',
    ];

    public function dana()
    {
        return $this->belongsTo(Dana::class);
    }
    public function rekbelanja()
    {
        return $this->belongsTo(Rekbelanja::class);
    }
}
