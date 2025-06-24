@extends('layouts.app')

@section('title', 'Edit Rekening Belanja')

<link rel="icon" href="{{ asset('img/logo_pemda.png') }}" type="image/png">

@push('style')
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <h2 class="section-title">Edit Rekening Belanja</h2>

                <div class="card">
                    <form action="{{ route('rekbelanjas.update', $rekbelanja->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-header">
                            <h4>Form Edit Rekening</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Kode Rekening</label>
                                <input type="text" class="form-control @error('kode_rek') is-invalid @enderror"
                                    name="kode_rek" value="{{ old('kode_rek', $rekbelanja->kode_rek) }}" required>
                                @error('kode_rek')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Deskripsi Belanja</label>
                                <textarea class="form-control @error('desk_belanja') is-invalid @enderror"
                                    name="desk_belanja">{{ old('desk_belanja', $rekbelanja->desk_belanja) }}</textarea>
                                @error('desk_belanja')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Jenis Belanja</label>
                                <select class="form-control selectric @error('jenis_belanja') is-invalid @enderror" name="jenis_belanja" required>
                                    <option value="">-- Pilih Jenis Belanja --</option>
                                    <option value="Modal" {{ old('jenis_belanja', $rekbelanja->jenis_belanja) == 'Modal' ? 'selected' : '' }}>Modal</option>
                                    <option value="Jasa" {{ old('jenis_belanja', $rekbelanja->jenis_belanja) == 'Jasa' ? 'selected' : '' }}>Jasa</option>
                                </select>
                                @error('jenis_belanja')
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

<script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('select.selectric').selectric();
    });
</script>

@endpush
