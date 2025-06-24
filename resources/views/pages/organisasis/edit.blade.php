@extends('layouts.app')

@section('title', 'Edit Organisasi')

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
            <div class="section-body">
                <h2 class="section-title">Edit Organisasi</h2>

                <div class="card">
                    <form action="{{ route('organisasis.update', $organisasi) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-header">
                            <h4>Form Edit Organisasi</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Kode Organisasi</label>
                                <input type="text" class="form-control @error('kode_organisasi') is-invalid @enderror"
                                    name="kode_organisasi" value="{{ $organisasi->kode_organisasi }}">
                                @error('kode_organisasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Deskripsi Organisasi</label>
                                <input type="text" class="form-control @error('desk_organisasi') is-invalid @enderror"
                                    name="desk_organisasi" value="{{ $organisasi->desk_organisasi }}">
                                @error('desk_organisasi')
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
