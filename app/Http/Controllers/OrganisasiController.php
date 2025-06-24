<?php

namespace App\Http\Controllers;

use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class OrganisasiController extends Controller
{
    // Index: Menampilkan daftar organisasi
    public function index()
    {
        $organisasis = Organisasi::paginate(10);
        return view('pages.organisasis.index', compact('organisasis'));
    }

    // Create: Menampilkan form tambah organisasi
    public function create()
    {
        return view('pages.organisasis.create');
    }

    // Store: Menyimpan organisasi baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode_organisasi' => 'required|unique:organisasis,kode_organisasi',
            'desk_organisasi' => 'nullable',
        ]);

        // Simpan data organisasi
        Organisasi::create([
            'kode_organisasi' => $request->kode_organisasi,
            'desk_organisasi' => $request->desk_organisasi,
        ]);

        return redirect()->route('organisasis.index')->with('success', 'Organisasi created successfully.');
    }

    // Show: Menampilkan detail organisasi
    public function show($id)
    {
        $organisasi = Organisasi::findOrFail($id);
        return view('pages.organisasis.show', compact('organisasi'));
    }

    // Edit: Menampilkan form edit organisasi
    public function edit($id)
    {
        $organisasi = Organisasi::findOrFail($id);
        return view('pages.organisasis.edit', compact('organisasi'));
    }

    // Update: Memperbarui data organisasi
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'kode_organisasi' => 'required|unique:organisasis,kode_organisasi,' . $id,
            'desk_organisasi' => 'nullable',
        ]);

        // Perbarui data organisasi
        $organisasi = Organisasi::findOrFail($id);
        $organisasi->update([
            'kode_organisasi' => $request->kode_organisasi,
            'desk_organisasi' => $request->desk_organisasi,
        ]);

        return redirect()->route('organisasis.index')->with('success', 'Organisasi updated successfully.');
    }

    // Destroy: Menghapus organisasi
    public function destroy($id)
    {
        try {
            $organisasi = Organisasi::findOrFail($id);
            $organisasi->delete();
            return redirect()->route('organisasis.index')->with('success', 'Organisasi deleted successfully.');
        } catch (QueryException $e) {
            return redirect()->route('organisasis.index')->with('error', 'Cannot delete this organisasi. It is linked to other data.');
        } catch (\Exception $e) {
            return redirect()->route('organisasis.index')->with('error', 'An unexpected error occurred. Please try again later.');
        }
    }
}
