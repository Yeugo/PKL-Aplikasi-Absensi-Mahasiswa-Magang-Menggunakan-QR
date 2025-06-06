<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon ">
            <img src="{{ asset('storage/assets/logobjm.png') }}" alt="Home Icon" style="width: 30px; height: auto; margin-right: 10px ">
        </div>
        <div class="sidebar-brand-text  mr-2">Absensi DKP3</div>
    </a>

    <!-- Nav Item - Absensi -->
    @if (auth()->user()->isUser())
    <li class="nav-item mt-5 {{ request()->routeIs('home.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('home.index') }}">
            <i class="bi bi-house-door"></i>
            <span>{{ __('Absensi') }}</span></a>
    </li>

    <!-- Nav Item - Akun -->
    <li class="nav-item {{ request()->routeIs('account.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('account.index') }}">
            <i class="bi bi-person-gear"></i>
            <span>{{ __('Akun') }}</span>
        </a>
    </li>

    <!-- Nav Item - Kegiatan -->
    <li class="nav-item {{ request()->routeIs('kegiatan.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('kegiatan.index') }}">
            <i class="bi bi-archive"></i>
            <span>{{ __('Catatan Kegiatan') }}</span>
        </a>
    </li>

    <!-- Nav Item - Kegiatan -->
    <li class="nav-item">
        {{-- Ini adalah menu baru untuk Sertifikat --}}
        <a class="nav-link {{ request()->routeIs('home.sertifikat.indexPeserta') ? 'active' : '' }}" href="{{ route('home.sertifikat.indexPeserta') }}">
            <i class="bi bi-award me-2"></i> Sertifikat
        </a>
    </li>


    @endif
    <li class="nav-item">
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