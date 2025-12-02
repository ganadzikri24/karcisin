@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Tiket Saya</h2>

    @forelse($tickets as $ticket)
        <div class="card mb-3 shadow-sm border-0">
            <div class="row g-0">
                <!-- Bagian Kiri: Poster Kecil -->
                <div class="col-md-2 bg-light d-flex align-items-center justify-content-center">
                    <img src="{{ $ticket->transaction->event->banner ? asset('storage/'.$ticket->transaction->event->banner) : 'https://via.placeholder.com/150' }}" 
                         class="img-fluid rounded-start" style="height: 100%; object-fit: cover;">
                </div>
                
                <!-- Bagian Tengah: Detail Event -->
                <div class="col-md-7">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $ticket->transaction->event->name }}</h5>
                        <p class="card-text text-muted mb-1">
                            📅 {{ \Carbon\Carbon::parse($ticket->transaction->event->event_date)->format('d F Y, H:i') }} WIB
                        </p>
                        <p class="card-text text-muted mb-2">
                            📍 {{ $ticket->transaction->event->location }}
                        </p>
                        <span class="badge bg-success">Status: Valid / Lunas</span>
                    </div>
                </div>

                <!-- Bagian Kanan: Kode Tiket & Download -->
                <div class="col-md-3 border-start bg-light d-flex flex-column align-items-center justify-content-center p-3">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $ticket->unique_code }}" alt="QR Code" class="img-fluid mb-3">
                    
                    <div class="mb-2 text-center">
                        <small class="text-muted">Kode:</small>
                        <h5 class="fw-bold font-monospace">{{ $ticket->unique_code }}</h5>
                    </div>

                    <!-- TOMBOL DOWNLOAD PDF (INI YANG BARU) -->
                    <a href="{{ route('ticket.download', $ticket->id) }}" class="btn btn-sm btn-danger w-100">
                        Download PDF
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">
            Anda belum memiliki tiket aktif. <a href="{{ route('welcome') }}">Cari event yuk!</a>
        </div>
    @endforelse
</div>
@endsection