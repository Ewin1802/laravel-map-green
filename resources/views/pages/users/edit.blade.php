@extends('layouts.app')

@section('title', 'Edit User')

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
                <h1>Ubah Data Pengguna</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Akses Admin</a></div>
                    <div class="breadcrumb-item"><a href="#">User</a></div>
                    <div class="breadcrumb-item">Ubah Data Pengguna</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Petunjuk</h2>
                <p class="section-lead">Halaman ini hanya bisa merubah Nama, Email, Phone dan Role. Tidak bisa merubah Password meski sudah diisi Password baru.</p>

                <div class="card">
                    <form action="{{ route('user.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-header">
                            <h4>Ubah Data sesuai SKPD</h4>
                        </div>

                        <div class="card-body">

                            <!-- Input Name -->
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       name="name" value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Input Email -->
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Dropdown SKPD -->
                            <div class="form-group">
                                <label for="nama_skpd">SKPD <span style="color: red;">*</span></label>
                                <select name="skpd" id="nama_skpd" class="form-control select2 @error('skpd') is-invalid @enderror" required>
                                    <option value="">Pilih SKPD</option>
                                    @foreach($skpd as $s)
                                        <!-- Memilih nama_skpd yang tersimpan di user -->
                                        <option value="{{ $s->nama_skpd }}" {{ old('skpd', $user->skpd) == $s->nama_skpd ? 'selected' : '' }}>
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



                            <!-- Input Password (opsional) -->
                            <div class="form-group">
                                <label>Password</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                    </div>
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           name="password">
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Input Phone -->
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="number" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}">
                            </div>

                            <!-- Input Roles -->
                            <div class="form-group">
                                <label class="form-label">Roles</label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="roles" value="ADMIN" class="selectgroup-input"
                                               {{ old('roles', $user->roles) == 'ADMIN' ? 'checked' : '' }}>
                                        <span class="selectgroup-button">ADMIN</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="roles" value="SKPD" class="selectgroup-input"
                                               {{ old('roles', $user->roles) == 'SKPD' ? 'checked' : '' }}>
                                        <span class="selectgroup-button">SKPD</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="roles" value="USER" class="selectgroup-input"
                                               {{ old('roles', $user->roles) == 'USER' ? 'checked' : '' }}>
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
