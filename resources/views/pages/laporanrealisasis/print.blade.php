<!DOCTYPE html>
<html lang="id">
<head>


    <meta charset="UTF-8">
    <title>Laporan Realisasi Anggaran</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            font-size: 11px;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header img {
            width: 60px;
            height: auto;
            margin-bottom: 5px;
        }

        .header h2, .header h4 {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #444;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .keterangan-table {
            margin-top: 30px;
            width: 45%;
            float: left;
        }

        .ttd {
            margin-top: 30px;
            width: 45%;
            float: right;
            text-align: center;
        }

        .ttd p {
            margin: 2px 0;
        }

        .footer {
            clear: both;
            margin-top: 60px;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('img/logo_pemda.png') }}" alt="Logo">
        <h2>Laporan Realisasi Anggaran</h2>
        <h4>Pos Wakil Bupati</h4>
    </div>

    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Subkegiatan</th>
                <th>Total Anggaran</th>
                <th>Sisa Anggaran</th>
                <th>Realisasi</th>
                <th>No. Bukti</th>
                <th>Tgl Bukti</th>
                <th>Uraian</th>
                <th>Jenis Belanja</th> <!-- Tambahkan ini -->
                <th>Jumlah Pemakaian</th>
            </tr>
        </thead>

        <tbody>
            @php
                $grandTotalAnggaran = 0;
                $grandSisaAnggaran = 0;
                $grandRealisasi = 0;
            @endphp

            @foreach ($danas as $dana)
                @php
                    $totalPemakaian = $dana->pemakaianDanas->sum('jumlah_pemakaian');
                    $totalAnggaran = $dana->jumlah_dana + $totalPemakaian;
                    $sisaAnggaran = $totalAnggaran - $totalPemakaian;
                    $persen = $totalAnggaran > 0 ? ($totalPemakaian / $totalAnggaran) * 100 : 0;

                    $grandTotalAnggaran += $totalAnggaran;
                    $grandSisaAnggaran += $sisaAnggaran;
                    $grandRealisasi += $totalPemakaian;
                @endphp

                @if ($dana->pemakaianDanas->isEmpty())
                    {{-- Kalau belum ada pemakaian --}}
                    <tr>
                        <td>{{ $dana->subkegiatan->kode_subkegiatan ?? '-' }}</td>
                        <td>{{ $dana->subkegiatan->desk_subkegiatan ?? '-' }}</td>
                        <td>Rp{{ number_format($totalAnggaran, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($sisaAnggaran, 0, ',', '.') }}</td>
                        <td>Rp0<br><small>(0%)</small></td>
                        <td colspan="5">-</td> <!-- Ubah colspan jadi 5 karena nambah kolom baru -->
                    </tr>
                @else
                    {{-- Kalau ada pemakaian --}}
                    @foreach ($dana->pemakaianDanas as $pemakaian)
                        <tr>
                            <td>{{ $dana->subkegiatan->kode_subkegiatan ?? '-' }}</td>
                            <td>{{ $dana->subkegiatan->desk_subkegiatan ?? '-' }}</td>
                            <td>Rp{{ number_format($totalAnggaran, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($sisaAnggaran, 0, ',', '.') }}</td>
                            <td>
                                Rp{{ number_format($totalPemakaian, 0, ',', '.') }}<br>
                                <small>({{ number_format($persen, 2) }}%)</small>
                            </td>
                            <td style="word-break: break-word; white-space: normal;">{{ $pemakaian->no_bukti }}</td>
                            <td>{{ \Carbon\Carbon::parse($pemakaian->tgl_bukti)->format('d-m-Y') }}</td>
                            <td>{{ $pemakaian->uraian }}</td>
                            <td>{{ $pemakaian->jenis_belanja ?? '-' }}</td> <!-- Kolom baru jenis belanja -->
                            <td>Rp{{ number_format($pemakaian->jumlah_pemakaian, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach

            {{-- Baris Total --}}
            @php
                $persenTotal = $grandTotalAnggaran > 0 ? ($grandRealisasi / $grandTotalAnggaran) * 100 : 0;
            @endphp
            <tr style="background: #e0e0e0; font-weight: bold;">
                <td colspan="2">TOTAL</td>
                <td>Rp{{ number_format($grandTotalAnggaran, 0, ',', '.') }}</td>
                <td>Rp{{ number_format($grandSisaAnggaran, 0, ',', '.') }}</td>
                <td>
                    Rp{{ number_format($grandRealisasi, 0, ',', '.') }}<br>
                    <small>({{ number_format($persenTotal, 2) }}%)</small>
                </td>
                <td colspan="5"></td> <!-- Ubah colspan jadi 5 supaya rapih -->
            </tr>
        </tbody>

    </table>


    <div class="keterangan-table">
        <table border="1" cellspacing="0" cellpadding="5" style="margin-top: 5px;">
            <thead>
                <tr>
                    <th>Keterangan</th>
                    <th>Jumlah</th>
                    <th>Kegiatan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Belanja Modal</td>
                    <td>Rp{{ number_format($totalBelanjaModal, 0, ',', '.') }}</td>
                    <td>{{ $totalKegiatanModal }}</td>
                </tr>
                <tr>
                    <td>Belanja Jasa / Habis Pakai</td>
                    <td>Rp{{ number_format($totalBelanjaJasa, 0, ',', '.') }}</td>
                    <td>{{ $totalKegiatanJasa }}</td>
                </tr>
                <tr style="background: #f0f0f0; font-weight: bold;">
                    <td>Total</td>
                    <td>Rp{{ number_format($totalBelanjaModal + $totalBelanjaJasa, 0, ',', '.') }}</td>
                    <td>
                        @php
                            $totalKegiatan = $totalKegiatanModal + $totalKegiatanJasa;
                        @endphp

                        @if ($totalKegiatan > 0)
                            {{ $totalKegiatan }}
                        @else
                            Tidak Ada Realisasi
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>


    </div>

    <div class="ttd">
        <p>Boroko, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p>PPTK WAKIL BUPATI</p>
        <br><br><br>
        <p><strong>Ewin Lantapa</strong></p>
        <p>NIP. 19900218 202012 1 005</p>
    </div>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d-m-Y H:i') }}
    </div>

</body>
</html>
