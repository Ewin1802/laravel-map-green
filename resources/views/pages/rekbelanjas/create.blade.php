@extends('layouts.app')

@section('title', 'Tambah Rekening Belanja')

<link rel="icon" href="{{ asset('img/logo_pemda.png') }}" type="image/png">

@push('style')
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Form Input Rekening Belanja</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Forms</a></div>
                    <div class="breadcrumb-item">Form Input Rekening</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Rekening Belanja</h2>

                <div class="card">
                    <form action="{{ route('rekbelanjas.store') }}" method="POST">
                        @csrf
                        <div class="card-header">
                            <h4>Isi Form di bawah ini</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Kode Rekening</label>
                                <input type="text" class="form-control @error('kode_rek') is-invalid @enderror" name="kode_rek" value="{{ old('kode_rek') }}">
                                @error('kode_rek')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Deskripsi Belanja</label>
                                <textarea class="form-control @error('desk_belanja') is-invalid @enderror" name="desk_belanja">{{ old('desk_belanja') }}</textarea>
                                @error('desk_belanja')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Jenis Belanja</label>
                                <select class="form-control selectric @error('jenis_belanja') is-invalid @enderror" name="jenis_belanja" required>
                                    <option value="">-- Pilih Jenis Belanja --</option>
                                    <option value="Modal" {{ old('jenis_belanja') == 'Modal' ? 'selected' : '' }}>Modal</option>
                                    <option value="Jasa" {{ old('jenis_belanja') == 'Jasa' ? 'selected' : '' }}>Jasa</option>
                                </select>
                                @error('jenis_belanja')
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
<script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('select.selectric').selectric();
    });
</script>

@endpush
