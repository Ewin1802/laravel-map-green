@extends('layouts.app')

@section('title', 'Input Realisasi')

<link rel="icon" href="{{ asset('img/logo_pemda.png') }}" type="image/png">

@push('style')
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Form Input Realisasi</h1>
        </div>

        <div class="section-body">

            <div class="card">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible show fade">
                        <div class="alert-body">
                            <button class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                            <strong>Error!</strong> {{ session('error') }}
                        </div>
                    </div>
                @endif

                <form action="{{ route('pemakaiandanas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header">
                        <h4>Isi Form di bawah ini</h4>
                    </div>
                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 pl-3">
                                    @foreach ($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Jenis input --}}
                        <div class="form-group">
                            <label class="d-block">Jenis Input</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="mode" value="biasa" class="selectgroup-input mode-radio"
                                        {{ old('mode', 'biasa') !== 'sppd' ? 'checked' : '' }}>
                                    <span class="selectgroup-button">Realisasi Biasa</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="mode" value="sppd" class="selectgroup-input mode-radio"
                                        {{ old('mode') === 'sppd' ? 'checked' : '' }}>
                                    <span class="selectgroup-button">SPPD (beberapa pelaksana, 1 surat tugas)</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Pilih Dana</label>
                            <select name="dana_id" id="danaSelect" class="form-control select2 @error('dana_id') is-invalid @enderror">
                                <option value="">Pilih Dana</option>
                                @foreach ($danas as $dana)
                                    <option value="{{ $dana->id }}"
                                        data-jumlah="{{ $dana->jumlah_dana }}"
                                        data-desk="{{ $dana->rekbelanja->desk_belanja ?? '' }}"
                                        data-jenis="{{ $dana->rekbelanja->jenis_belanja ?? '' }}"
                                        {{ old('dana_id') == $dana->id ? 'selected' : '' }}>
                                        {{ $dana->subkegiatan->desk_subkegiatan ?? '-' }}
                                        - {{ $dana->rekbelanja->desk_belanja ?? '-' }}
                                        - Rp{{ number_format($dana->jumlah_dana, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dana_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Jumlah Dana Tersedia</label>
                            <input type="text" id="jumlahDanaDisplay" class="form-control" readonly>
                        </div>

                        {{-- Deskripsi Belanja --}}
                        <div class="form-group">
                            <label>Deskripsi Belanja</label>
                            <input type="text" name="desk_belanja" id="deskBelanja" class="form-control @error('desk_belanja') is-invalid @enderror" readonly>
                            @error('desk_belanja')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Jenis Belanja --}}
                        <div class="form-group">
                            <label>Jenis Belanja</label>
                            <input type="text" name="jenis_belanja" id="jenisBelanja" class="form-control @error('jenis_belanja') is-invalid @enderror"
                                value="{{ old('jenis_belanja', $pemakaianDana->jenis_belanja ?? '') }}" readonly>

                            @error('jenis_belanja')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="form-group mode-biasa">
                            <label>Jumlah Pemakaian (Rp)</label>
                            <input type="text" id="jumlahPemakaianFormatted" class="form-control @error('jumlah_pemakaian') is-invalid @enderror" placeholder="Contoh: 1.000.000">
                            <input type="hidden" name="jumlah_pemakaian" id="jumlahPemakaian">
                            @error('jumlah_pemakaian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label id="labelUraian">Uraian Pemakaian Dana</label>
                            <input type="text" name="uraian" id="uraianInput" value="{{ old('uraian') }}" class="form-control @error('uraian') is-invalid @enderror" placeholder="Contoh: Pembelian alat tulis">
                            <small class="form-text text-muted mode-sppd">Berlaku untuk semua pelaksana. Nama pelaksana otomatis ditambahkan di uraian tiap orang.</small>
                            @error('uraian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>No. Bukti (SP2D/SPM/SPP/dll)</label>
                            <input type="text" name="no_bukti" value="{{ old('no_bukti') }}" class="form-control @error('no_bukti') is-invalid @enderror" placeholder="Contoh: 01/SP2D/2025">
                            @error('no_bukti')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mode-sppd">
                            <label>Nomor Surat Tugas</label>
                            <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}" class="form-control @error('nomor_surat') is-invalid @enderror" placeholder="Contoh: 094/ST/IV/2025">
                            @error('nomor_surat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label id="labelTanggal">Tanggal Bukti</label>
                            <input type="date" name="tgl_bukti" value="{{ old('tgl_bukti') }}" class="form-control @error('tgl_bukti') is-invalid @enderror" required>
                            @error('tgl_bukti')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label id="labelFile">Upload Bukti (PDF)</label>
                            <input type="file" name="file_bukti" class="form-control-file @error('file_bukti') is-invalid @enderror" accept="application/pdf">
                            @error('file_bukti')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Hanya file PDF. Maksimal 10 MB.<span class="mode-sppd"> Cukup diunggah sekali untuk semua pelaksana.</span></small>
                        </div>

                        {{-- Daftar pelaksana SPPD --}}
                        <div class="form-group mode-sppd">
                            <label>Pelaksana Perjalanan Dinas</label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm mb-2" id="tabelPelaksana">
                                    <thead>
                                        <tr>
                                            <th style="width:50px">No</th>
                                            <th>Nama Pelaksana</th>
                                            <th style="width:240px">Biaya (Rp)</th>
                                            <th style="width:60px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $oldPelaksana = old('pelaksana', [['nama' => '', 'jumlah' => '']]);
                                        @endphp
                                        @foreach ($oldPelaksana as $i => $p)
                                            <tr class="baris-pelaksana">
                                                <td class="no-urut align-middle text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <input type="text" name="pelaksana[{{ $i }}][nama]" value="{{ $p['nama'] ?? '' }}"
                                                        class="form-control input-pelaksana @error('pelaksana.'.$i.'.nama') is-invalid @enderror" placeholder="Nama pegawai">
                                                </td>
                                                <td>
                                                    <input type="text" name="pelaksana[{{ $i }}][jumlah]"
                                                        value="{{ ($p['jumlah'] ?? '') !== '' ? number_format((int) $p['jumlah'], 0, ',', '.') : '' }}"
                                                        class="form-control input-pelaksana input-rupiah @error('pelaksana.'.$i.'.jumlah') is-invalid @enderror" placeholder="0">
                                                </td>
                                                <td class="text-center align-middle">
                                                    <button type="button" class="btn btn-sm btn-danger btn-hapus-pelaksana" title="Hapus"><i class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" class="text-right">Total</th>
                                            <th id="totalPelaksana">Rp 0</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="btnTambahPelaksana">
                                <i class="fas fa-plus"></i> Tambah Pelaksana
                            </button>
                            <small id="peringatanTotal" class="form-text text-danger d-none">Total biaya melebihi jumlah dana tersedia.</small>
                        </div>

                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();

        // Saat pilihan dana berubah
        $('#danaSelect').on('change', function () {
            const selected = $(this).find(':selected');

            const jumlah = selected.data('jumlah');
            const desk = selected.data('desk');
            const jenis = selected.data('jenis');

            // Tampilkan jumlah dana
            $('#jumlahDanaDisplay').val(jumlah ? 'Rp ' + parseInt(jumlah).toLocaleString('id-ID') : '');

            // Isi deskripsi dan jenis belanja secara otomatis (dan readonly)
            $('#deskBelanja').val(desk || '');
            $('#jenisBelanja').val(jenis || '');
        });

        // ===== Mode SPPD (beberapa pelaksana, 1 surat tugas) =====
        function formatRupiah(v) {
            v = String(v || '').replace(/[^\d]/g, '');
            return v ? new Intl.NumberFormat('id-ID').format(v) : '';
        }

        function nomoriUlang() {
            $('#tabelPelaksana tbody tr').each(function (i) {
                $(this).find('.no-urut').text(i + 1);
                $(this).find('input').each(function () {
                    this.name = this.name.replace(/pelaksana\[\d+\]/, 'pelaksana[' + i + ']');
                });
            });
        }

        function hitungTotal() {
            let total = 0;
            $('#tabelPelaksana .input-rupiah').each(function () {
                total += parseInt($(this).val().replace(/[^\d]/g, '') || 0);
            });
            $('#totalPelaksana').text('Rp ' + total.toLocaleString('id-ID'));

            const tersedia = parseInt($('#danaSelect').find(':selected').data('jumlah') || 0);
            $('#peringatanTotal').toggleClass('d-none', !(tersedia && total > tersedia));
        }

        function terapkanMode() {
            const sppd = $('input[name="mode"]:checked').val() === 'sppd';

            $('.mode-sppd').toggle(sppd);
            $('.mode-biasa').toggle(!sppd);

            // input yang tidak dipakai di-disable agar tidak ikut terkirim
            $('.input-pelaksana, input[name="nomor_surat"]').prop('disabled', !sppd);
            $('#jumlahPemakaianFormatted, #jumlahPemakaian').prop('disabled', sppd);

            $('#labelUraian').text(sppd ? 'Uraian Kegiatan Perjalanan Dinas' : 'Uraian Pemakaian Dana');
            $('#uraianInput').attr('placeholder', sppd ? 'Contoh: SPPD LUAR DAERAH - MANADO' : 'Contoh: Pembelian alat tulis');
            $('#labelTanggal').text(sppd ? 'Tanggal Surat Tugas / Bukti' : 'Tanggal Bukti');
            $('#labelFile').text(sppd ? 'Upload Surat Tugas (PDF)' : 'Upload Bukti (PDF)');

            hitungTotal();
        }

        $('.mode-radio').on('change', terapkanMode);

        // Otomatis pindah ke mode SPPD bila dana yang dipilih adalah belanja perjalanan dinas
        $('#danaSelect').on('change', function () {
            const desk = String($(this).find(':selected').data('desk') || '').toLowerCase();
            if (desk.includes('perjalanan dinas')) {
                $('input[name="mode"][value="sppd"]').prop('checked', true);
            }
            terapkanMode();
        });

        $('#btnTambahPelaksana').on('click', function () {
            const baris = $('#tabelPelaksana tbody tr:first').clone();
            baris.find('input').val('').removeClass('is-invalid');
            $('#tabelPelaksana tbody').append(baris);
            nomoriUlang();
            baris.find('input:first').focus();
        });

        $('#tabelPelaksana').on('click', '.btn-hapus-pelaksana', function () {
            if ($('#tabelPelaksana tbody tr').length > 1) {
                $(this).closest('tr').remove();
            } else {
                $(this).closest('tr').find('input').val('');
            }
            nomoriUlang();
            hitungTotal();
        });

        $('#tabelPelaksana').on('input', '.input-rupiah', function () {
            $(this).val(formatRupiah($(this).val()));
            hitungTotal();
        });

        // Tampilkan info dana bila form dibuka ulang setelah gagal validasi
        if ($('#danaSelect').val()) {
            $('#danaSelect').trigger('change');
            @if (old('mode'))
                $('input[name="mode"][value="{{ old('mode') }}"]').prop('checked', true);
            @endif
        }
        terapkanMode();

        // Format input jumlah pemakaian
        $('#jumlahPemakaianFormatted').on('input', function () {
            let value = $(this).val().replace(/[^\d]/g, '');
            let formatted = new Intl.NumberFormat('id-ID').format(value);
            $(this).val(formatted);
            $('#jumlahPemakaian').val(value);
        });
    });
</script>
@endpush

