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
                <th>Sub Kegiatan</th>
                <th>Desk Belanja</th>
                <th>Total Anggaran</th>
                <th>Sisa Anggaran</th>
                <th>Realisasi</th>
                <th>No. Bukti</th>
                <th>Tgl Bukti</th>
                <th>Uraian</th>
                <th>Jenis Belanja</th>
                <th>Jumlah Pemakaian</th>
            </tr>
        </thead>

        <tbody>
            @php
                $grandTotalAnggaran = 0;
                $grandSisaAnggaran = 0;
                $grandRealisasi = 0;

                $groupedBySub = $danas->groupBy(fn($item) => $item->subkegiatan->desk_subkegiatan ?? '-');
            @endphp

            @foreach ($groupedBySub as $subkegiatan => $danasPerSub)
                @php
                    $totalRowsSub = 0;
                    foreach ($danasPerSub as $dana) {
                        $totalRowsSub += max($dana->pemakaianDanas->count(), 1);
                    }
                @endphp

                @php $printedSub = false; @endphp
                @foreach ($danasPerSub as $dana)
                    @php
                        $pemakaians = $dana->pemakaianDanas;
                        $rowspanBelanja = max($pemakaians->count(), 1);

                        $totalPemakaian = $pemakaians->sum('jumlah_pemakaian');
                        $totalAnggaran = $dana->jumlah_dana + $totalPemakaian;
                        $sisaAnggaran = $totalAnggaran - $totalPemakaian;
                        $persen = $totalAnggaran > 0 ? ($totalPemakaian / $totalAnggaran) * 100 : 0;

                        $deskBelanja = $dana->rekbelanja->desk_belanja ?? '-';

                        $grandTotalAnggaran += $totalAnggaran;
                        $grandSisaAnggaran += $sisaAnggaran;
                        $grandRealisasi += $totalPemakaian;
                    @endphp

                    @if ($pemakaians->isEmpty())
                        <tr>
                            @if (!$printedSub)
                                <td rowspan="{{ $totalRowsSub }}">{{ $subkegiatan }}</td>
                                @php $printedSub = true; @endphp
                            @endif
                            <td>{{ $deskBelanja }}</td>
                            <td>Rp{{ number_format($totalAnggaran, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($sisaAnggaran, 0, ',', '.') }}</td>
                            <td>Rp0<br><small>(0%)</small></td>
                            <td colspan="5">-</td>
                        </tr>
                    @else
                        @foreach ($pemakaians as $index => $p)
                            <tr>
                                @if (!$printedSub)
                                    <td rowspan="{{ $totalRowsSub }}">{{ $subkegiatan }}</td>
                                    @php $printedSub = true; @endphp
                                @endif

                                @if ($index == 0)
                                    <td rowspan="{{ $rowspanBelanja }}">{{ $deskBelanja }}</td>
                                    <td rowspan="{{ $rowspanBelanja }}">Rp{{ number_format($totalAnggaran, 0, ',', '.') }}</td>
                                    <td rowspan="{{ $rowspanBelanja }}">Rp{{ number_format($sisaAnggaran, 0, ',', '.') }}</td>
                                    <td rowspan="{{ $rowspanBelanja }}">
                                        Rp{{ number_format($totalPemakaian, 0, ',', '.') }}<br>
                                        <small>({{ number_format($persen, 2) }}%)</small>
                                    </td>
                                @endif

                                <td>{{ $p->no_bukti }}</td>
                                <td>{{ \Carbon\Carbon::parse($p->tgl_bukti)->format('d-m-Y') }}</td>
                                <td>{{ $p->uraian }}</td>
                                <td>{{ $p->jenis_belanja ?? '-' }}</td>
                                <td>Rp{{ number_format($p->jumlah_pemakaian, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            @endforeach

            {{-- Footer Total --}}
            <tr style="background: #e0e0e0; font-weight: bold;">
                <td colspan="2">TOTAL</td>
                <td>Rp{{ number_format($grandTotalAnggaran, 0, ',', '.') }}</td>
                <td>Rp{{ number_format($grandSisaAnggaran, 0, ',', '.') }}</td>
                <td>
                    Rp{{ number_format($grandRealisasi, 0, ',', '.') }}<br>
                    <small>({{ number_format(($grandRealisasi / $grandTotalAnggaran) * 100, 2) }}%)</small>
                </td>
                <td colspan="5"></td>
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
