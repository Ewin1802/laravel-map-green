@extends('layouts.app')

@section('title', 'Anggaran')

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
                <h1>Anggaran</h1>
                <div class="section-header-button">
                    <a href="{{ route('danas.create') }}" class="btn btn-primary">Input Anggaran</a>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Penatausahaan</a></div>
                    <div class="breadcrumb-item"><a href="#">Anggaran</a></div>
                    <div class="breadcrumb-item">Semua Anggaran</div>
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
                                <h4>Semua Anggaran</h4>
                            </div>
                            <div class="card-body">
                                <div class="float-right">
                                    <form method="GET" action="{{ route('danas.index') }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Cari Program / Kegiatan" name="search">
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
                                                <th>Kegiatan</th>
                                                <th>Subkegiatan</th>
                                                <th>Sumber Dana</th>
                                                <th>Anggaran Tersedia</th>
                                                <th>Ket</th>
                                                <th>Jenis</th>
                                                <th>Dibuat Tanggal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($danas as $dana)
                                                <tr @if(!$dana->available) style="color: #999;" @endif>
                                                    <td>{{ $dana->kegiatan->desk_kegiatan ?? '-' }}</td>
                                                    <td><strong>{{ $dana->subkegiatan->desk_subkegiatan ?? '-' }}</strong></td>
                                                    <td>{{ $dana->sumberdana->sumber_dana ?? '-' }}</td>
                                                    <td>Rp{{ number_format($dana->jumlah_dana, 0, ',', '.') }}</td>
                                                    <td>{{ $dana->ket ?? '-' }}</td>
                                                    <td>
                                                        @if($dana->dana_asal_id)
                                                            <span class="badge bg-info">Pergeseran</span>
                                                        @else
                                                            <span class="badge bg-success">Anggaran Awal</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $dana->created_at->format('d-m-Y') }}</td>

                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            @if($dana->available)
                                                                {{-- Tombol Edit: hanya tampil jika bukan pergeseran --}}
                                                                @if(!$dana->dana_asal_id)
                                                                    <a href="{{ route('danas.edit', $dana->id) }}" class="btn btn-sm btn-primary btn-icon ml-1">
                                                                        <i class="fas fa-edit"></i> Edit
                                                                    </a>
                                                                @endif

                                                                {{-- Tombol Pergeseran --}}
                                                                <button type="button"
                                                                    class="btn btn-sm btn-warning btn-icon ml-1 btn-pergeseran"
                                                                    data-dana-id="{{ $dana->id }}"
                                                                    data-program-id="{{ $dana->program->id }}"
                                                                    data-kegiatan-id="{{ $dana->kegiatan->id }}"
                                                                    data-organisasi-id="{{ $dana->organisasi->id }}"
                                                                    data-sumberdana-id="{{ $dana->sumberdana->id }}"
                                                                    data-jumlah-dana="{{ $dana->jumlah_dana }}"
                                                                    data-program="{{ $dana->program->desk_program }}"
                                                                    data-kegiatan="{{ $dana->kegiatan->desk_kegiatan }}"
                                                                    data-organisasi="{{ $dana->organisasi->desk_organisasi }}"
                                                                    data-sumberdana="{{ $dana->sumberdana->sumber_dana }}"
                                                                    data-subkegiatan="{{ $dana->subkegiatan->desk_subkegiatan ?? 'Subkegiatan Tidak Diketahui' }}"
                                                                    >
                                                                    <i class="fas fa-random"></i> Pergeseran
                                                                </button>

                                                                {{-- Tombol Hapus --}}
                                                                <button type="button" class="btn btn-sm btn-danger btn-icon confirm-delete ml-1"
                                                                    data-delete-url="{{ route('danas.destroy', $dana->id) }}">
                                                                    <i class="fas fa-times"></i> Hapus
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>

                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">Tidak ada data dana.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="float-right">
                                    {{ $danas->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

<!-- Modal Pergeseran -->
<div class="modal fade" id="pergeseranModal" tabindex="-1" role="dialog" aria-labelledby="pergeseranModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('danas.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pergeseran Subkegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="program_id" id="modal-program-id">
                    <input type="hidden" name="kegiatan_id" id="modal-kegiatan-id">
                    <input type="hidden" name="organisasi_id" id="modal-organisasi-id">
                    <input type="hidden" name="sumberdana_id" id="modal-sumberdana-id">
                    <input type="hidden" name="jumlah_dana" id="modal-jumlah-dana">
                    <input type="hidden" name="dana_asal_id" id="modal-dana-asal-id">
                    <input type="hidden" name="ket" id="modal-ket">

                    <div class="form-group">
                        <label>Program</label>
                        <input type="text" class="form-control" id="modal-program" readonly>
                    </div>

                    <div class="form-group">
                        <label>Kegiatan</label>
                        <input type="text" class="form-control" id="modal-kegiatan" readonly>
                    </div>

                    <div class="form-group">
                        <label>Organisasi</label>
                        <input type="text" class="form-control" id="modal-organisasi" readonly>
                    </div>

                    <div class="form-group">
                        <label>Sumber Dana</label>
                        <input type="text" class="form-control" id="modal-sumberdana" readonly>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Dana Tersedia</label>
                        <input type="text" class="form-control" id="modal-jumlah-dana-tersedia" readonly>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Dana yang Akan Digeser</label>
                        <input type="text" class="form-control" id="modal-jumlah-pemakaian" required>
                        <input type="hidden" name="jumlah_pemakaian" id="jumlah-pemakaian-hidden">
                    </div>

                    <div class="form-group">
                        <label>Subkegiatan Baru</label>
                        <select class="form-control select2" name="subkegiatan_id" required>
                            @foreach ($subkegiatans as $subkegiatan)
                                <option value="{{ $subkegiatan->id }}">{{ $subkegiatan->desk_subkegiatan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Pergeseran</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>


@push('scripts')
<script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>

    {{-- Konfirmasi Hapus --}}
    <script>
        document.querySelectorAll('.confirm-delete').forEach(btn => {
            btn.addEventListener('click', function () {
                if (confirm('Yakin ingin menghapus?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = this.dataset.deleteUrl;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrf);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>

    {{-- Simpan Pergeseran --}}
    <script>
        $(document).ready(function () {
            $('.btn-pergeseran').click(function () {
                // Ambil data
                $('#modal-program').val($(this).data('program'));
                $('#modal-kegiatan').val($(this).data('kegiatan'));
                $('#modal-organisasi').val($(this).data('organisasi'));
                $('#modal-sumberdana').val($(this).data('sumberdana'));

                $('#modal-program-id').val($(this).data('program-id'));
                $('#modal-kegiatan-id').val($(this).data('kegiatan-id'));
                $('#modal-organisasi-id').val($(this).data('organisasi-id'));
                $('#modal-sumberdana-id').val($(this).data('sumberdana-id'));
                $('#modal-jumlah-dana').val($(this).data('jumlah-dana'));
                $('#modal-dana-asal-id').val($(this).data('dana-id'));

                $('#modal-jumlah-dana-tersedia').val($(this).data('jumlah-dana'));
                $('#modal-jumlah-pemakaian').attr('max', $(this).data('jumlah-dana'));

                // Hindari undefined
                var subkegiatan = $(this).data('subkegiatan') || 'Subkegiatan Tidak Diketahui';
                $('#modal-ket').val("Pergeseran dari - " + subkegiatan);

                $('#pergeseranModal').modal('show');
            });



            $('.select2').select2({
                width: '100%'
            });
        });

    </script>

    <script>
        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function unformatRupiah(rp) {
            return rp.replace(/\./g, '');
        }

        $(document).ready(function () {
            $('.btn-pergeseran').click(function () {
                const jumlahDana = $(this).data('jumlah-dana');
                $('#modal-jumlah-dana-tersedia').val('Rp' + formatRupiah(jumlahDana));
                $('#modal-jumlah-pemakaian').val('');
                $('#jumlah-pemakaian-hidden').val('');
            });

            $('#modal-jumlah-pemakaian').on('input', function () {
                const angka = unformatRupiah($(this).val().replace(/[^0-9]/g, ''));
                const formatted = formatRupiah(angka);
                $(this).val(formatted);
                $('#jumlah-pemakaian-hidden').val(angka);
            });
        });
    </script>


@endpush
