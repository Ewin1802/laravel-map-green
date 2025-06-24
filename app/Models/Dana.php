<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dana extends Model
{
    use HasFactory;
    protected $fillable = [
        'program_id',
        'kegiatan_id',
        'subkegiatan_id',
        'organisasi_id',
        'sumberdana_id',
        'jumlah_dana',
        'available',
        'user_name',
        'user_unit',
        'thn_anggaran',
        'ket',
        'dana_asal_id',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function subkegiatan()
    {
        return $this->belongsTo(Subkegiatan::class);
    }

    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class);
    }

    public function sumberdana()
    {
        return $this->belongsTo(Sumberdana::class);
    }

    public function pemakaianDanas()
    {
        return $this->hasMany(PemakaianDana::class);
    }

    public function rekbelanja()
    {
        return $this->belongsTo(Rekbelanja::class);
    }
}
