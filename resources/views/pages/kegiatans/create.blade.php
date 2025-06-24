@extends('layouts.app')

@section('title', 'Tambah Kegiatan')

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
            <div class="section-header">
                <h1>Form Input Kegiatan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Forms</a></div>
                    <div class="breadcrumb-item">Form Input Kegiatan</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Kegiatan</h2>

                <div class="card">
                    <form action="{{ route('kegiatans.store') }}" method="POST">
                        @csrf
                        <div class="card-header">
                            <h4>Isi Form di bawah ini</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Kode Kegiatan</label>
                                <input type="text"
                                    class="form-control @error('kode_kegiatan') is-invalid @enderror"
                                    name="kode_kegiatan">
                                @error('kode_kegiatan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Deskripsi Kegiatan</label>
                                <input type="text"
                                    class="form-control @error('desk_kegiatan') is-invalid @enderror"
                                    name="desk_kegiatan">
                                @error('desk_kegiatan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
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
@endpush
