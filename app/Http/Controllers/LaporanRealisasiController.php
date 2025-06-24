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



    // public function print()
    // {
    //     $danas = Dana::with(['pemakaianDanas', 'subkegiatan'])
    //         ->orderBy('id', 'desc')
    //         ->get();

    //     // Hitung total belanja berdasarkan jenis
    //     $totalBelanjaModal = 0;
    //     $totalBelanjaJasa = 0;

    //     foreach ($danas as $dana) {
    //         foreach ($dana->pemakaianDanas as $pemakaian) {
    //             if ($pemakaian->jenis_belanja === 'Modal') {
    //                 $totalBelanjaModal += $pemakaian->jumlah_pemakaian;
    //             } elseif ($pemakaian->jenis_belanja === 'Jasa/Habis Pakai') {
    //                 $totalBelanjaJasa += $pemakaian->jumlah_pemakaian;
    //             }
    //         }
    //     }

    //     // BARU setelah loop, hitung total keseluruhan
    //     $totalBelanjaKeseluruhan = $totalBelanjaModal + $totalBelanjaJasa;

    //     // $pdf = PDF::loadView('pages.laporanrealisasis.print', compact('danas', 'totalBelanjaModal', 'totalBelanjaJasa', 'totalBelanjaKeseluruhan'))
    //     //         ->setPaper('a4', 'landscape');
    //     $pdf = PDF::loadView('pages.laporanrealisasis.print', compact('danas', 'totalBelanjaModal', 'totalBelanjaJasa', 'totalBelanjaKeseluruhan'))
    //       ->setPaper([0, 0, 594.28, 877.74], 'landscape');


    //     return $pdf->stream('laporan-realisasi.pdf');
    // }

    public function print()
    {
        $danas = Dana::with(['pemakaianDanas', 'subkegiatan'])
            ->get()
            ->sortBy(function($item) {
                return $item->subkegiatan->kode_subkegiatan ?? '';
            });

        $totalBelanjaModal = 0;
        $totalBelanjaJasa = 0;
        $totalKegiatanModal = 0;
        $totalKegiatanJasa = 0;

        foreach ($danas as $dana) {
            foreach ($dana->pemakaianDanas as $pemakaian) {
                if ($pemakaian->jenis_belanja === 'Modal') {
                    $totalBelanjaModal += $pemakaian->jumlah_pemakaian;
                    $totalKegiatanModal++;
                } elseif ($pemakaian->jenis_belanja === 'Jasa/Habis Pakai') {
                    $totalBelanjaJasa += $pemakaian->jumlah_pemakaian;
                    $totalKegiatanJasa++;
                }
            }
        }

        $totalBelanjaKeseluruhan = $totalBelanjaModal + $totalBelanjaJasa;

        $pdf = PDF::loadView('pages.laporanrealisasis.print', compact(
            'danas',
            'totalBelanjaModal',
            'totalBelanjaJasa',
            'totalBelanjaKeseluruhan',
            'totalKegiatanModal',
            'totalKegiatanJasa'
        ))->setPaper([0, 0, 595.276, 877.953], 'landscape');

        return $pdf->stream('laporan-realisasi.pdf');
    }







}
