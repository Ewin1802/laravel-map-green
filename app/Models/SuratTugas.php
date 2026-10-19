<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratTugas extends Model
{
    use HasFactory;

    protected $table = 'surat_tugas';

    protected $fillable = [
        'dana_id',
        'nomor_surat',
        'uraian',
        'no_bukti',
        'tgl_bukti',
        'file_surat',
        'user_name',
        'user_unit',
        'thn_anggaran',
    ];

    public function dana()
    {
        return $this->belongsTo(Dana::class);
    }

    public function pelaksana()
    {
        return $this->hasMany(PemakaianDana::class, 'surat_tugas_id');
    }
}
