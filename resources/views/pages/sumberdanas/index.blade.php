@extends('layouts.app')

@section('title', 'Daftar Sumber Dana')

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
                <h1>Sumber Dana</h1>
                <div class="section-header-button">
                    <a href="{{ route('sumberdanas.create') }}" class="btn btn-primary">Tambah Sumber Dana</a>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Kode Rek</a></div>
                    <div class="breadcrumb-item"><a href="#">Sumber Dana</a></div>
                    <div class="breadcrumb-item">Semua Sumber Dana</div>
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
                                <h4>Semua Sumber Dana</h4>
                            </div>
                            <div class="card-body">
                                <div class="float-right">
                                    <form method="GET" action="{{ route('sumberdanas.index') }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Cari Sumber Dana" name="sumber_dana">
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
                                            <th>Sumber Dana</th>
                                            <th>Dibuat Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                        @foreach ($sumberdanas as $sumberdana)
                                            <tr>
                                                <td>{{ $sumberdana->sumber_dana }}</td>
                                                <td>{{ $sumberdana->created_at }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a href='{{ route('sumberdanas.edit', $sumberdana->id) }}'
                                                            class="btn btn-sm btn-info btn-icon">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>

                                                        <form action="{{ route('sumberdanas.destroy', $sumberdana->id) }}"
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
                                    {{ $sumberdanas->withQueryString()->links() }}
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
                    const confirmed = confirm('Apakah Anda yakin ingin menghapus sumber dana ini?');
                    if (!confirmed) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
@endpush
