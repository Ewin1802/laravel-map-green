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

                        <div class="form-group">
                            <label>Pilih Dana</label>
                            <select name="dana_id" id="danaSelect" class="form-control select2 @error('dana_id') is-invalid @enderror">
                                <option value="">Pilih Dana</option>
                                @foreach ($danas as $dana)
                                    <option value="{{ $dana->id }}"
                                        data-jumlah="{{ $dana->jumlah_dana }}"
                                        data-desk="{{ $dana->rekbelanja->desk_belanja ?? '' }}"
                                        data-jenis="{{ $dana->rekbelanja->jenis_belanja ?? '' }}">
                                        {{ $dana->kegiatan->desk_kegiatan ?? 'N/A' }}
                                        - {{ $dana->subkegiatan->desk_subkegiatan ?? '' }}
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
                            <select name="desk_belanja" id="deskBelanjaSelect" class="form-control select2 @error('desk_belanja') is-invalid @enderror">
                                <option value="">-- Pilih Deskripsi Belanja --</option>
                                @foreach($rekbelanjas as $rek)
                                    <option value="{{ $rek->desk_belanja }}"
                                        data-jenis="{{ $rek->jenis_belanja }}"
                                        {{ old('desk_belanja', $pemakaianDana->desk_belanja ?? '') == $rek->desk_belanja ? 'selected' : '' }}>
                                        {{ $rek->desk_belanja }}
                                    </option>
                                @endforeach
                            </select>
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


                        <div class="form-group">
                            <label>Jumlah Pemakaian (Rp)</label>
                            <input type="text" id="jumlahPemakaianFormatted" class="form-control @error('jumlah_pemakaian') is-invalid @enderror" placeholder="Contoh: 1.000.000">
                            <input type="hidden" name="jumlah_pemakaian" id="jumlahPemakaian">
                            @error('jumlah_pemakaian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Uraian Pemakaian Dana</label>
                            <input type="text" name="uraian" class="form-control @error('uraian') is-invalid @enderror" placeholder="Contoh: Pembelian alat tulis">
                            @error('uraian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>No. Bukti (SP2D/SPM/SPP/dll)</label>
                            <input type="text" name="no_bukti" class="form-control @error('no_bukti') is-invalid @enderror" placeholder="Contoh: 01/SP2D/2025">
                            @error('no_bukti')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Tanggal Bukti</label>
                            <input type="date" name="tgl_bukti" class="form-control @error('tgl_bukti') is-invalid @enderror" required>
                            @error('tgl_bukti')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Upload Bukti (PDF)</label>
                            <input type="file" name="file_bukti" class="form-control-file @error('file_bukti') is-invalid @enderror" accept="application/pdf">
                            @error('file_bukti')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Hanya file PDF. Maksimal 10 MB.</small>
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

        $('#danaSelect').on('change', function () {
            const selected = $(this).find(':selected');
            const jumlah = selected.data('jumlah');
            const desk = selected.data('desk');
            const jenis = selected.data('jenis');

            $('#jumlahDanaDisplay').val(jumlah ? 'Rp ' + parseInt(jumlah).toLocaleString('id-ID') : '');
            $('#deskBelanja').val(desk || '');
            $('#jenisBelanja').val(jenis || '');
        });

        $('#jumlahPemakaianFormatted').on('input', function () {
            let value = $(this).val().replace(/[^\d]/g, '');
            let formatted = new Intl.NumberFormat('id-ID').format(value);
            $(this).val(formatted);
            $('#jumlahPemakaian').val(value);
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('.select2').select2();

        // Jika deskripsi belanja dipilih, isi jenis belanja otomatis
        $('#deskBelanjaSelect').on('change', function () {
            const jenis = $(this).find(':selected').data('jenis') || '';
            $('#jenisBelanja').val(jenis);
        });
    });
</script>
@endpush
