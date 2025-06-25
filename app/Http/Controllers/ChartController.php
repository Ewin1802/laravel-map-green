<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dana;
use App\Models\PemakaianDana;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class ChartController extends Controller
{
    public function index()
    {
        $tahun = session('tahun');

        $danas = Dana::with(['pemakaianDanas', 'subkegiatan', 'rekbelanja'])
            ->where('thn_anggaran', $tahun)
            ->get();

        $grouped = $danas->groupBy('subkegiatan_id');

        $data = [];

        foreach ($grouped as $subkegiatanId => $items) {
            $subkegiatanNama = optional($items->first()->subkegiatan)->desk_subkegiatan ?? 'Subkegiatan Tidak Diketahui';


            $deskBelanjas = [];

            foreach ($items->groupBy('rekbelanja_id') as $rekId => $groupRek) {
                $deskNama = optional($groupRek->first()->rekbelanja)->desk_belanja ?? 'Belanja Tidak Diketahui';

                $totalDana = 0;
                $totalPemakaian = 0;

                foreach ($groupRek as $dana) {
                    $pemakaian = $dana->pemakaianDanas->sum('jumlah_pemakaian');
                    $totalPemakaian += $pemakaian;
                    $totalDana += $dana->jumlah_dana + $pemakaian; // seperti di print()
                }

                $deskBelanjas[] = [
                    'desk_belanja' => $deskNama,
                    'total_pemakaian' => $totalPemakaian,
                    'sisa_anggaran' => $totalDana - $totalPemakaian,
                ];
            }

            $data[] = [
                'subkegiatan' => $subkegiatanNama,
                'total_dana' => array_sum(array_map(fn($d) => $d['total_pemakaian'] + $d['sisa_anggaran'], $deskBelanjas)),
                'jumlah_dana' => array_sum(array_map(fn($d) => $d['sisa_anggaran'], $deskBelanjas)),
                'pemakaian' => $deskBelanjas,
            ];
        }

        $totalUser = User::where('role', 'user')->count();

        return view('pages.dashboard', compact('data', 'tahun', 'totalUser'));
    }


}
