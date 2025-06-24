<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dana;
use App\Models\Program;
use App\Models\Kegiatan;
use App\Models\Subkegiatan;
use App\Models\Organisasi;
use App\Models\Sumberdana;
use Illuminate\Support\Facades\Auth;

class DanaController extends Controller
{

    public function index(Request $request)
    {
        $tahun = session('tahun'); // ambil tahun dari session

        $query = Dana::whereYear('created_at', $tahun); // filter berdasarkan tahun

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('program', function ($q2) use ($search) {
                    $q2->where('nama_program', 'like', "%$search%");
                })->orWhereHas('kegiatan', function ($q2) use ($search) {
                    $q2->where('desk_kegiatan', 'like', "%$search%");
                });
            });
        }

        $danas = $query->with(['program', 'kegiatan', 'subkegiatan', 'organisasi', 'sumberdana'])
                    ->paginate(10);

        $subkegiatans = Subkegiatan::all(); // bisa juga difilter kalau perlu

        return view('pages.danas.index', compact('danas', 'subkegiatans'));
    }

    public function create()
    {
        return view('pages.danas.create', [
            'programs' => Program::all(),
            'kegiatans' => Kegiatan::all(),
            'subkegiatans' => Subkegiatan::all(),
            'organisasis' => Organisasi::all(),
            'sumberdanas' => Sumberdana::all(),
        ]);
    }

    public function edit($id)
    {
        $dana = Dana::findOrFail($id);

        return view('pages.danas.edit', [
            'dana' => $dana,
            'programs' => Program::all(),
            'kegiatans' => Kegiatan::all(),
            'subkegiatans' => Subkegiatan::all(),
            'organisasis' => Organisasi::all(),
            'sumberdanas' => Sumberdana::all(),
            'dana_asals' => Dana::where('id', '!=', $id)->get(), // agar tidak memilih diri sendiri
            // 'dana_asals' => Dana::where('id', '!=', $id)->with('program')->get()
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'subkegiatan_id' => 'required|exists:subkegiatans,id',
            'organisasi_id' => 'required|exists:organisasis,id',
            'sumberdana_id' => 'required|exists:sumberdanas,id',
            'dana_asal_id' => 'nullable|exists:danas,id',
        ]);

        $user = Auth::user();
        $thnAnggaran = date('Y');
        $adalahPergeseran = $request->filled('dana_asal_id');

        if ($adalahPergeseran) {
            $danaLama = Dana::find($request->dana_asal_id);

            if (!$danaLama) {
                return redirect()->back()->withErrors(['dana_asal_id' => 'Dana asal tidak ditemukan.']);
            }

            $request->validate([
                'jumlah_pemakaian' => 'required|numeric|min:1|max:' . $danaLama->jumlah_dana,
            ]);

            $jumlahPemakaian = $request->jumlah_pemakaian;

            // Potong dana asal
            $sisaDana = $danaLama->jumlah_dana - $jumlahPemakaian;
            $danaLama->update([
                'jumlah_dana' => $sisaDana,
                'available' => $sisaDana > 0 ? 1 : 0,
            ]);

            // Buat dana baru dari hasil pergeseran
            Dana::create([
                'program_id' => $request->program_id,
                'kegiatan_id' => $request->kegiatan_id,
                'subkegiatan_id' => $request->subkegiatan_id,
                'organisasi_id' => $request->organisasi_id,
                'sumberdana_id' => $request->sumberdana_id,
                'jumlah_dana' => $jumlahPemakaian,
                'dana_asal_id' => $request->dana_asal_id,
                'available' => 1,
                'user_name' => $user->name,
                'user_unit' => $user->unit,
                'thn_anggaran' => $thnAnggaran,
                'ket' => $request->ket,
            ]);
        } else {
            $request->validate([
                'jumlah_dana' => 'required|numeric|min:0',
            ]);

            Dana::create([
                'program_id' => $request->program_id,
                'kegiatan_id' => $request->kegiatan_id,
                'subkegiatan_id' => $request->subkegiatan_id,
                'organisasi_id' => $request->organisasi_id,
                'sumberdana_id' => $request->sumberdana_id,
                'jumlah_dana' => $request->jumlah_dana,
                'available' => 1,
                'user_name' => $user->name,
                'user_unit' => $user->unit,
                'thn_anggaran' => $thnAnggaran,
                'ket' => $request->ket,
                'dana_asal_id' => null, // opsional, Laravel default null
            ]);
        }

        return redirect()->route('danas.index')->with('success', $adalahPergeseran ? 'Pergeseran berhasil disimpan.' : 'Anggaran baru berhasil ditambahkan.');
    }

    public function show($id)
    {
        $dana = Dana::with(['program', 'kegiatan', 'subkegiatan', 'organisasi', 'sumberdana'])->findOrFail($id);
        return response()->json($dana);
    }

    public function update(Request $request, $id)
    {
        $dana = Dana::findOrFail($id);

        $request->validate([
            'program_id' => 'sometimes|exists:programs,id',
            'kegiatan_id' => 'sometimes|exists:kegiatans,id',
            'subkegiatan_id' => 'sometimes|exists:subkegiatans,id',
            'organisasi_id' => 'sometimes|exists:organisasis,id',
            'sumberdana_id' => 'sometimes|exists:sumberdanas,id',
            'jumlah_dana' => 'sometimes|numeric|min:0',
        ]);

        $dana->update($request->all());
        return redirect()->route('danas.index')->with('success', 'Anggaran berhasil diperbarui.');
    }

    // public function destroy($id)
    // {
    //     $dana = Dana::findOrFail($id);
    //     $dana->delete();

    //     // return response()->json(['message' => 'Dana berhasil dihapus']);
    //     return redirect()->route('danas.index')->with('success', 'Dana berhasil dihapus.');
    // }

    public function destroy($id)
    {
        $dana = Dana::findOrFail($id);

        // Jika dana ini berasal dari pergeseran
        if ($dana->dana_asal_id) {
            $danaAsal = Dana::find($dana->dana_asal_id);

            if ($danaAsal) {
                // Tambahkan kembali jumlah dana yang telah digeser
                $danaAsal->jumlah_dana += $dana->jumlah_dana;
                // $danaAsal->available = true; // jika sebelumnya false, aktifkan kembali
                $danaAsal->save();
            }
        }

        $dana->delete();

        return redirect()->route('danas.index')->with('success', 'Anggaran berhasil dihapus.');
    }


}
