@extends('layouts.app')

@section('title', 'Edit Realisasi')

<link rel="icon" href="{{ asset('img/logo_pemda.png') }}" type="image/png">

@push('style')
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="section-header">
                <h1>Ubah Realisasi</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active">
                        <strong>No Bukti:</strong> {{ $pemakaianDana->no_bukti ?? '-' }}
                    </div>
                    <div class="breadcrumb-item">
                        <strong>Jumlah:</strong> Rp {{ number_format($pemakaianDana->jumlah_pemakaian, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <div class="card">
                <form action="{{ route('pemakaiandanas.update', $pemakaianDana->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-header">
                        <h4>Form Ubah Realisasi</h4>
                    </div>
                    <div class="card-body">

                        {{-- Dana (readonly select jika tidak boleh diubah) --}}
                        <div class="form-group">
                            <label>Pilih Dana</label>
                            <select name="dana_id" id="dana_id" class="form-control select2" disabled>
                                @foreach ($danas as $dana)
                                    <option value="{{ $dana->id }}"
                                        data-desk="{{ $dana->rekbelanja->desk_belanja ?? '' }}"
                                        data-jenis="{{ $dana->rekbelanja->jenis_belanja ?? '' }}"
                                        {{ $pemakaianDana->dana_id == $dana->id ? 'selected' : '' }}>
                                        {{ $dana->kegiatan->desk_kegiatan ?? '' }} - {{ $dana->subkegiatan->desk_subkegiatan ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="dana_id" value="{{ $pemakaianDana->dana_id }}">
                        </div>

                        {{-- Deskripsi Belanja --}}
                        <div class="form-group">
                            <label>Deskripsi Belanja</label>
                            <select name="desk_belanja" id="deskBelanjaSelect" class="form-control select2 @error('desk_belanja') is-invalid @enderror">
                                <option value="">-- Pilih Deskripsi Belanja --</option>
                                @foreach($rekbelanjas as $rek)
                                    <option value="{{ $rek->desk_belanja }}"
                                        data-jenis="{{ $rek->jenis_belanja }}"
                                        {{ old('desk_belanja', $pemakaianDana->desk_belanja) == $rek->desk_belanja ? 'selected' : '' }}>
                                        {{ $rek->desk_belanja }}
                                    </option>
                                @endforeach
                            </select>
                            @error('desk_belanja')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Jenis Belanja (readonly) --}}
                        <div class="form-group">
                            <label>Jenis Belanja</label>
                            <input type="text" readonly id="jenisBelanja" class="form-control @error('jenis_belanja') is-invalid @enderror"
                                value="{{ old('jenis_belanja', $pemakaianDana->jenis_belanja) }}">
                            <input type="hidden" name="jenis_belanja" id="jenisBelanjaHidden" value="{{ old('jenis_belanja', $pemakaianDana->jenis_belanja) }}">
                            @error('jenis_belanja')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>



                        <div class="form-group">
                            <label>Uraian (Keterangan terkait Realisasi Anggaran digunakan untuk apa)</label>
                            <input type="text" name="uraian" class="form-control @error('uraian') is-invalid @enderror"
                                value="{{ old('uraian', $pemakaianDana->uraian) }}" required>
                            @error('uraian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>No Bukti</label>
                            <input type="text" name="no_bukti" class="form-control @error('no_bukti') is-invalid @enderror"
                                value="{{ old('no_bukti', $pemakaianDana->no_bukti) }}" required>
                            @error('no_bukti')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Anggaran Tersedia</label>
                            <input type="text" class="form-control" value="{{ number_format($sisaDana, 0, ',', '.') }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Jumlah Pemakaian</label>
                            <input type="text" id="jumlah_pemakaian" name="jumlah_pemakaian"
                                class="form-control @error('jumlah_pemakaian') is-invalid @enderror"
                                value="{{ old('jumlah_pemakaian', number_format($pemakaianDana->jumlah_pemakaian, 0, ',', '.')) }}"
                                required>
                            @error('jumlah_pemakaian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Tanggal Bukti</label>
                            <input type="date" name="tgl_bukti" class="form-control @error('tgl_bukti') is-invalid @enderror"
                                value="{{ old('tgl_bukti', \Carbon\Carbon::parse($pemakaianDana->tgl_bukti)->format('Y-m-d')) }}"
                                required>
                            @error('tgl_bukti')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>File Bukti (PDF)</label>
                            <input type="file" name="file_bukti" accept="application/pdf" class="form-control-file @error('file_bukti') is-invalid @enderror">
                            @if($pemakaianDana->file_bukti)
                                <p class="mt-2">File saat ini: <a href="{{ asset('storage/' . $pemakaianDana->file_bukti) }}" target="_blank">Lihat File</a></p>
                            @endif
                            @error('file_bukti')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary mr-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>

                </form>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();

        // Saat pertama load, jika selected ada, update jenis belanja
        let selectedOption = $('#deskBelanjaSelect').find('option:selected');
        $('#jenisBelanja').val(selectedOption.data('jenis'));

        // Ketika deskripsi belanja diubah
        $('#deskBelanjaSelect').on('change', function () {
            let selected = $(this).find(':selected');
            let jenis = selected.data('jenis') || '';
            $('#jenisBelanja').val(jenis);
        });
    });
</script>
@endpush
