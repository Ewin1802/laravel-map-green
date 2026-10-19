<?php

namespace App\Http\Controllers;

use App\Models\Sumberdana;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class SumberdanaController extends Controller
{
    // Index: Menampilkan daftar sumber dana
    public function index()
    {
        $sumberdanas = Sumberdana::paginate(10);
        return view('pages.sumberdanas.index', compact('sumberdanas'));
    }

    // Create: Menampilkan form tambah sumber dana
    public function create()
    {
        return view('pages.sumberdanas.create');
    }

    // Store: Menyimpan sumber dana baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'sumber_dana' => 'required|unique:sumberdanas,sumber_dana',
        ]);

        // Simpan data sumber dana
        Sumberdana::create([
            'sumber_dana' => $request->sumber_dana,
        ]);

        return redirect()->route('sumberdanas.index')->with('success', 'Sumber Dana created successfully.');
    }

   
    // Edit: Menampilkan form edit sumber dana
    public function edit($id)
    {
        $sumberdana = Sumberdana::findOrFail($id);
        return view('pages.sumberdanas.edit', compact('sumberdana'));
    }

    // Update: Memperbarui data sumber dana
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'sumber_dana' => 'required|unique:sumberdanas,sumber_dana,' . $id,
        ]);

        // Perbarui data sumber dana
        $sumberdana = Sumberdana::findOrFail($id);
        $sumberdana->update([
            'sumber_dana' => $request->sumber_dana,
        ]);

        return redirect()->route('sumberdanas.index')->with('success', 'Sumber Dana updated successfully.');
    }

    // Destroy: Menghapus sumber dana
    public function destroy($id)
    {
        try {
            $sumberdana = Sumberdana::findOrFail($id);
            $sumberdana->delete();
            return redirect()->route('sumberdanas.index')->with('success', 'Sumber Dana deleted successfully.');
        } catch (QueryException $e) {
            return redirect()->route('sumberdanas.index')->with('error', 'Cannot delete this Sumber Dana. It is linked to other data.');
        } catch (\Exception $e) {
            return redirect()->route('sumberdanas.index')->with('error', 'An unexpected error occurred. Please try again later.');
        }
    }
}
