@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active">{{ $event->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <img src="{{ asset('storage/'.$event->banner) }}" class="img-fluid rounded-4 shadow-sm w-100 mb-4">
            
            <h2 class="fw-bold mb-3">{{ $event->name }}</h2>
            <span class="badge bg-primary fs-6 mb-3">{{ $event->category }}</span>
            
            <h5 class="fw-bold mt-4">Deskripsi Event</h5>
            <p style="white-space: pre-line;">{{ $event->description }}</p>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 sticky-top" style="top: 100px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Detail Tiket</h5>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Tanggal & Waktu</small>
                        <span class="fw-bold">{{ \Carbon\Carbon::parse($event->event_date)->format('d F Y, H:i') }} WIB</span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Lokasi</small>
                        <span class="fw-bold">{{ $event->location }}</span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Harga Tiket</small>
                        <h3 class="text-primary fw-bold">Rp {{ number_format($event->price, 0, ',', '.') }}</h3>
                    </div>

                    <hr>

                    <a href="{{ route('event.checkout', $event->id) }}" class="btn btn-primary w-100 btn-lg rounded-pill fw-bold">
                        Beli Tiket Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection