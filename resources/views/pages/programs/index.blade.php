@extends('layouts.app')

@section('title', 'Daftar Program')

{{-- Favicon - Logo web disamping title --}}
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
                <h1>Program</h1>
                <div class="section-header-button">
                    <a href="{{ route('programs.create') }}" class="btn btn-primary">Tambah Program</a>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Kode Rek</a></div>
                    <div class="breadcrumb-item"><a href="#">Program</a></div>
                    <div class="breadcrumb-item">Semua Program</div>
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
                                <h4>Semua Program</h4>
                            </div>
                            <div class="card-body">
                                <div class="float-right">
                                    <form method="GET" action="{{ route('programs.index') }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Cari Program" name="kode_program">
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
                                            <th>Kode Program</th>
                                            <th>Deskripsi Program</th>
                                            <th>Dibuat Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                        @foreach ($programs as $program)
                                            <tr>
                                                <td>{{ $program->kode_program }}</td>
                                                <td>{{ $program->desk_program }}</td>
                                                <td>{{ $program->created_at }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a href='{{ route('programs.edit', $program->id) }}'
                                                            class="btn btn-sm btn-info btn-icon">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>

                                                        <form action="{{ route('programs.destroy', $program->id) }}"
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
                                    {{ $programs->withQueryString()->links() }}
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
                    const confirmed = confirm('Apakah Anda yakin ingin menghapus program ini?');
                    if (!confirmed) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
@endpush
