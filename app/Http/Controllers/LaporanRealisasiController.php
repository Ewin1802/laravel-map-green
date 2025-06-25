<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Dana;
use App\Models\PemakaianDana;
use PDF;
use Illuminate\Support\Facades\DB;

class LaporanRealisasiController extends Controller
{
    public function index(Request $request)
    {
        $tahun = session('tahun');

        $query = Dana::with(['subkegiatan', 'pemakaianDanas'])
                    ->whereYear('created_at', $tahun); // filter berdasarkan tahun

        if ($request->filled('search')) {
            $query->whereHas('subkegiatan', function ($q) use ($request) {
                $q->where('kode_subkegiatan', 'like', '%' . $request->search . '%');
            });
        }

        $danas = $query->paginate(10);

        return view('pages.laporanrealisasis.index', compact('danas'));
    }

    public function print()
    {
        // Ambil data dana dengan relasi ke pemakaian_danas dan subkegiatan
        $danas = Dana::with(['pemakaianDanas', 'subkegiatan', 'rekbelanja']) // ← tambah relasi rekbelanja
            ->get()
            ->sortBy(function ($item) {
                return $item->subkegiatan->kode_subkegiatan ?? '';
            });

        // Rekap jenis belanja
        $totalBelanjaModal = 0;
        $totalBelanjaJasa = 0;
        $totalKegiatanModal = 0;
        $totalKegiatanJasa = 0;

        foreach ($danas as $dana) {
            foreach ($dana->pemakaianDanas as $pemakaian) {
                if ($pemakaian->jenis_belanja === 'Modal') {
                    $totalBelanjaModal += $pemakaian->jumlah_pemakaian;
                    $totalKegiatanModal++;
                } elseif ($pemakaian->jenis_belanja === 'Jasa/Habis Pakai' || $pemakaian->jenis_belanja === 'Jasa') {
                    $totalBelanjaJasa += $pemakaian->jumlah_pemakaian;
                    $totalKegiatanJasa++;
                }
            }
        }

        $totalBelanjaKeseluruhan = $totalBelanjaModal + $totalBelanjaJasa;

        // Rekap berdasarkan data_id
        $rekapPerDataId = [];

        foreach ($danas as $dana) {
            $dataId = $dana->data_id;
            $totalPemakaian = $dana->pemakaianDanas->sum('jumlah_pemakaian');
            $totalAnggaran = $dana->jumlah_dana + $totalPemakaian;

            if (!isset($rekapPerDataId[$dataId])) {
                $rekapPerDataId[$dataId] = [
                    'rek_belanja' => $dana->rek_belanja,
                    'desk_belanja' => $dana->desk_belanja,
                    'jumlah' => 0,
                    'kegiatan' => 0,
                ];
            }

            $rekapPerDataId[$dataId]['jumlah'] += $totalAnggaran;
            $rekapPerDataId[$dataId]['kegiatan'] += 1;
        }

        // Kirim data ke view
        return PDF::loadView('pages.laporanrealisasis.print', compact(
            'danas',
            'totalBelanjaModal',
            'totalBelanjaJasa',
            'totalBelanjaKeseluruhan',
            'totalKegiatanModal',
            'totalKegiatanJasa',
            'rekapPerDataId' // <- kirim rekap berdasarkan data_id
        ))->setPaper([0, 0, 595.276, 877.953], 'landscape')
        ->stream('laporan-realisasi.pdf');
    }
}
