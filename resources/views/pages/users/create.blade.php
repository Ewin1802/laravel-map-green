@extends('layouts.app')

@section('title', 'Create User')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet"
        href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
        {{-- Favicon - Logo web disamping title --}}
    <link rel="icon" href="{{ asset('img/logo_pemda.png') }}" type="image/png">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambahkan Pengguna Baru</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Akses Admin</a></div>
                    <div class="breadcrumb-item"><a href="#">User</a></div>
                    <div class="breadcrumb-item">Tambahkan Pengguna Baru</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Petunjuk</h2>
                <p class="section-lead">Perhatikan Role dari Pengguna saat menyimpan</p>

                <div class="card">
                    <form action="{{ route('user.store')}}" method="POST">
                        @csrf
                        <div class="card-header">
                            <h4>Input Text</h4>
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text"
                                class="form-control
                                @error('name')
                                    is-invalid
                                @enderror"
                                name="name">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input type="email"
                                class="form-control
                                @error('email')
                                    is-invalid
                                @enderror"
                                name="email">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="nama_skpd">SKPD <span style="color: red;">*</span></label>
                                <select name="skpd" id="nama_skpd" class="form-control select2 @error('skpd') is-invalid @enderror" required>
                                    <option value="">Pilih SKPD</option>
                                    @foreach($skpd as $s)
                                        <option value="{{ $s->nama_skpd }}" {{ old('skpd') == $s->nama_skpd ? 'selected' : '' }}>
                                            {{ $s->nama_skpd }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('skpd')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>



                            <div class="form-group">
                                <label>Password</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                    </div>
                                    <input type="password"
                                        class="form-control @error('password')
                                            is-invalid
                                        @enderror"
                                        name="password">
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">
                                            {{ $message }}
                                    </div>
                               @enderror
                            </div>

                            <div class="form-group">
                                <label>Phone</label>
                                <input type="number" class="form-control" name="phone">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Roles</label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="roles" value="ADMIN" class="selectgroup-input"
                                            checked="">
                                        <span class="selectgroup-button">ADMIN</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="roles" value="SKPD" class="selectgroup-input">
                                        <span class="selectgroup-button">SKPD</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="roles" value="USER" class="selectgroup-input">
                                        <span class="selectgroup-button">USER</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>

            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/cleave.js/dist/cleave.min.js') }}"></script>
    <script src="{{ asset('library/cleave.js/dist/addons/cleave-phone.us.js') }}"></script>
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('library/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/forms-advanced-forms.js') }}"></script>

    {{-- <script>
        $(document).ready(function() {
            // Data desa berdasarkan kecamatan
            var desaData = {
                "Pinogaluman": ["Batu_Bantayo", "Batutajam", "Buko", "Buko_Selatan", "Buko_Utara", "Busato", "Dalapuli", "Dalapuli_Barat", "Dalapuli_Timur", "Dengi", "Duini", "Kayuogu", "Komus_Satu", "Padango", "Tanjung_Sidupa", "Tombulang_Pantai", "Tombulang_Timur", "Tombulang", "Tuntulow", "Tuntung", "Tuntung_Timur", "Tuntulow_Utara"],
                "Kaidipang": ["Bigo", "Bigo_Selatan", "Boroko", "Boroko_Timur", "Boroko_Utara", "Gihang", "Inomunga", "Inomunga_Utara", "Komus_Dua", "Komus_Dua_Timur", "Kuala", "Kuala_Utara", "Pontak", "Soligir", "Solo"],
                "Bolangitang_Barat": ["Sonuo", "Bolangitang", "Bolangitang_Satu", "Bolangitang_Dua", "Jambusarang", 'Talaga_Tomoagu', "Iyok", "Keimanga", "Langi", "Ollot", "Ollot_Satu", "Ollot_Dua", "Paku", "Paku_Selatan", "Talaga", "Tanjung_Buaya", "Tote", "Wakat"],
                "Bolangitang_Timur": ["Binjeita", "Binjeita_Satu", "Binjeita_Dua", "Binuanga", "Binuni", "Biontong", "Biontong_Satu", "Biontong_Dua", "Bohabak_Satu", "Bohabak_Dua", "Bohabak_Tiga", "Bohabak_Empat", "Lipu_Bogu", "Mokoditek", "Mokoditek_Satu", "Nagara", "Nunuka", "Saleo", "Saleo_Satu", "Tanjung_Labuo"],
                "Bintauna": ["Dudepo", "Bintauna Pantai", "Bintauna Induk", "Domisil"],
                "Sangkub": ["Sangkub Selatan", "Tuntulow", "Dalagon", "Sampiro"]
                // Tambahkan kecamatan dan desa lainnya di sini jika diperlukan
            };

            // Event listener untuk perubahan di dropdown kecamatan
            $('#kecamatan').change(function() {
                var kecamatan = $(this).val(); // Ambil nilai kecamatan yang dipilih
                var desaOptions = '<option value="">-- Pilih Desa --</option>'; // Opsi default

                if (kecamatan) {
                    // Jika ada kecamatan yang dipilih, ambil desa yang sesuai
                    var desaList = desaData[kecamatan];
                    desaList.forEach(function(desa) {
                        desaOptions += '<option value="' + desa + '">' + desa + '</option>';
                    });
                }

                $('#akses').html(desaOptions); // Update dropdown desa dengan opsi yang sesuai
            });
        });
    </script> --}}

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const skpdSelect = document.getElementById('skpd');
            const submitButton = document.querySelector('button[type="submit"]');

            function toggleSubmitButton() {
                if (skpdSelect.value === "") {
                    submitButton.disabled = true;
                } else {
                    submitButton.disabled = false;
                }
            }

            // Initial check
            toggleSubmitButton();

            // Add event listener to SKPD select
            skpdSelect.addEventListener('change', toggleSubmitButton);
        });
    </script>
@endpush
