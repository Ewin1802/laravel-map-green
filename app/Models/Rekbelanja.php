<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekbelanja extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_rek',
        'desk_belanja',
        'jenis_belanja',
    ];
}
