@extends('layouts.app')

@section('title', 'Kegiatan')

{{-- Favicon - Logo web disamping title --}}
<link rel="icon" href="{{ asset('img/logo_pemda.png') }}" type="image/png">

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Kegiatan</h1>
                <div class="section-header-button">
                    <a href="{{ route('kegiatans.create') }}" class="btn btn-primary">Tambah Kegiatan</a>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Kode Rek</a></div>
                    <div class="breadcrumb-item"><a href="#">Kegiatan</a></div>
                    <div class="breadcrumb-item">Semua Kegiatan</div>
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
                                <h4>Semua Kegiatan</h4>
                            </div>
                            <div class="card-body">

                                <div class="float-right">
                                    <form method="GET" action="{{ route('kegiatans.index') }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Cari Kegiatan" name="kode_kegiatan">
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
                                            <th>Kode Kegiatan</th>
                                            <th>Deskripsi Kegiatan</th>
                                            <th>Dibuat Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                        @foreach ($kegiatans as $kegiatan)
                                            <tr>
                                                <td>{{ $kegiatan->kode_kegiatan }}</td>
                                                <td>{{ $kegiatan->desk_kegiatan }}</td>
                                                <td>{{ $kegiatan->created_at }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a href='{{ route('kegiatans.edit', $kegiatan->id) }}'
                                                            class="btn btn-sm btn-info btn-icon">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>

                                                        <form action="{{ route('kegiatans.destroy', $kegiatan->id) }}"
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
                                    {{ $kegiatans->withQueryString()->links() }}
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
    <!-- JS Libraries -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-posts.js') }}"></script>

    {{-- Konfirmasi Hapus --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.confirm-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    const confirmed = confirm('Apakah Anda yakin ingin menghapus kegiatan ini?');
                    if (!confirmed) {
                        event.preventDefault(); // Membatalkan aksi penghapusan jika user memilih "Batal"
                    }
                });
            });
        });
    </script>
@endpush
