@extends('layouts.app')

@section('title', 'Laporan Realisasi')

<link rel="icon" href="{{ asset('img/logo_pemda.png') }}" type="image/png">

@push('style')
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <style>
        .table th, .table td {
            vertical-align: middle !important;
        }
        .border-top-subkegiatan td {
        border-top: 2px solid #000 !important;
    }
    </style>
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan Realisasi</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    @include('layouts.alert')
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>Data Realisasi Pemakaian Dana</h4>
                            <a href="{{ route('laporan.realisasi.print') }}" target="_blank" class="btn btn-outline-primary"><i class="fas fa-print"></i> Cetak Laporan</a>
                        </div>
                        <div class="card-body">
                            <div class="float-right mb-3">
                                <form method="GET" action="{{ route('laporan.realisasi') }}">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Cari kode subkegiatan" name="search" value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-danger"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="clearfix"></div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Kode</th>
                                            <th>Subkegiatan</th>
                                            <th>Total Anggaran</th>
                                            <th>Kuota / Bulan</th>
                                            <th>Sisa Anggaran</th>
                                            <th>Realisasi</th>
                                            <th>No. Bukti</th>
                                            <th>Tgl Bukti</th>
                                            <th>Jumlah Pemakaian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $grandTotalAnggaran = 0;
                                            $grandSisa = 0;
                                            $grandRealisasi = 0;
                                            $grandKuota = 0;
                                        @endphp
                                        @php $prevKode = null; @endphp

                                        @foreach ($danas as $dana)
                                            @php
                                                $pemakaians = $dana->pemakaianDanas;
                                                $totalPemakaian = $pemakaians->sum('jumlah_pemakaian');
                                                // $totalAnggaran = $dana->jumlah_dana;
                                                $totalAnggaran = $dana->jumlah_dana + $totalPemakaian;
                                                $sisaAnggaran = $totalAnggaran - $totalPemakaian;
                                                $kuota = $totalAnggaran / 12;
                                                $persen = $totalAnggaran > 0 ? ($totalPemakaian / $totalAnggaran) * 100 : 0;
                                                $rowspan = max($pemakaians->count(), 1);
                                                $grandTotalAnggaran += $totalAnggaran;
                                                $grandSisa += $sisaAnggaran;
                                                $grandRealisasi += $totalPemakaian;
                                                $grandKuota += $kuota;

                                                // Cek apakah subkegiatan baru
                                                $isNewSubkegiatan = $dana->subkegiatan->kode_subkegiatan !== $prevKode;
                                                $prevKode = $dana->subkegiatan->kode_subkegiatan;
                                            @endphp

                                            @if ($pemakaians->count())
                                                @foreach ($pemakaians as $index => $pemakaian)
                                                    <tr class="{{ $index === 0 && $isNewSubkegiatan ? 'border-top-subkegiatan' : '' }}">
                                                        @if ($index === 0)
                                                            <td  rowspan="{{ $rowspan }}">{{ $dana->subkegiatan->kode_subkegiatan ?? '-' }}</td>
                                                            <td rowspan="{{ $rowspan }}">{{ $dana->subkegiatan->desk_subkegiatan ?? '-' }}</td>
                                                            <td rowspan="{{ $rowspan }}">Rp{{ number_format($totalAnggaran, 0, ',', '.') }}</td>
                                                            <td rowspan="{{ $rowspan }}">Rp{{ number_format($kuota, 0, ',', '.') }}</td>
                                                            <td rowspan="{{ $rowspan }}">Rp{{ number_format($sisaAnggaran, 0, ',', '.') }}</td>
                                                            <td rowspan="{{ $rowspan }}">
                                                                Rp{{ number_format($totalPemakaian, 0, ',', '.') }}<br>
                                                                <small>({{ number_format($persen, 2) }}%)</small>
                                                            </td>
                                                        @endif
                                                        <td style="white-space: normal; word-break: break-word;">{{ $pemakaian->no_bukti }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($pemakaian->tgl_bukti)->format('d-m-Y') }}</td>
                                                        <td>Rp{{ number_format($pemakaian->jumlah_pemakaian, 0, ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr class="{{ $isNewSubkegiatan ? 'border-top-subkegiatan' : '' }}">
                                                    <td>{{ $dana->subkegiatan->kode_subkegiatan ?? '-' }}</td>
                                                    <td>{{ $dana->subkegiatan->desk_subkegiatan ?? '-' }}</td>
                                                    <td>Rp{{ number_format($totalAnggaran, 0, ',', '.') }}</td>
                                                    <td>Rp{{ number_format($kuota, 0, ',', '.') }}</td>
                                                    <td>Rp{{ number_format($totalAnggaran, 0, ',', '.') }}</td>
                                                    <td>Rp0<br><small>(0%)</small></td>
                                                    <td colspan="3" class="text-muted text-center">Belum digunakan</td>
                                                </tr>
                                            @endif
                                            @endforeach

                                        {{-- Baris total --}}
                                        @php
                                            $persenTotal = $grandTotalAnggaran > 0 ? ($grandRealisasi / $grandTotalAnggaran) * 100 : 0;
                                        @endphp
                                        <tr style="background-color: #f7f7f7; font-weight: bold;">
                                            <td colspan="2" class="text-center">TOTAL</td>
                                            <td>Rp{{ number_format($grandTotalAnggaran, 0, ',', '.') }}</td>
                                            <td>Rp{{ number_format($grandKuota, 0, ',', '.') }}</td>
                                            <td>Rp{{ number_format($grandSisa, 0, ',', '.') }}</td>
                                            <td>
                                                Rp{{ number_format($grandRealisasi, 0, ',', '.') }}<br>
                                                <small>({{ number_format($persenTotal, 2) }}%)</small>
                                            </td>
                                            <td colspan="3"></td>
                                        </tr>
                                    </tbody>
                                </table>


                            </div>

                            <div class="float-right">
                                {{ $danas->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
