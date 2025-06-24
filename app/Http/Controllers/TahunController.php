<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TahunController extends Controller
{
    public function show()
    {
        $tahunSekarang = date('Y');
        $daftarTahun = [$tahunSekarang, $tahunSekarang - 1, $tahunSekarang - 2];

        return view('pages.auth.pilih_tahun', compact('daftarTahun'));
    }

    public function set(Request $request)
    {
        $request->validate([
            'tahun' => 'required|in:' . implode(',', [date('Y'), date('Y') - 1, date('Y') - 2]),
        ]);

        session(['tahun' => $request->tahun]);

        return redirect()->route('dashboard'); // atau route dashboard kamu
    }
}
