<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ProgramController extends Controller
{
    // Index: Menampilkan daftar program
    public function index()
    {
        $programs = Program::paginate(10);
        return view('pages.programs.index', compact('programs'));
    }

    // Create: Menampilkan form tambah program
    public function create()
    {
        return view('pages.programs.create');
    }

    // Store: Menyimpan program baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode_program' => 'required|unique:programs,kode_program',
            'desk_program' => 'required',
        ]);

        // Simpan data program
        Program::create([
            'kode_program' => $request->kode_program,
            'desk_program' => $request->desk_program,
        ]);

        return redirect()->route('programs.index')->with('success', 'Program created successfully.');
    }

    // Show: Menampilkan detail program
    public function show($id)
    {
        $program = Program::findOrFail($id);
        return view('pages.programs.show', compact('program'));
    }

    // Edit: Menampilkan form edit program
    public function edit($id)
    {
        $program = Program::findOrFail($id);
        return view('pages.programs.edit', compact('program'));
    }

    // Update: Memperbarui data program
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'kode_program' => 'required|unique:programs,kode_program,' . $id,
            'desk_program' => 'required',
        ]);

        // Perbarui data program
        $program = Program::findOrFail($id);
        $program->update([
            'kode_program' => $request->kode_program,
            'desk_program' => $request->desk_program,
        ]);

        return redirect()->route('programs.index')->with('success', 'Program updated successfully.');
    }

    // Destroy: Menghapus program
    public function destroy($id)
    {
        try {
            $program = Program::findOrFail($id);
            $program->delete();
            return redirect()->route('programs.index')->with('success', 'Program deleted successfully.');
        } catch (QueryException $e) {
            return redirect()->route('programs.index')->with('error', 'Cannot delete this program. It is linked to other data.');
        } catch (\Exception $e) {
            return redirect()->route('programs.index')->with('error', 'An unexpected error occurred. Please try again later.');
        }
    }
}
