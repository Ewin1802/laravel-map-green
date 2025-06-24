@extends('layouts.app')

@section('title', 'Edit Program')

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
                <h2 class="section-title">Edit Program</h2>

                <div class="card">
                    <form action="{{ route('programs.update', $program) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-header">
                            <h4>Form Edit Program</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Kode Program</label>
                                <input type="text" class="form-control @error('kode_program') is-invalid @enderror"
                                    name="kode_program" value="{{ $program->kode_program }}">
                                @error('kode_program')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Deskripsi Program</label>
                                <input type="text" class="form-control @error('desk_program') is-invalid @enderror"
                                    name="desk_program" value="{{ $program->desk_program }}">
                                @error('desk_program')
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
