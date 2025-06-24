@extends('layouts.app')

@section('title', 'Tambah Sumber Dana')

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
                <h1>Form Input Sumber Dana</h1>
            </div>

            <div class="section-body">
                <h2 class="section-title">Tambah Sumber Dana</h2>

                <div class="card">
                    <form action="{{ route('sumberdanas.store') }}" method="POST">
                        @csrf
                        <div class="card-header">
                            <h4>Isi Form di bawah ini</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Sumber Dana</label>
                                <input type="text" class="form-control @error('sumber_dana') is-invalid @enderror"
                                    name="sumber_dana">
                                @error('sumber_dana')
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
