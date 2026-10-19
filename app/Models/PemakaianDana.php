<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemakaianDana extends Model
{
    use HasFactory;
    protected $fillable = [
        'dana_id',
        'surat_tugas_id',
        'uraian',
        'nama_pelaksana',
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
    public function suratTugas()
    {
        return $this->belongsTo(SuratTugas::class, 'surat_tugas_id');
    }

    public function rekbelanja()
    {
        return $this->belongsTo(Rekbelanja::class);
    }
}
