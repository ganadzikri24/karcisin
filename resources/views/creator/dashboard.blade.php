@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header Dashboard -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">Dashboard Event</h2>
            <p class="text-muted mb-0">Kelola event dan pantau penjualan tiket Anda.</p>
        </div>
        
        @if(Auth::user()->role == 'creator')
            <a href="{{ route('event.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                <i class="bi bi-plus-lg me-2"></i> Buat Event Baru
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Statistik Singkat -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar-check fs-1 me-3 opacity-50"></i>
                        <div>
                            <h5 class="mb-0 fw-bold">Total Event</h5>
                            <h2 class="fw-bold mb-0">{{ $myEvents->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="me-auto">
                        <h5 class="fw-bold text-dark mb-1">Status Akun</h5>
                        <span class="badge bg-success rounded-pill px-3">Terverifikasi</span>
                    </div>
                    <div class="text-end text-muted small">
                        <i class="bi bi-shield-check fs-4 text-primary d-block mb-1"></i>
                        {{ Auth::user()->email }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Event -->
    <h5 class="fw-bold mb-3 text-secondary">Event Saya</h5>
    
    <div class="row">
        @forelse($myEvents as $event)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden card-hover-effect">
                    <div class="position-relative">
                        <!-- Banner Event -->
                        <img src="{{ $event->banner ? asset('storage/'.$event->banner) : 'https://via.placeholder.com/400x200?text=No+Banner' }}" class="card-img-top" alt="{{ $event->name }}" style="height: 180px; object-fit: cover;">
                        
                        <!-- Badge Sisa Tiket -->
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-light text-dark shadow-sm">{{ $event->quota }} Tiket Tersisa</span>
                        </div>

                        <!-- Badge Status Approval (Penting untuk Poin 1) -->
                        <div class="position-absolute top-0 start-0 m-2">
                            @if($event->status == 'approved')
                                <span class="badge bg-success shadow-sm">Tayang (Approved)</span>
                            @elseif($event->status == 'rejected')
                                <span class="badge bg-danger shadow-sm">Ditolak</span>
                            @else
                                <span class="badge bg-warning text-dark shadow-sm">Menunggu Review</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-truncate">{{ $event->name }}</h5>
                        <p class="text-muted small mb-1">
                            <i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y, H:i') }} WIB
                        </p>
                        <!-- Menampilkan Kategori -->
                        <p class="text-muted small mb-3">
                            <i class="bi bi-tag me-1"></i> {{ $event->category ?? 'General' }}
                        </p>
                        
                        <hr class="my-3 border-light">
                        
                        <!-- TOMBOL AKSI -->
                        <div class="d-flex justify-content-between align-items-center gap-2">
                            <!-- Tombol Kelola -->
                            <a href="{{ route('event.show', $event->id) }}" class="btn btn-outline-primary btn-sm rounded-pill flex-fill">
                                Kelola
                            </a>

                            <!-- TOMBOL SCANNER BARU (Sesuai Poin 4) -->
                            <!-- Hanya muncul jika event sudah diapprove -->
                            @if($event->status == 'approved')
                                <a href="{{ route('event.scan', $event->id) }}" class="btn btn-dark btn-sm rounded-pill flex-fill">
                                    <i class="bi bi-qr-code-scan"></i> Scan
                                </a>
                            @else
                                <button class="btn btn-secondary btn-sm rounded-pill flex-fill" disabled title="Tunggu Approved">
                                    <i class="bi bi-lock"></i> Scan
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 py-5 text-center bg-light">
                    <div class="card-body">
                        <i class="bi bi-calendar-x fs-1 text-muted mb-3 d-block"></i>
                        <h5 class="fw-bold text-muted">Belum ada event.</h5>
                        <p class="text-muted small mb-4">Mulai buat event pertamamu sekarang dan raih audiens!</p>
                        @if(Auth::user()->role == 'creator')
                            <a href="{{ route('event.create') }}" class="btn btn-primary rounded-pill px-4">
                                + Buat Event Sekarang
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
    .card-hover-effect { transition: transform 0.2s; }
    .card-hover-effect:hover { transform: translateY(-5px); }
</style>
@endsection