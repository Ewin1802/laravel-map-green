<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class KegiatanController extends Controller
{
    // Index: Menampilkan daftar kegiatan
    public function index()
    {
        $kegiatans = Kegiatan::paginate(10);
        return view('pages.kegiatans.index', compact('kegiatans'));
    }

    // Create: Menampilkan form tambah kegiatan
    public function create()
    {
        return view('pages.kegiatans.create');
    }

    // Store: Menyimpan kegiatan baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode_kegiatan' => 'required|unique:kegiatans,kode_kegiatan',
            'desk_kegiatan' => 'required',
        ]);

        // Simpan data kegiatan
        Kegiatan::create([
            'kode_kegiatan' => $request->kode_kegiatan,
            'desk_kegiatan' => $request->desk_kegiatan,
        ]);

        return redirect()->route('kegiatans.index')->with('success', 'Kegiatan created successfully.');
    }

    // Show: Menampilkan detail kegiatan
    public function show($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        return view('pages.kegiatans.show', compact('kegiatan'));
    }

    // Edit: Menampilkan form edit kegiatan
    public function edit($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        return view('pages.kegiatans.edit', compact('kegiatan'));
    }

    // Update: Memperbarui data kegiatan
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'kode_kegiatan' => 'required|unique:kegiatans,kode_kegiatan,' . $id,
            'desk_kegiatan' => 'required',
        ]);

        // Perbarui data kegiatan
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->update([
            'kode_kegiatan' => $request->kode_kegiatan,
            'desk_kegiatan' => $request->desk_kegiatan,
        ]);

        return redirect()->route('kegiatans.index')->with('success', 'Kegiatan updated successfully.');
    }

    // Destroy: Menghapus kegiatan
    public function destroy($id)
    {
        try {
            $kegiatan = Kegiatan::findOrFail($id);
            $kegiatan->delete();
            return redirect()->route('kegiatans.index')->with('success', 'Kegiatan deleted successfully.');
        } catch (QueryException $e) {
            return redirect()->route('kegiatans.index')->with('error', 'Cannot delete this kegiatan. It is linked to other data.');
        } catch (\Exception $e) {
            return redirect()->route('kegiatans.index')->with('error', 'An unexpected error occurred. Please try again later.');
        }
    }
}
