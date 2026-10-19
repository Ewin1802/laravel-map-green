<?php

namespace App\Http\Controllers;

use App\Models\PemakaianDana;
use App\Models\Dana;
use App\Models\Rekbelanja;
use App\Models\SuratTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class PemakaianDanaController extends Controller
{

    public function index(Request $request)
    {
        $tahun = session('tahun');
        $search = $request->search;

        $pemakaians = PemakaianDana::with(['dana.subkegiatan', 'suratTugas'])
            ->whereYear('tgl_bukti', $tahun)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('no_bukti', 'like', '%' . $search . '%')
                    ->orWhere('nama_pelaksana', 'like', '%' . $search . '%')
                    ->orWhereHas('suratTugas', function ($q2) use ($search) {
                        $q2->where('nomor_surat', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('dana.subkegiatan', function ($q2) use ($search) {
                        $q2->where('desk_subkegiatan', 'like', '%' . $search . '%');
                    });
                });
            })
            ->orderBy('id')
            ->paginate(10);

        return view('pages.pemakaiandanas.index', compact('pemakaians'));
    }

    public function create()
    {
        $danas = Dana::with(['subkegiatan', 'rekbelanja']) // Hapus 'kegiatan'
                    ->where('available', 1)
                    ->get();

        $rekbelanjas = Rekbelanja::all();

        return view('pages.pemakaiandanas.create', compact('danas', 'rekbelanjas'));
    }

    public function edit($id)
    {
        $pemakaianDana = PemakaianDana::with('suratTugas.pelaksana')->findOrFail($id);
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
        $pemakaianDana = PemakaianDana::with('suratTugas')->findOrFail($id);
        $suratTugas = $pemakaianDana->suratTugas;

        $rules = [
            'uraian' => 'required|string|max:255',
            // 'no_bukti' => 'required|string|max:100',
            'jumlah_pemakaian' => 'required|string',
            'tgl_bukti' => 'required|date',
            'file_bukti' => 'nullable|file|mimes:pdf|max:10240',
            'desk_belanja' => 'required|string',
            'jenis_belanja' => 'required|string',
        ];
        if ($suratTugas) {
            $rules['nama_pelaksana'] = 'required|string|max:255';
            $rules['nomor_surat'] = 'nullable|string|max:255';
        }
        $request->validate($rules);

        $dana = Dana::findOrFail($pemakaianDana->dana_id);

        $jumlahBaru = (int) str_replace('.', '', $request->jumlah_pemakaian);

        $totalPemakaianLain = PemakaianDana::where('dana_id', $pemakaianDana->dana_id)
            ->where('id', '!=', $pemakaianDana->id)
            ->sum('jumlah_pemakaian');

        $totalPemakaianSetelahUpdate = $totalPemakaianLain + $jumlahBaru;

        if ($totalPemakaianSetelahUpdate > $dana->jumlah_dana) {
            return back()->withErrors(['jumlah_pemakaian' => 'Jumlah pemakaian melebihi anggaran tersedia.'])->withInput();
        }

        $user = Auth::user();

        DB::transaction(function () use ($request, $pemakaianDana, $suratTugas, $jumlahBaru, $user) {
            $fileLama = $pemakaianDana->file_bukti;
            $fileBaru = null;

            if ($request->hasFile('file_bukti')) {
                $fileBaru = $this->simpanPdf($request->file('file_bukti'), $request->uraian);
                $pemakaianDana->file_bukti = $fileBaru;
            }

            $pemakaianDana->no_bukti = $request->no_bukti;
            $pemakaianDana->jumlah_pemakaian = $jumlahBaru;
            $pemakaianDana->tgl_bukti = $request->tgl_bukti;
            $pemakaianDana->desk_belanja = $request->desk_belanja;
            $pemakaianDana->jenis_belanja = $request->jenis_belanja;
            $pemakaianDana->user_name = $user->name;
            $pemakaianDana->user_unit = $user->unit;
            $pemakaianDana->thn_anggaran = now()->year;

            if ($suratTugas) {
                // Data bersama (tanggal, no bukti, nomor & file surat tugas, uraian kegiatan)
                // diperbarui sekali dan berlaku untuk semua pelaksana di surat tugas ini.
                $suratTugas->uraian = $request->uraian;
                $suratTugas->nomor_surat = $request->nomor_surat;
                $suratTugas->no_bukti = $request->no_bukti;
                $suratTugas->tgl_bukti = $request->tgl_bukti;
                if ($fileBaru) {
                    $suratTugas->file_surat = $fileBaru;
                }
                $suratTugas->save();

                $pemakaianDana->nama_pelaksana = $request->nama_pelaksana;
                $pemakaianDana->uraian = $this->uraianPelaksana($request->uraian, $request->nama_pelaksana);
                $pemakaianDana->save();

                foreach ($suratTugas->pelaksana()->where('id', '!=', $pemakaianDana->id)->get() as $lain) {
                    $lain->no_bukti = $request->no_bukti;
                    $lain->tgl_bukti = $request->tgl_bukti;
                    $lain->uraian = $this->uraianPelaksana($request->uraian, $lain->nama_pelaksana);
                    if ($fileBaru) {
                        $lain->file_bukti = $fileBaru;
                    }
                    $lain->save();
                }
            } else {
                $pemakaianDana->uraian = $request->uraian;
                $pemakaianDana->save();
            }

            if ($fileBaru && $fileLama) {
                $this->hapusFileJikaTidakDipakai($fileLama);
            }
        });

        return redirect()->route('pemakaiandanas.index')->with('success', 'Data pemakaian dana berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        if ($request->input('mode') === 'sppd') {
            return $this->storeSppd($request);
        }

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
                $filePath = $this->simpanPdf($request->file('file_bukti'), $request->uraian);
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Terjadi kesalahan saat menyimpan pemakaian dana:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
        }
    }

    /**
     * SPPD dengan beberapa pelaksana: tanggal, nomor & file surat tugas diinput sekali,
     * lalu dibuat satu baris realisasi per pelaksana.
     */
    protected function storeSppd(Request $request)
    {
        // Ubah "1.500.000" -> 1500000 sebelum validasi
        $pelaksana = collect($request->input('pelaksana', []))
            ->map(function ($p) {
                return [
                    'nama' => trim($p['nama'] ?? ''),
                    'jumlah' => preg_replace('/[^\d]/', '', (string) ($p['jumlah'] ?? '')),
                ];
            })
            ->filter(fn ($p) => $p['nama'] !== '' || $p['jumlah'] !== '')
            ->values()
            ->all();
        $request->merge(['pelaksana' => $pelaksana]);

        $request->validate([
            'dana_id' => 'required|exists:danas,id',
            'uraian' => 'required|string|max:255',
            'nomor_surat' => 'required|string|max:255',
            'tgl_bukti' => 'required|date',
            'file_bukti' => 'nullable|file|mimes:pdf|max:10240',
            'desk_belanja' => 'required|string',
            'jenis_belanja' => 'required|string',
            'pelaksana' => 'required|array|min:1',
            'pelaksana.*.nama' => 'required|string|max:255',
            'pelaksana.*.jumlah' => 'required|numeric|min:1',
        ], [
            'nomor_surat.required' => 'Nomor surat tugas wajib diisi.',
            'pelaksana.required' => 'Tambahkan minimal satu pelaksana.',
            'pelaksana.*.nama.required' => 'Nama pelaksana wajib diisi.',
            'pelaksana.*.jumlah.required' => 'Biaya pelaksana wajib diisi.',
            'pelaksana.*.jumlah.min' => 'Biaya pelaksana harus lebih dari 0.',
        ]);

        \Log::info('Data SPPD kolektif diterima:', $request->except('file_bukti'));

        $user = Auth::user();
        $dana = Dana::findOrFail($request->dana_id);
        $total = array_sum(array_map(fn ($p) => (int) $p['jumlah'], $pelaksana));

        if ($total > $dana->jumlah_dana) {
            return back()->with('error', 'Total biaya seluruh pelaksana (Rp' . number_format($total, 0, ',', '.') .
                ') melebihi dana yang tersedia (Rp' . number_format($dana->jumlah_dana, 0, ',', '.') . ').')->withInput();
        }

        $filePath = null;
        try {
            if ($request->hasFile('file_bukti')) {
                $filePath = $this->simpanPdf($request->file('file_bukti'), $request->uraian);
            }

            DB::transaction(function () use ($request, $pelaksana, $user, $dana, $total, $filePath) {
                $surat = SuratTugas::create([
                    'dana_id' => $dana->id,
                    'nomor_surat' => $request->nomor_surat,
                    'uraian' => $request->uraian,
                    'no_bukti' => $request->no_bukti,
                    'tgl_bukti' => $request->tgl_bukti,
                    'file_surat' => $filePath,
                    'user_name' => $user->name,
                    'user_unit' => $user->unit,
                    'thn_anggaran' => now()->year,
                ]);

                foreach ($pelaksana as $p) {
                    PemakaianDana::create([
                        'dana_id' => $dana->id,
                        'surat_tugas_id' => $surat->id,
                        'uraian' => $this->uraianPelaksana($request->uraian, $p['nama']),
                        'nama_pelaksana' => $p['nama'],
                        'no_bukti' => $request->no_bukti,
                        'jumlah_pemakaian' => (int) $p['jumlah'],
                        'file_bukti' => $filePath,
                        'tgl_bukti' => $request->tgl_bukti,
                        'desk_belanja' => $request->desk_belanja,
                        'jenis_belanja' => $request->jenis_belanja,
                        'user_name' => $user->name,
                        'user_unit' => $user->unit,
                        'thn_anggaran' => now()->year,
                    ]);
                }

                $dana->jumlah_dana -= $total;
                if ($dana->jumlah_dana <= 0) {
                    $dana->available = 0;
                }
                $dana->save();
            });
        } catch (\Exception $e) {
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            \Log::error('Terjadi kesalahan saat menyimpan SPPD kolektif:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data SPPD.')->withInput();
        }

        return redirect()->route('pemakaiandanas.index')
            ->with('success', 'SPPD berhasil disimpan untuk ' . count($pelaksana) . ' pelaksana.');
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

        $file = $pemakaian->file_bukti;
        $suratTugasId = $pemakaian->surat_tugas_id;

        // Hapus data pemakaian
        $pemakaian->delete();

        // Hapus surat tugas jika sudah tidak ada pelaksana lagi
        if ($suratTugasId && !PemakaianDana::where('surat_tugas_id', $suratTugasId)->exists()) {
            SuratTugas::where('id', $suratTugasId)->delete();
        }

        // Hapus file bukti hanya jika tidak dipakai pelaksana lain (surat tugas bersama)
        if ($file) {
            $this->hapusFileJikaTidakDipakai($file);
        }

        return redirect()->route('pemakaiandanas.index')->with('success', 'Kegiatan ini berhasil dihapus, Dana telah dikembalikan.');
    }

    // ---------------------------------------------------------------------

    protected function simpanPdf($file, $uraian)
    {
        $uraianSlug = Str::slug($uraian, '-');
        $timestamp = now()->format('YmdHi');
        $filename = $uraianSlug . '-' . $timestamp . '-' . Str::random(4) . '.' . $file->getClientOriginalExtension();

        $storedPath = $file->storeAs('public/bukti-pdf', $filename);
        return str_replace('public/', '', $storedPath);
    }

    protected function hapusFileJikaTidakDipakai($path)
    {
        $masihDipakai = PemakaianDana::where('file_bukti', $path)->exists()
            || SuratTugas::where('file_surat', $path)->exists();

        if (!$masihDipakai) {
            Storage::disk('public')->delete($path);
        }
    }

    protected function uraianPelaksana($uraian, $nama)
    {
        return Str::limit($uraian . ' - a.n. ' . $nama, 255, '');
    }

}
