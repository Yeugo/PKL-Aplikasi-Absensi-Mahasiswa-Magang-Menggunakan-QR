<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
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
                class="rounded-circle mb-2" 
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
                    <span data-feather="home" class="align-text-bottom"></span>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('bidangs.*') ? 'active' : '' }}"
                    href="{{ route('bidangs.index') }}">
                    <span data-feather="archive" class="align-text-bottom"></span>
                    Bidang / Divisi
                </a>
            </li>
        </li>
        {{-- <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('positions.*') ? 'active' : '' }}"
                href="{{ route('positions.index') }}">
                <span data-feather="tag" class="align-text-bottom"></span>
                Jabatan / Posisi
            </a>
        </li> --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                    href="{{ route('users.index') }}">
                    <span data-feather="users" class="align-text-bottom"></span>
                    User
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('peserta.*') ? 'active' : '' }}"
                    href="{{ route('peserta.index') }}">
                    <span data-feather="users" class="align-text-bottom"></span>
                    Peserta Magang
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pembimbing.*') ? 'active' : '' }}"
                    href="{{ route('pembimbing.index') }}">
                    <span data-feather="users" class="align-text-bottom"></span>
                    Pembimbing
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('holidays.*') ? 'active' : '' }}"
                    href="{{ route('holidays.index') }}">
                    <span data-feather="calendar" class="align-text-bottom"></span>
                    Hari Libur
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('absensi.*') ? 'active' : '' }}"
                    href="{{ route('absensi.index') }}">
                    <span data-feather="clipboard" class="align-text-bottom"></span>
                    Absensi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('kehadiran.*') ? 'active' : '' }}"
                    href="{{ route('kehadiran.index') }}">
                    <span data-feather="clipboard" class="align-text-bottom"></span>
                    Data Kehadiran
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
</nav>