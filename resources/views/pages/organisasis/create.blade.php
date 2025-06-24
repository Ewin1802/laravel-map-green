@extends('layouts.app')

@section('title', 'Tambah Organisasi')

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
                <h1>Form Input Organisasi</h1>
            </div>

            <div class="section-body">
                <h2 class="section-title">Tambah Organisasi</h2>

                <div class="card">
                    <form action="{{ route('organisasis.store') }}" method="POST">
                        @csrf
                        <div class="card-header">
                            <h4>Isi Form di bawah ini</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Kode Organisasi</label>
                                <input type="text" class="form-control @error('kode_organisasi') is-invalid @enderror"
                                    name="kode_organisasi">
                                @error('kode_organisasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Deskripsi Organisasi</label>
                                <input type="text" class="form-control @error('desk_organisasi') is-invalid @enderror"
                                    name="desk_organisasi">
                                @error('desk_organisasi')
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
