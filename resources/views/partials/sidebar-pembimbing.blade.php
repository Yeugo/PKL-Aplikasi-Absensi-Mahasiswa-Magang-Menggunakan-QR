{{-- <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <!-- Profil User -->
        <div class="d-flex flex-column align-items-center pb-3 border-bottom profile-bg-dark">
            @php
                // Mengambil relasi pembimbing berdasarkan user yang login
                $pembimbing = auth()->user()->pembimbing;
            @endphp

            <!-- Menampilkan Foto Profil -->
            <img 
                src="{{ $pembimbing && $pembimbing->foto ? asset('storage/' . $pembimbing->foto) : asset('storage/default-profile.png') }}" 
                alt="Profile Photo" 
                class="rounded-circle mb-2 border border-3"  
                style="width: 80px; height: 80px; object-fit: cover;">
            
            <!-- Menampilkan Nama Pengguna -->
            <h6 class="text-center fw-bold">
                {{ $pembimbing ? $pembimbing->name : 'Nama Tidak Tersedia' }}
            </h6>
            <span class="text-muted">
                {{ auth()->user()->email }}
            </span>
        </div>
        <ul class="nav flex-column">
            @if (auth()->user()->isAdmin() or auth()->user()->isOperator())
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}" aria-current="page"
                    href="{{ route('dashboard.index') }}">
                    <i class="bi bi-house-door me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('bidangs.*') ? 'active' : '' }}"
                    href="{{ route('bidangs.index') }}">
                    <i class="bi bi-archive me-2"></i>
                    Bidang / Divisi
                </a>
            </li>
        </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                    href="{{ route('users.index') }}">
                    <i class="bi bi-people me-2"></i>
                    User
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('peserta.*') ? 'active' : '' }}"
                    href="{{ route('peserta.index') }}">
                    <i class="bi bi-person-lines-fill me-2"></i>
                    Peserta Magang
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pembimbing.*') ? 'active' : '' }}"
                    href="{{ route('pembimbing.index') }}">
                    <i class="bi bi-person-workspace me-2"></i>
                    Pembimbing
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('absensi.*') ? 'active' : '' }}"
                    href="{{ route('absensi.index') }}">
                    <i class="bi bi-calendar-check me-2"></i>
                    Absensi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('kehadiran.*') ? 'active' : '' }}"
                    href="{{ route('kehadiran.index') }}">
                    <i class="bi bi-journal-check me-2"></i>
                    Data Kehadiran
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('kegiatan.*') ? 'active' : '' }}"
                    href="{{ route('kegiatan.index') }}">
                    <i class="bi bi-list-task me-2"></i>
                    Data Kegiatan
                </a>
            </li>
            @endif
        </ul>

        <form action="{{ route('auth.logout') }}" method="post"
            onsubmit="return confirm('Apakah anda yakin ingin keluar?')">
            @method('DELETE')
            @csrf
            <button class="w-full mt-4 d-block bg-transparent border-0 fw-bold text-danger px-3">Keluar</button>
        </form>
    </div>
</nav> --}}
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon ">
            <img src="{{ asset('storage/assets/logobjm.png') }}" alt="Dashboard Icon" style="width: 70px; height: auto; margin-right: -10px ">
        </div>
        <div class="sidebar-brand-text">Absensi DKP3</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    @if (auth()->user()->isPembimbing())
    <li class="nav-item {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard.index') }}">
            <i class="bi bi-house-door"></i>
            <span>{{ __('Dashboard') }}</span></a>
    </li>
    

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        {{ __('MENU') }}
    </div>

    <!-- Nav Item - Akun -->
    <li class="nav-item {{ request()->routeIs('account.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('account.index') }}">
            <i class="bi bi-person-gear"></i>
            <span>{{ __('Akun') }}</span>
        </a>
    </li>

    <!-- Nav Item - Peserta Magang -->
    <li class="nav-item {{ request()->routeIs('peserta.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('peserta.index') }}">
            <i class="bi bi-person-lines-fill"></i>
            <span>{{ __('Peserta Magang') }}</span>
        </a>
    </li>

    <!-- Nav Item - Absensi -->
    <li class="nav-item {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('absensi.index') }}">
            <i class="bi bi-calendar-check"></i>
            <span>{{ __('Absensi') }}</span>
        </a>
    </li>

    <!-- Nav Item - Kehadiran -->
    <li class="nav-item {{ request()->routeIs('kehadiran.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('kehadiran.index') }}">
            <i class="bi bi-journal-check"></i>
            <span>{{ __('Kehadiran') }}</span>
        </a>
    </li>

    <!-- Nav Item - Kegiatan -->
    <li class="nav-item {{ request()->routeIs('kegiatan.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('kegiatan.index') }}">
            <i class="bi bi-journal-bookmark-fill"></i>
            <span>{{ __('Kegiatan') }}</span>
        </a>
    </li>

     <!-- Nav Item - Penilaian -->
    <li class="nav-item {{ request()->routeIs('nilai.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('nilai.index') }}">
            <i class="bi bi-award"></i>
            <span>{{ __('Nilai') }}</span>
        </a>
    </li>
    @endif

    <!-- Nav Item - Logout -->
    <li class="nav-item">

        {{-- <form action="{{ route('auth.logout') }}" method="post"
        onsubmit="return confirm('Apakah anda yakin ingin keluar?')">
        @method('DELETE')
        @csrf
        <button class="w-full mt-4 d-block bg-transparent border-0 fw-bold text-danger px-3">Keluar</button>
        </form> --}}
        <a class="nav-link" href="#" onclick="event.preventDefault(); if(confirm('Apakah anda yakin ingin keluar?')) { document.getElementById('logout-form').submit(); }">
            <i class="bi bi-box-arrow-right"></i>
            <span>{{ __('Keluar') }}</span>
        </a>
        <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
            @method('DELETE')
            @csrf
        </form>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>