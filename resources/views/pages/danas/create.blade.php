@extends('layouts.app')

@section('title', 'Tambah Dana')

{{-- Favicon --}}
<link rel="icon" href="{{ asset('img/logo_pemda.png') }}" type="image/png">

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Form Input Anggaran</h1>
            </div>

            <div class="section-body">

                <div class="card">
                    <form action="{{ route('danas.store') }}" method="POST">
                        @csrf
                        <div class="card-header">
                            <h4>Isi Form di bawah ini</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Program</label>
                                <select name="program_id" class="form-control select2 @error('program_id') is-invalid @enderror">
                                    <option value="">Pilih Program</option>
                                    @foreach ($programs as $program)
                                        <option value="{{ $program->id }}">{{ $program->kode_program }} - {{ $program->desk_program }}</option>
                                    @endforeach
                                </select>
                                @error('program_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Kegiatan</label>
                                <select name="kegiatan_id" class="form-control select2 @error('kegiatan_id') is-invalid @enderror">
                                    <option value="">Pilih Kegiatan</option>
                                    @foreach ($kegiatans as $kegiatan)
                                        <option value="{{ $kegiatan->id }}">{{ $kegiatan->kode_kegiatan }} - {{ $kegiatan->desk_kegiatan }}</option>
                                    @endforeach
                                </select>
                                @error('kegiatan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Subkegiatan</label>
                                <select name="subkegiatan_id" class="form-control select2 @error('subkegiatan_id') is-invalid @enderror">
                                    <option value="">Pilih Subkegiatan</option>
                                    @foreach ($subkegiatans as $subkegiatan)
                                        <option value="{{ $subkegiatan->id }}">{{ $subkegiatan->kode_subkegiatan }} - {{ $subkegiatan->desk_subkegiatan }}</option>
                                    @endforeach
                                </select>
                                @error('subkegiatan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Organisasi</label>
                                <select name="organisasi_id" class="form-control select2 @error('organisasi_id') is-invalid @enderror">
                                    <option value="">Pilih Organisasi</option>
                                    @foreach ($organisasis as $organisasi)
                                        <option value="{{ $organisasi->id }}">{{ $organisasi->kode_organisasi }} - {{ $organisasi->desk_organisasi }}</option>
                                    @endforeach
                                </select>
                                @error('organisasi_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Sumber Dana</label>
                                <select name="sumberdana_id" class="form-control select2 @error('sumberdana_id') is-invalid @enderror">
                                    <option value="">Pilih Sumber Dana</option>
                                    @foreach ($sumberdanas as $sumberdana)
                                        <option value="{{ $sumberdana->id }}">{{ $sumberdana->sumber_dana }}</option>
                                    @endforeach
                                </select>
                                @error('sumberdana_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Jumlah Dana (Rp)</label>
                                <input type="text" id="jumlah_dana" class="form-control @error('jumlah_dana') is-invalid @enderror"
                                    name="jumlah_dana" oninput="formatRupiah(this)">
                                @error('jumlah_dana')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea name="ket" class="form-control @error('ket') is-invalid @enderror" rows="3" placeholder="Tambahkan keterangan jika perlu">{{ old('ket') }}</textarea>
                                @error('ket')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
            $('.select2').select2({
                width: '100%'
            });
        });
    </script>

    <script>
        function formatRupiah(input) {
            let value = input.value.replace(/[^,\d]/g, '').toString();
            let split = value.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            input.value = rupiah;
        }

        // Optional: ubah ke format angka sebelum dikirim
        $('form').on('submit', function () {
            let rawValue = $('#jumlah_dana').val().replace(/\./g, '').replace(',', '');
            $('<input>').attr({
                type: 'hidden',
                name: 'jumlah_dana',
                value: rawValue
            }).appendTo(this);
            $('#jumlah_dana').remove(); // Remove formatted input
        });
    </script>

@endpush
