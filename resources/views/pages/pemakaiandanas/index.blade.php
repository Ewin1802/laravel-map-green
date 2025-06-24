@extends('layouts.app')

@section('title', 'Realisasi')

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
                <h1>Realisasi</h1>
                <div class="section-header-button">
                    <a href="{{ route('pemakaiandanas.create') }}" class="btn btn-primary">Input Realisasi</a>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Penatausahaan</a></div>
                    <div class="breadcrumb-item"><a href="#">Realisasi</a></div>
                    <div class="breadcrumb-item">Semua Realisasi</div>
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
                                <h4>Semua Realisasi</h4>
                            </div>
                            <div class="card-body">
                                <div class="float-right">
                                    <form method="GET" action="{{ route('pemakaiandanas.index') }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Cari No. Bukti / Subkegiatan" name="search">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="clearfix mb-3"></div>

                                <div class="table-responsive">
                                    <table class="table-striped table">
                                        <thead>
                                            <tr>
                                                <th>No. Bukti</th>
                                                <th>Tanggal Bukti</th>
                                                <th>Subkegiatan</th>
                                                <th>Uraian</th>
                                                <th>Jenis Belanja</th> {{-- Tambahan --}}
                                                <th>Jumlah Pemakaian</th>
                                                <th>File Bukti</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($pemakaians as $pemakaian)
                                                <tr>
                                                    <td style="white-space: normal; word-break: break-word;">{{ $pemakaian->no_bukti }}</td>

                                                    <td>{{ \Carbon\Carbon::parse($pemakaian->tgl_bukti)->format('d-m-Y') }}</td>
                                                    <td>{{ $pemakaian->dana->subkegiatan->desk_subkegiatan ?? '-' }}</td>
                                                    <td>{{ $pemakaian->uraian }}</td>
                                                    <td>{{ $pemakaian->jenis_belanja ?? '-' }}</td> {{-- Tambahan --}}
                                                    <td>Rp{{ number_format($pemakaian->jumlah_pemakaian, 0, ',', '.') }}</td>
                                                    <td>
                                                        @if ($pemakaian->file_bukti)
                                                            <a href="{{ asset('storage/' . $pemakaian->file_bukti) }}" target="_blank">Lihat PDF</a>
                                                        @else
                                                            <span class="text-muted">Tidak ada</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <a href="{{ route('pemakaiandanas.edit', $pemakaian->id) }}" class="btn btn-sm btn-info btn-icon">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </a>

                                                            <form action="{{ route('pemakaiandanas.destroy', $pemakaian->id) }}" method="POST" class="ml-2">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-sm btn-danger btn-icon confirm-delete">
                                                                    <i class="fas fa-times"></i> Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">Tidak ada data pemakaian dana.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>

                                    </table>
                                </div>

                                <div class="float-right">
                                    {{ $pemakaians->withQueryString()->links() }}
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
                    const confirmed = confirm('Apakah Anda yakin ingin menghapus data ini? Anggarannya akan dikembalikan ke subkegiatan sebelumnya');
                    if (!confirmed) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
@endpush
