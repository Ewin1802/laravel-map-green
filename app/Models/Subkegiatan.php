<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subkegiatan extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_subkegiatan',
        'desk_subkegiatan',
    ];
}
