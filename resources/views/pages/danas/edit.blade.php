@extends('layouts.app')

@section('title', 'Edit Dana')

<link rel="icon" href="{{ asset('img/logo_pemda.png') }}" type="image/png">

@push('style')
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush


@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="section-header">
                <h1>Ubah Anggaran</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">{{ $dana->subkegiatan->desk_subkegiatan ?? 'Subkegiatan Tidak Ditemukan' }}</a></div>
                    <div class="breadcrumb-item"><a href="#">Rp {{ number_format($dana->jumlah_dana, 0, ',', '.') }}</a></div>
                </div>
            </div>

            <div class="card">
                <form action="{{ route('danas.update', $dana->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-header">
                        <h4>Form Ubah Anggaran</h4>
                    </div>
                    <div class="card-body">
                        <!-- Program -->
                        <div class="form-group">
                            <label>Program</label>
                            <select name="program_id" class="form-control select2" required>
                                @foreach ($programs as $item)
                                    <option value="{{ $item->id }}" {{ $dana->program_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->desk_program }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kegiatan -->
                        <div class="form-group">
                            <label>Kegiatan</label>
                            <select name="kegiatan_id" class="form-control select2" required>
                                @foreach ($kegiatans as $item)
                                    <option value="{{ $item->id }}" {{ $dana->kegiatan_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->desk_kegiatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Subkegiatan -->
                        <div class="form-group">
                            <label>Subkegiatan</label>
                            <select name="subkegiatan_id" class="form-control select2" required>
                                @foreach ($subkegiatans as $item)
                                    <option value="{{ $item->id }}" {{ $dana->subkegiatan_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->desk_subkegiatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Organisasi -->
                        <div class="form-group">
                            <label>Organisasi</label>
                            <select name="organisasi_id" class="form-control select2" required>
                                @foreach ($organisasis as $item)
                                    <option value="{{ $item->id }}" {{ $dana->organisasi_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->desk_organisasi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sumber Dana -->
                        <div class="form-group">
                            <label>Sumber Dana</label>
                            <select name="sumberdana_id" class="form-control select2" required>
                                @foreach ($sumberdanas as $item)
                                    <option value="{{ $item->id }}" {{ $dana->sumberdana_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->sumber_dana }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Jumlah Dana -->
                        <div class="form-group">
                            <label>Jumlah Dana</label>
                            <input type="text" name="jumlah_dana" id="jumlah_dana" class="form-control"
                                value="{{ number_format($dana->jumlah_dana, 0, ',', '.') }}" required>
                        </div>


                        <!-- Dana Asal -->
                        {{-- <div class="form-group">
                            <label>Dana Asal (jika pergeseran)</label>
                            <select name="dana_asal_id" class="form-control select2">
                                <option value="">-- Tidak ada --</option>
                                @foreach ($dana_asals as $item)
                                    <option value="{{ $item->id }}" {{ $dana->dana_asal_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->program->nama_program }} - {{ number_format($item->jumlah_dana) }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}

                        <!-- Keterangan -->
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="ket" class="form-control">{{ $dana->ket }}</textarea>
                        </div>

                        <!-- Status Tersedia -->
                        <div class="form-group">
                            <label>Status Tersedia</label>
                            <select name="available" class="form-control">
                                <option value="1" {{ $dana->available ? 'selected' : '' }}>Tersedia</option>
                                <option value="0" {{ !$dana->available ? 'selected' : '' }}>Tidak Tersedia</option>
                            </select>
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
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>

<script>
    $(document).ready(function () {
        $('.select2').select2();

        // Format input jumlah_dana dengan pemisah ribuan
        $('#jumlah_dana').on('input', function () {
            let angka = $(this).val().replace(/\D/g, '');
            $(this).val(angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
        });

        // Ubah titik ke angka saat submit form
        $('form').on('submit', function () {
            let angkaBersih = $('#jumlah_dana').val().replace(/\./g, '');
            $('#jumlah_dana').val(angkaBersih);
        });
    });
</script>
@endpush
