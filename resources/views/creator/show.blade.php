@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('home') }}" class="btn btn-outline-secondary mb-3">&larr; Kembali ke Dashboard</a>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="{{ $event->banner ? asset('storage/'.$event->banner) : 'https://via.placeholder.com/400x200' }}" class="card-img-top">
                <div class="card-body">
                    <h4 class="card-title">{{ $event->name }}</h4>
                    <p class="text-muted mb-2">
                        📅 {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y, H:i') }} WIB
                    </p>
                    <p class="text-muted mb-3">📍 {{ $event->location }}</p>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <strong>Harga:</strong>
                        <span class="text-success">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <strong>Sisa Kuota:</strong>
                        <span>{{ $event->quota }} Tiket</span>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-warning w-100 mb-2">Edit Event (Coming Soon)</button>
                        <button class="btn btn-danger w-100">Hapus Event (Coming Soon)</button>
                    </div>
                </div>
            </div>
        </div>

<div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Daftar Pembeli Tiket</h5>
                </div>
                <div class="card-body">
                    @if($event->transactions->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <p class="mb-0">Belum ada transaksi pembelian tiket.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Pembeli</th>
                                        <th>Jml</th>
                                        <th>Total</th>
                                        <th>Bukti</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($event->transactions as $transaction)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $transaction->user->name }}</div>
                                            <small class="text-muted">{{ $transaction->user->email }}</small>
                                        </td>
                                        <td>{{ $transaction->quantity }}</td>
                                        <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                        <td>
                                            <a href="{{ asset('storage/'.$transaction->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                Lihat
                                            </a>
                                        </td>
                                        <td>
                                            @if($transaction->status == 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif($transaction->status == 'paid')
                                                <span class="badge bg-success">Lunas</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($transaction->status == 'pending')
                                                <form action="{{ route('transaction.approve', $transaction->id) }}" method="POST" onsubmit="return confirm('Yakin terima pembayaran ini?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                                </form>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled>Selesai</button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
@endsection