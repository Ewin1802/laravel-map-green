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
        $tahun = session('tahun'); // ambil tahun dari session, bukan tahun sekarang

        $totalDana = DB::table('danas')
            ->whereYear('created_at', $tahun)
            ->select('subkegiatan_id', DB::raw('SUM(jumlah_dana) as total_dana'))
            ->groupBy('subkegiatan_id')
            ->get()
            ->keyBy('subkegiatan_id');

        $totalPemakaian = DB::table('pemakaian_danas')
            ->join('danas', 'pemakaian_danas.dana_id', '=', 'danas.id')
            ->whereYear('pemakaian_danas.created_at', $tahun)
            ->select('danas.subkegiatan_id', DB::raw('SUM(pemakaian_danas.jumlah_pemakaian) as total_pemakaian'))
            ->groupBy('danas.subkegiatan_id')
            ->get()
            ->keyBy('subkegiatan_id');

        $chartData = $totalDana->map(function ($item, $subkegiatan_id) use ($totalPemakaian) {
            return [
                'total_dana' => (int) $item->total_dana,
                'total_pemakaian' => (int) ($totalPemakaian[$subkegiatan_id]->total_pemakaian ?? 0),
            ];
        });

        $subkegiatanNames = \App\Models\Subkegiatan::whereIn('id', $chartData->keys())
            ->pluck('desk_subkegiatan', 'id');

        $totalUser = User::where('role', 'user')->count();

        $jenisBelanja = DB::table('pemakaian_danas')
    ->join('danas', 'pemakaian_danas.dana_id', '=', 'danas.id')
    ->whereYear('pemakaian_danas.created_at', $tahun)
    ->select('pemakaian_danas.jenis_belanja', DB::raw('SUM(pemakaian_danas.jumlah_pemakaian) as total'))
    ->groupBy('pemakaian_danas.jenis_belanja')
    ->get();

// Hitung total pemakaian semua jenis belanja
$totalPemakaianSemuaJenis = $jenisBelanja->sum('total');

// Persiapkan data belanjaChart dengan persentase
$belanjaChart = [
    'labels' => [],
    'data' => [],
];

foreach ($jenisBelanja as $item) {
    $belanjaChart['labels'][] = $item->jenis_belanja ?? 'Tidak Diketahui';
    $belanjaChart['data'][] = $item->total;
}

        // dd($chartData);


        return view('pages.dashboard', compact('chartData', 'subkegiatanNames', 'tahun', 'totalUser', 'belanjaChart'));
    }

}
