@extends('layouts.app')

@section('title', 'Pilih Tahun')
@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-body d-flex justify-content-center align-items-center" style="min-height: 70vh;">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white text-center">
                        <h4 class="mb-0">Pilih Tahun Anggaran</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('set.tahun') }}">
                            @csrf
                            <div class="form-group">
                                {{-- <label for="tahun">Tahun:</label> --}}
                                <select name="tahun" class="form-control" required>
                                    @foreach($daftarTahun as $tahun)
                                        <option value="{{ $tahun }}" {{ session('tahun') == $tahun ? 'selected' : '' }}>
                                            {{ $tahun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="btn btn-primary btn-block" type="submit">Lanjut</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
