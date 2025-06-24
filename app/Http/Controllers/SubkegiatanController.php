<?php

namespace App\Http\Controllers;

use App\Models\Subkegiatan;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class SubkegiatanController extends Controller
{
    // Index: Menampilkan daftar subkegiatan
    public function index()
    {
        $subkegiatans = Subkegiatan::paginate(10);
        return view('pages.subkegiatans.index', compact('subkegiatans'));
    }

    // Create: Menampilkan form tambah subkegiatan
    public function create()
    {
        return view('pages.subkegiatans.create');
    }

    // Store: Menyimpan subkegiatan baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode_subkegiatan' => 'required|unique:subkegiatans,kode_subkegiatan',
            'desk_subkegiatan' => 'required',
        ]);

        // Simpan data subkegiatan
        Subkegiatan::create([
            'kode_subkegiatan' => $request->kode_subkegiatan,
            'desk_subkegiatan' => $request->desk_subkegiatan,
        ]);

        return redirect()->route('subkegiatans.index')->with('success', 'Subkegiatan created successfully.');
    }

    // Show: Menampilkan detail subkegiatan
    public function show($id)
    {
        $subkegiatan = Subkegiatan::findOrFail($id);
        return view('pages.subkegiatans.show', compact('subkegiatan'));
    }

    // Edit: Menampilkan form edit subkegiatan
    public function edit($id)
    {
        $subkegiatan = Subkegiatan::findOrFail($id);
        return view('pages.subkegiatans.edit', compact('subkegiatan'));
    }

    // Update: Memperbarui data subkegiatan
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'kode_subkegiatan' => 'required|unique:subkegiatans,kode_subkegiatan,' . $id,
            'desk_subkegiatan' => 'required',
        ]);

        // Perbarui data subkegiatan
        $subkegiatan = Subkegiatan::findOrFail($id);
        $subkegiatan->update([
            'kode_subkegiatan' => $request->kode_subkegiatan,
            'desk_subkegiatan' => $request->desk_subkegiatan,
        ]);

        return redirect()->route('subkegiatans.index')->with('success', 'Subkegiatan updated successfully.');
    }

    // Destroy: Menghapus subkegiatan
    public function destroy($id)
    {
        try {
            $subkegiatan = Subkegiatan::findOrFail($id);
            $subkegiatan->delete();
            return redirect()->route('subkegiatans.index')->with('success', 'Subkegiatan deleted successfully.');
        } catch (QueryException $e) {
            return redirect()->route('subkegiatans.index')->with('error', 'Cannot delete this subkegiatan. It is linked to other data.');
        } catch (\Exception $e) {
            return redirect()->route('subkegiatans.index')->with('error', 'An unexpected error occurred. Please try again later.');
        }
    }
}
