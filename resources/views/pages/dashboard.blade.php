@extends('layouts.app')

@section('title', 'Dashboard')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="icon" href="{{ asset('img/logo_pemda.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        /* #loadingIndicator {
            position: relative;
            text-align: center;
            padding: 50px 0;
        } */
        #loadingIndicator {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.85);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999; /* jangan terlalu tinggi */
    pointer-events: auto;
}

#loadingIndicator.hidden {
    display: none !important;
    pointer-events: none;   /* <--- ini penting */
    opacity: 0;             /* opsional untuk menghindari flashing */
    z-index: -1;            /* opsional, dorong ke belakang */
}




        #loadingIndicator .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        #tabelWrapper {
            display: none;
        }
        .subkegiatan-card {
            border: 1px solid #dcdcdc;
            border-radius: 10px;
            padding: 1.2rem;
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            position: relative;
        }

        .subkegiatan-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
        }

        .subkegiatan-info {
            font-size: 0.95rem;
            color: #666;
            margin-top: 4px;
        }

        .desk-belanja-label {
            margin-top: 1rem;
            font-weight: 500;
            color: #555;
            background-color: #f2f2f2;
            padding: 6px 10px;
            border-radius: 4px;
        }

        .table-belanja {
            width: 100%;
            margin-top: 0.5rem;
            border-collapse: collapse;
        }

        .table-belanja th,
        .table-belanja td {
            padding: 8px 12px;
            border: 1px solid #eee;
        }

        .text-right {
            text-align: right;
        }

        .toggle-button {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #666;
            cursor: pointer;
            outline: none;
            transition: transform 0.2s ease;
        }

        .toggle-button:focus {
            outline: none;
        }
    </style>

@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Realisasi Anggaran per Sub Kegiatan</h1>
        </div>

        {{-- Loading Spinner --}}
        <div id="loadingIndicator" class="loading-overlay">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>


        {{-- Wrapper untuk konten --}}
        <div id="tabelWrapper" style="display: none;">
            @if(count($data) === 0)
                <div class="text-center text-muted py-5">
                    <h5>Belum ada Data yang tersedia. Silahkan input Anggaran</h5>
                </div>
            @else
                <div class="accordion" id="accordionDana">
                    @foreach ($data as $key => $item)
                        <div class="accordion mb-3">
                            <div class="subkegiatan-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="subkegiatan-title">{{ $item['subkegiatan'] }}</div>
                                        <div class="subkegiatan-info">
                                            Total Dana: Rp {{ number_format($item['total_dana'], 0, ',', '.') }}<br>
                                            Sisa Anggaran: Rp {{ number_format($item['jumlah_dana'], 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <button class="btn btn-link toggle-btn" type="button" data-target="#collapse-{{ $key }}">
                                        <i class="fas fa-chevron-down icon-toggle"></i>
                                    </button>


                                </div>

                                <div class="collapse" id="collapse-{{ $key }}">
                                    {{-- <div class="desk-belanja-label">Deskripsi Belanja</div> --}}
                                    <table class="table-belanja">
                                        <thead>
                                            <tr>
                                                <th>Deskripsi</th>
                                                <th class="text-right">Total Anggaran</th>
                                                <th class="text-right">Realisasi</th>
                                                <th class="text-right">Sisa Anggaran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($item['pemakaian'] as $belanja)
                                                <tr>
                                                    <td>{{ $belanja['desk_belanja'] }}</td>
                                                    <td class="text-right">
                                                        Rp {{ number_format($belanja['total_pemakaian'] + $belanja['sisa_anggaran'], 0, ',', '.') }}
                                                    </td>
                                                    <td class="text-right">Rp {{ number_format($belanja['total_pemakaian'], 0, ',', '.') }}</td>
                                                    <td class="text-right">Rp {{ number_format($belanja['sisa_anggaran'], 0, ',', '.') }}</td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </section>
</div>
@endsection


@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
<script src="{{ asset('js/scripts.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const loading = document.getElementById('loadingIndicator');
        const wrapper = document.getElementById('tabelWrapper');

        setTimeout(() => {
            if (loading) {
                loading.parentNode.removeChild(loading); // 🔹 Hapus elemen dari DOM
                console.log('✅ Loading overlay dihapus');
            }
            if (wrapper) {
                wrapper.style.display = 'block';
                console.log('✅ Konten ditampilkan');
            }
        }, 500);
    });
</script>




<script>
    $(document).ready(function () {
        $('.toggle-btn').on('click', function () {
            const targetId = $(this).data('target');
            const $target = $(targetId);
            const $icon = $(this).find('.icon-toggle');

            $target.collapse('toggle'); // buka/tutup konten

            // ubah icon saat transisi selesai
            $target.on('shown.bs.collapse', function () {
                $icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
            });

            $target.on('hidden.bs.collapse', function () {
                $icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
            });
        });
    });
</script>
@endpush


