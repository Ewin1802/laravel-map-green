<?php

namespace App\Http\Controllers;

use App\Models\PemakaianDana;
use App\Models\Dana;
use App\Models\Rekbelanja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class PemakaianDanaController extends Controller
{

    public function index(Request $request)
    {
        $tahun = session('tahun');
        $search = $request->search;

        $pemakaians = PemakaianDana::with(['dana.subkegiatan'])
            ->whereYear('tgl_bukti', $tahun)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('no_bukti', 'like', '%' . $search . '%')
                    ->orWhereHas('dana.subkegiatan', function ($q2) use ($search) {
                        $q2->where('desk_subkegiatan', 'like', '%' . $search . '%');
                    });
                });
            })
            ->paginate(10);

        return view('pages.pemakaiandanas.index', compact('pemakaians'));
    }



    public function create()
    {
        $danas = Dana::with(['kegiatan', 'subkegiatan'])
                    ->where('available', 1)
                    ->get();
                    $rekbelanjas = Rekbelanja::all();

        return view('pages.pemakaiandanas.create', compact('danas', 'rekbelanjas'));
    }

    public function edit($id)
    {
        $pemakaianDana = PemakaianDana::findOrFail($id);
        $dana = Dana::findOrFail($pemakaianDana->dana_id);

        $totalPemakaianLain = PemakaianDana::where('dana_id', $dana->id)
            ->where('id', '!=', $pemakaianDana->id)
            ->sum('jumlah_pemakaian');

        $sisaDana = $dana->jumlah_dana - $totalPemakaianLain;

        $danas = Dana::all();
        $rekbelanjas = Rekbelanja::all();

        return view('pages.pemakaiandanas.edit', compact('pemakaianDana', 'sisaDana', 'danas', 'rekbelanjas'));
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'uraian' => 'required|string|max:255',
            // 'no_bukti' => 'required|string|max:100',
            'jumlah_pemakaian' => 'required|string',
            'tgl_bukti' => 'required|date',
            'file_bukti' => 'nullable|file|mimes:pdf|max:10240',
            'desk_belanja' => 'required|string',
            'jenis_belanja' => 'required|string',
        ]);

        $pemakaianDana = PemakaianDana::findOrFail($id);
        $dana = Dana::findOrFail($pemakaianDana->dana_id);

        $jumlahBaru = (int) str_replace('.', '', $request->jumlah_pemakaian);

        $totalPemakaianLain = PemakaianDana::where('dana_id', $pemakaianDana->dana_id)
            ->where('id', '!=', $pemakaianDana->id)
            ->sum('jumlah_pemakaian');

        $totalPemakaianSetelahUpdate = $totalPemakaianLain + $jumlahBaru;

        if ($totalPemakaianSetelahUpdate > $dana->jumlah_dana) {
            return back()->withErrors(['jumlah_pemakaian' => 'Jumlah pemakaian melebihi anggaran tersedia.'])->withInput();
        }

        if ($request->hasFile('file_bukti')) {
            if ($pemakaianDana->file_bukti && Storage::exists($pemakaianDana->file_bukti)) {
                Storage::delete($pemakaianDana->file_bukti);
            }
            $path = $request->file('file_bukti')->store('bukti_dana');
            $pemakaianDana->file_bukti = $path;
        }

        $user = Auth::user();

        $pemakaianDana->uraian = $request->uraian;
        $pemakaianDana->no_bukti = $request->no_bukti;
        $pemakaianDana->jumlah_pemakaian = $jumlahBaru;
        $pemakaianDana->tgl_bukti = $request->tgl_bukti;
        $pemakaianDana->desk_belanja = $request->desk_belanja;
        $pemakaianDana->jenis_belanja = $request->jenis_belanja;
        $pemakaianDana->user_name = $user->name;
        $pemakaianDana->user_unit = $user->unit;
        $pemakaianDana->thn_anggaran = now()->year;
        $pemakaianDana->save();

        return redirect()->route('pemakaiandanas.index')->with('success', 'Data pemakaian dana berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Data pemakaian dana diterima:', $request->all());

            // Validasi input
            $request->validate([
                'dana_id' => 'required|exists:danas,id',
                'uraian' => 'required|string|max:255',
                // 'no_bukti' => 'required|string|max:100|unique:pemakaian_danas,no_bukti',
                'jumlah_pemakaian' => 'required|numeric|min:0',
                'tgl_bukti' => 'required|date',
                'file_bukti' => 'nullable|file|mimes:pdf|max:10240',
                'desk_belanja' => 'required|string',
                'jenis_belanja' => 'required|string',
            ]);

            $user = Auth::user();

            $dana = Dana::findOrFail($request->dana_id);

            if ($request->jumlah_pemakaian > $dana->jumlah_dana) {
                return back()->with('error', 'Jumlah pemakaian melebihi dana yang tersedia.')->withInput();
            }

            $filePath = null;
            if ($request->hasFile('file_bukti')) {
                $file = $request->file('file_bukti');
                $uraianSlug = Str::slug($request->uraian, '-');
                $timestamp = now()->format('YmdHi');
                $filename = $uraianSlug . '-' . $timestamp . '.' . $file->getClientOriginalExtension();

                $storedPath = $file->storeAs('public/bukti-pdf', $filename);
                $filePath = str_replace('public/', '', $storedPath);
            }

            $pemakaian = PemakaianDana::create([
                'dana_id' => $request->dana_id,
                'uraian' => $request->uraian,
                'no_bukti' => $request->no_bukti,
                'jumlah_pemakaian' => $request->jumlah_pemakaian,
                'file_bukti' => $filePath,
                'tgl_bukti' => $request->tgl_bukti,
                'desk_belanja' => $request->desk_belanja,
                'jenis_belanja' => $request->jenis_belanja,
                'user_name' => $user->name,
                'user_unit' => $user->unit,
                'thn_anggaran' => now()->year,
            ]);

            $dana->jumlah_dana -= $request->jumlah_pemakaian;
            if ($dana->jumlah_dana <= 0) {
                $dana->available = 0;
            }
            $dana->save();

            \Log::info('Pemakaian dana berhasil disimpan:', [
                'id' => $pemakaian->id,
                'file' => $filePath
            ]);

            return redirect()->route('pemakaiandanas.index')->with('success', 'Data pemakaian dana berhasil disimpan.');
        } catch (\Exception $e) {
            \Log::error('Terjadi kesalahan saat menyimpan pemakaian dana:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
        }
    }

    public function destroy($id)
    {
        $pemakaian = PemakaianDana::findOrFail($id);

        // Ambil relasi dana terkait
        $dana = $pemakaian->dana;

        // Tambahkan kembali jumlah pemakaian ke dana yang bersangkutan
        if ($dana) {
            $dana->jumlah_dana += $pemakaian->jumlah_pemakaian;
            $dana->save();
        }

        // Hapus file bukti jika ada
        if ($pemakaian->file_bukti) {
            Storage::disk('public')->delete($pemakaian->file_bukti);
        }

        // Hapus data pemakaian
        $pemakaian->delete();

        return redirect()->route('pemakaiandanas.index')->with('success', 'Kegiatan ini berhasil dihapus, Dana telah dikembalikan.');
    }

}

