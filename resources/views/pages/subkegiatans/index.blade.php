@extends('layouts.app')

@section('title', 'Daftar Subkegiatan')

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
                <h1>Subkegiatan</h1>
                <div class="section-header-button">
                    <a href="{{ route('subkegiatans.create') }}" class="btn btn-primary">Tambah Subkegiatan</a>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Kode Rek</a></div>
                    <div class="breadcrumb-item"><a href="#">Subkegiatan</a></div>
                    <div class="breadcrumb-item">Semua Subkegiatan</div>
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
                                <h4>Semua Subkegiatan</h4>
                            </div>
                            <div class="card-body">
                                <div class="float-right">
                                    <form method="GET" action="{{ route('subkegiatans.index') }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Cari Subkegiatan" name="kode_subkegiatan">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="clearfix mb-3"></div>

                                <div class="table-responsive">
                                    <table class="table-striped table">
                                        <tr>
                                            <th>Kode Subkegiatan</th>
                                            <th>Deskripsi Subkegiatan</th>
                                            <th>Dibuat Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                        @foreach ($subkegiatans as $subkegiatan)
                                            <tr>
                                                <td>{{ $subkegiatan->kode_subkegiatan }}</td>
                                                <td>{{ $subkegiatan->desk_subkegiatan }}</td>
                                                <td>{{ $subkegiatan->created_at }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a href='{{ route('subkegiatans.edit', $subkegiatan->id) }}'
                                                            class="btn btn-sm btn-info btn-icon">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>

                                                        <form action="{{ route('subkegiatans.destroy', $subkegiatan->id) }}"
                                                            method="POST" class="ml-2">
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
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $subkegiatans->withQueryString()->links() }}
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
    {{-- Konfirmasi Hapus --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.confirm-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    const confirmed = confirm('Apakah Anda yakin ingin menghapus subkegiatan ini?');
                    if (!confirmed) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
@endpush
