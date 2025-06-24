<?php

namespace App\Http\Controllers;

use App\Models\Rekbelanja;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class RekbelanjaController extends Controller
{
    // Index: Menampilkan daftar rekbelanja
    public function index()
    {
        $rekbelanjas = Rekbelanja::paginate(10);
        return view('pages.rekbelanjas.index', compact('rekbelanjas'));
    }

    // Create: Menampilkan form tambah rekbelanja
    public function create()
    {
        return view('pages.rekbelanjas.create');
    }

    // Store: Menyimpan data rekbelanja baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_rek' => 'required|unique:rekbelanjas,kode_rek',
            'desk_belanja' => 'nullable',
            'jenis_belanja' => 'nullable',
        ]);

        Rekbelanja::create([
            'kode_rek' => $request->kode_rek,
            'desk_belanja' => $request->desk_belanja,
            'jenis_belanja' => $request->jenis_belanja,
        ]);

        return redirect()->route('rekbelanjas.index')->with('success', 'Rekening belanja berhasil ditambahkan.');
    }

    // Show: Menampilkan detail rekbelanja
    public function show($id)
    {
        $rekbelanja = Rekbelanja::findOrFail($id);
        return view('pages.rekbelanjas.show', compact('rekbelanja'));
    }

    // Edit: Menampilkan form edit rekbelanja
    public function edit($id)
    {
        $rekbelanja = Rekbelanja::findOrFail($id);
        return view('pages.rekbelanjas.edit', compact('rekbelanja'));
    }

    // Update: Memperbarui data rekbelanja
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_rek' => 'required|unique:rekbelanjas,kode_rek,' . $id,
            'desk_belanja' => 'nullable',
            'jenis_belanja' => 'nullable',
        ]);

        $rekbelanja = Rekbelanja::findOrFail($id);
        $rekbelanja->update([
            'kode_rek' => $request->kode_rek,
            'desk_belanja' => $request->desk_belanja,
            'jenis_belanja' => $request->jenis_belanja,
        ]);

        return redirect()->route('rekbelanjas.index')->with('success', 'Rekening belanja berhasil diperbarui.');
    }

    // Destroy: Menghapus rekbelanja
    public function destroy($id)
    {
        try {
            $rekbelanja = Rekbelanja::findOrFail($id);
            $rekbelanja->delete();
            return redirect()->route('rekbelanjas.index')->with('success', 'Rekening belanja berhasil dihapus.');
        } catch (QueryException $e) {
            return redirect()->route('rekbelanjas.index')->with('error', 'Tidak dapat menghapus. Data ini terhubung dengan data lain.');
        } catch (\Exception $e) {
            return redirect()->route('rekbelanjas.index')->with('error', 'Terjadi kesalahan. Coba lagi nanti.');
        }
    }
}

