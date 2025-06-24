@extends('layouts.app')

@section('title', 'Edit Kegiatan')

{{-- Favicon - Logo web disamping title --}}
<link rel="icon" href="{{ asset('img/logo_pemda.png') }}" type="image/png">

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <h2 class="section-title">Edit Kegiatan</h2>

                <div class="card">
                    <form action="{{ route('kegiatans.update', $kegiatan) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-header">
                            <h4>Form Edit Kegiatan</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Kode Kegiatan</label>
                                <input type="text" class="form-control @error('kode_kegiatan') is-invalid @enderror"
                                    name="kode_kegiatan" value="{{ $kegiatan->kode_kegiatan }}" required>
                                @error('kode_kegiatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Deskripsi Kegiatan</label>
                                <input type="text" class="form-control @error('desk_kegiatan') is-invalid @enderror"
                                    name="desk_kegiatan" value="{{ $kegiatan->desk_kegiatan }}" required>
                                @error('desk_kegiatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
@endpush
