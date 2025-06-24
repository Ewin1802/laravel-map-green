@extends('layouts.app')

@section('title', 'Rekening Belanja')

<link rel="icon" href="{{ asset('img/logo_pemda.png') }}" type="image/png">

@push('style')
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Rekening Belanja</h1>
                <div class="section-header-button">
                    <a href="{{ route('rekbelanjas.create') }}" class="btn btn-primary">Tambah Rekening</a>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Kode Rek</a></div>
                    <div class="breadcrumb-item">Rekening Belanja</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Daftar Rekening Belanja</h4>
                            </div>
                            <div class="card-body">
                                <div class="float-right">
                                    <form method="GET" action="{{ route('rekbelanjas.index') }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Cari Kode Rekening" name="kode_rek" value="{{ request('kode_rek') }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="clearfix mb-3"></div>

                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Kode Rekening</th>
                                                <th>Deskripsi Belanja</th>
                                                <th>Jenis Belanja</th>
                                                <th>Dibuat Tanggal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rekbelanjas as $rek)
                                                <tr>
                                                    <td>{{ $rek->kode_rek }}</td>
                                                    <td>{{ $rek->desk_belanja }}</td>
                                                    <td>{{ $rek->jenis_belanja }}</td>
                                                    <td>{{ $rek->created_at->format('d M Y') }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <a href="{{ route('rekbelanjas.edit', $rek->id) }}" class="btn btn-sm btn-info btn-icon">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </a>
                                                            <form action="{{ route('rekbelanjas.destroy', $rek->id) }}" method="POST" class="ml-2">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-sm btn-danger btn-icon confirm-delete">
                                                                    <i class="fas fa-times"></i> Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $rekbelanjas->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.confirm-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    const confirmed = confirm('Apakah Anda yakin ingin menghapus rekening ini?');
                    if (!confirmed) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
@endpush
