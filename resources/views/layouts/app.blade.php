<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Karcis.in') }}</title>

    <!-- Icons Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-karcisin sticky-top">
            <div class="container">
                <!-- 1. LOGO KIRI -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('img/karcisin_logo_panjang.png') }}" 
                         alt="Karcis.in" 
                         style="height: 40px; width: auto;"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <span style="display:none; font-weight:bold; font-size:24px; color:#0118d8;">Karcis.in</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <!-- 2. MENU TENGAH -->
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">
                                <i class="bi bi-compass"></i> Jelajahi Event
                            </a>
                        </li>
                        
                        <!-- HANYA TAMPIL JIKA LOGIN SEBAGAI CREATOR -->
                        @if(Auth::check() && Auth::user()->role == 'creator')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('event.create') }}">
                                    <i class="bi bi-plus-circle"></i> Buat Event
                                </a>
                            </li>
                        @endif
                        
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-search"></i> Cari Event
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('about') }}">Tentang Karcisin</a>
                        </li>
                    </ul>

                    <!-- 3. MENU KANAN (User / Guest) -->
                    <ul class="navbar-nav">
                        @guest
                            <li class="nav-item me-2">
                                <a class="btn btn-outline-primary btn-auth" href="{{ route('login') }}">Masuk</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-primary btn-auth text-white" href="{{ route('register') }}">Daftar</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle fw-bold" href="#" role="button" data-bs-toggle="dropdown">
                                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=0118d8&color=fff" class="rounded-circle me-1" width="30">
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end shadow border-0">
                                    
                                    <!-- KHUSUS DEVELOPER (ganadzikri@gmail.com) - SUDAH DIPERBAIKI -->
                                    @if(Auth::user()->email == 'ganadzikri@gmail.com')
                                        <div class="dropdown-header text-primary fw-bold">👑 MODE DEVELOPER</div>
                                        <!-- Perbaikan: href mengarah ke route developer.index -->
                                        <a class="dropdown-item" href="{{ route('developer.index') }}">
                                            <i class="bi bi-shield-lock"></i> Approval Event
                                        </a>
                                        <hr class="dropdown-divider">
                                    @endif

                                    <!-- KHUSUS CREATOR -->
                                    @if(Auth::user()->role == 'creator')
                                        <a class="dropdown-item" href="{{ route('home') }}">
                                            <i class="bi bi-speedometer2"></i> Dashboard Creator
                                        </a>
                                        <a class="dropdown-item" href="{{ route('scan.index') }}">
                                            <i class="bi bi-qr-code-scan"></i> Scan Validasi
                                        </a>
                                        <hr class="dropdown-divider">
                                    @endif

                                    <!-- USER BIASA -->
                                    <a class="dropdown-item" href="{{ route('my.tickets') }}">
                                        <i class="bi bi-ticket-perforated"></i> Tiket Saya
                                    </a>

                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right"></i> Keluar
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>

        <!-- FOOTER -->
        <footer class="footer-karcisin">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <h4 class="fw-bold mb-3">Karcis.in</h4>
                        <p class="text-white-50">Platform E-Ticketing Event Kampus Terlengkap & Ekonomis.</p>
                    </div>
                    <div class="col-md-4 mb-4">
                        <h5 class="fw-bold mb-3">Ikuti Kami</h5>
                        <ul class="list-unstyled text-white-50">
                            <li class="mb-2"><i class="bi bi-instagram me-2"></i> @karcis.in</li>
                            <li class="mb-2"><i class="bi bi-envelope me-2"></i> support@karcis.in</li>
                        </ul>
                    </div>
                    <div class="col-md-4 mb-4">
                        <h5 class="fw-bold mb-3">Lokasi</h5>
                        <p class="text-white-50">Kampus Dramaga, Bogor, Jawa Barat.</p>
                    </div>
                </div>
                <div class="border-top border-secondary pt-4 text-center text-white-50">
                    &copy; 2025 Karcis.in - All Rights Reserved.
                </div>
            </div>
        </footer>
    </div>
</body>
</html>