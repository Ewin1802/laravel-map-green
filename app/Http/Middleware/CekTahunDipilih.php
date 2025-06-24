<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CekTahunDipilih
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && !session()->has('tahun')) {
            return redirect()->route('pilih.tahun');
        }

        return $next($request);
    }
}
