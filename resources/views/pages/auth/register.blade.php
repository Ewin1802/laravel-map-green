@extends('layouts.auth')

@section('title', 'Register')
{{-- Favicon - Logo web disamping title --}}
<link rel="icon" href="{{ asset('img/logo_pemda.png') }}" type="image/png">
@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet"
        href="{{ asset('library/selectric/public/selectric.css') }}">

@endpush

@section('main')
    <div class="card card-primary">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="text-right">Register</h4>
            <!-- Section for the logos, they will be side by side -->
            <div class="d-flex align-items-center">
                <!-- Logo BPKPD -->
                <img src="{{ asset('img/logo_bpkpd.png') }}" alt="Logo BPKPD" style="height: 50px;">
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{route('register')}}">
                @csrf
                <div class="form-group">
                    <label for="frist_name">Nama (sesuai Data Pegawai)</label>
                    <input id="frist_name"
                        type="text"
                        class="form-control @error('name')
                        is-invalid
                        @enderror"
                        name="name"
                        placeholder="Masukkan Nama Lengkap"
                        autofocus>
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                </div>



                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email"
                        type="email"
                        class="form-control @error('email')
                        is-invalid
                        @enderror"
                        name="email"
                        placeholder="Masukkan email aktif">
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">Nomor Telepon</label>
                    <input id="phone"
                        type="text"
                        {{-- Membatasi input hanya untuk angka --}}
                        pattern="[0-9]*"
                        {{-- Menampilkan keyboard numerik di perangkat mobile --}}
                        inputmode="numeric"
                        class="form-control @error('phone') is-invalid @enderror"
                        name="phone"
                        {{-- Membatasi panjang maksimal karakter --}}
                        maxlength="15"
                        placeholder="Masukkan nomor telepon">
                    @error('phone')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="password"
                        class="d-block">Password</label>
                    <input id="password"
                        type="password"
                        class="form-control pwstrength @error('password')
                            is-invalid
                        @enderror"
                        data-indicator="pwindicator"
                        name="password"
                        placeholder="Masukkan Passowrd">
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    <div id="pwindicator"
                        class="pwindicator">
                        <div class="bar"></div>
                        <div class="label"></div>
                    </div>
                </div>

                <div class="form-group ">
                    <label for="password2"
                        class="d-block">Password Confirmation</label>
                    <input id="password2"
                        type="password"

                        class="form-control @error('password_confirmation')
                            is-invalid
                        @enderror"
                        name="password_confirmation"
                        placeholder="Ulangi Passowrd">
                </div>
                @error('password_confirmation')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>

                @enderror

                <div class="form-group">
                    <button type="submit"
                        class="btn btn-primary btn-lg btn-block">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('library/jquery.pwstrength/jquery.pwstrength.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/auth-register.js') }}"></script>
@endpush
