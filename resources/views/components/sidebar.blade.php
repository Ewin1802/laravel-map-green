<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="">POS WKDH BOLTARA</a>
        </div>
        {{-- <div class="sidebar-brand">
            <a href="index.html">
                <img src="{{ asset('img/logo_usaha.png') }}" alt="Logo Usaha" style="height: 60px;">
            </a>
        </div> --}}

        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">WKDH</a>
        </div>



        <ul class="sidebar-menu">

            <li class="menu-header">Data Umum</li>

            <li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="fab fa-windows"></i><span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item {{ Request::is('user*') ? 'active' : '' }}">
                    <a href="{{ route('user.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <span>User</span>
                    </a>
            </li>

            @if (auth()->user()->role == 'admin')
                <li class="menu-header">Data Akuntansi</li>

                <li class="nav-item dropdown {{ Request::is('kegiatan*', 'program*', 'organisas*', 'subkeg*', 'sumberda*', 'rekbelanja*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fas fa-cogs"></i>
                        <span>Kode Rekening</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="{{ Request::is('program*') ? 'active' : '' }}">
                            <a href="{{ route('programs.index') }}" class="nav-link">
                                <i class="fas fa-users"></i><span>Program</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('kegiatan*') ? 'active' : '' }}">
                            <a href="{{ route('kegiatans.index') }}" class="nav-link">
                                <i class="fas fa-users"></i><span>Kegiatan</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('organisas*') ? 'active' : '' }}">
                            <a href="{{ route('organisasis.index') }}" class="nav-link">
                                <i class="fas fa-users"></i><span>Organisasi</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('subkeg*') ? 'active' : '' }}">
                            <a href="{{ route('subkegiatans.index') }}" class="nav-link">
                                <i class="fas fa-users"></i><span>Sub Kegiatan</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('sumberda*') ? 'active' : '' }}">
                            <a href="{{ route('sumberdanas.index') }}" class="nav-link">
                                <i class="fas fa-users"></i><span>Sumber Dana</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('rekbelanja*') ? 'active' : '' }}">
                            <a href="{{ route('rekbelanjas.index') }}" class="nav-link">
                                <i class="fas fa-users"></i><span>Rekening Belanja</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            <li class="nav-item dropdown {{ Request::is('pemakaiandana*','dana*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-balance-scale"></i>
                    <span>Penatausahaan</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('dana*') ? 'active' : '' }}">
                        <a href="{{ route('danas.index') }}" class="nav-link">
                            <i class="fas fa-chart-line"></i><span>Anggaran</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('pemakaiandana*') ? 'active' : '' }}">
                        <a href="{{ route('pemakaiandanas.index') }}" class="nav-link">
                            <i class="fas fa-chart-line fa-rotate-180"></i><span>Realisasi</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="{{ Request::is('/laporan/realisas*') ? 'active' : '' }}">
                <a href="{{ route('laporan.realisasi') }}" class="nav-link">
                    <i class="fas fa-print"></i><span>Laporan</span>
                </a>
            </li>

        </ul>


        <div class="hide-sidebar-mini mt-4 mb-4 p-3">
            <a href="https://www.instagram.com/ewin.lntp/"
               class="btn btn-primary btn-lg btn-block btn-icon-split">
               <i class="fas fa-rocket"></i> Tentang Kami
            </a>
        </div>
    </aside>
</div>
