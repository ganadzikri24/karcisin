@extends('layouts.app')

@section('content')
<!-- Hero Banner -->
<div class="container mt-4">
    <div class="bg-dark text-white rounded-4 p-5 text-center shadow" style="background: linear-gradient(to right, #0118d8, #000);">
        <h1 class="fw-bold display-4">Temukan Event Impianmu</h1>
        <p class="lead">Konser, Webinar, hingga Liburan ada di sini.</p>
    </div>
</div>

<!-- EVENT UNGGULAN -->
<div class="container mt-5">
    <h3 class="fw-bold text-primary mb-3">Event Unggulan</h3>
    <div class="scroll-horizontal">
        @if(isset($unggulan) && count($unggulan) > 0)
            @foreach($unggulan as $event)
                <div class="item">
                    <a href="{{ route('public.event.show', $event->id) }}" class="text-decoration-none text-dark">
                        <div class="card card-event h-100">
                            <img src="{{ asset('storage/'.$event->banner) }}" class="card-img-top">
                            <div class="card-body">
                                <h6 class="fw-bold text-truncate">{{ $event->name }}</h6>
                                <span class="badge bg-primary">{{ $event->category }}</span>
                                <p class="text-muted small mt-2 mb-0">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        @else
            <p class="text-muted">Belum ada event unggulan.</p>
        @endif
    </div>
</div>

<!-- KONSER -->
<div class="container mt-5">
    <h3 class="fw-bold mb-3">Konser Musik</h3>
    <div class="scroll-horizontal">
        @if(isset($konser) && count($konser) > 0)
            @foreach($konser as $event)
                <div class="item">
                    <a href="{{ route('public.event.show', $event->id) }}" class="text-decoration-none text-dark">
                        <div class="card card-event h-100">
                            <img src="{{ asset('storage/'.$event->banner) }}" class="card-img-top">
                            <div class="card-body">
                                <h6 class="fw-bold text-truncate">{{ $event->name }}</h6>
                                <p class="text-primary fw-bold mb-0">Rp {{ number_format($event->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        @else
            <p class="text-muted">Belum ada konser.</p>
        @endif
    </div>
</div>

<!-- WISATA / HEALING -->
<div class="container mt-5 mb-5">
    <h3 class="fw-bold mb-3">Ayo Healing!</h3>
    <div class="scroll-horizontal">
        @if(isset($wisata) && count($wisata) > 0)
            @foreach($wisata as $event)
                <div class="item">
                    <a href="{{ route('public.event.show', $event->id) }}" class="text-decoration-none text-dark">
                        <div class="card card-event h-100">
                            <img src="{{ asset('storage/'.$event->banner) }}" class="card-img-top">
                            <div class="card-body">
                                <h6 class="fw-bold text-truncate">{{ $event->name }}</h6>
                                <p class="text-muted small">{{ $event->location }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        @else
            <p class="text-muted">Belum ada paket wisata.</p>
        @endif
    </div>
</div>
@endsection