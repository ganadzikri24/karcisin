@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">👑 Developer Dashboard</h2>
        <span class="badge bg-warning text-dark px-3 py-2">Mode Admin</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="bi bi-hourglass-split me-2"></i> Daftar Pengajuan Event (Menunggu Approval)
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Banner</th>
                            <th>Nama Event</th>
                            <th>Kategori</th>
                            <th>Creator</th>
                            <th>Tgl Diajukan</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingEvents as $event)
                            <tr>
                                <td class="ps-4">
                                    <img src="{{ asset('storage/'.$event->banner) }}" width="80" height="50" class="rounded object-fit-cover shadow-sm">
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $event->name }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y, H:i') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark bg-opacity-10 border border-info px-3">{{ $event->category }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-secondary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; font-size: 12px;">
                                            {{ substr($event->creator->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="small fw-bold">{{ $event->creator->name }}</div>
                                            <div class="small text-muted" style="font-size: 10px;">{{ $event->creator->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted small">
                                    {{ $event->created_at->diffForHumans() }}
                                </td>
                                <td class="text-end pe-4">
                                    <!-- Tombol Approve -->
                                    <form action="{{ route('developer.approve', $event->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menyetujui event ini agar TAYANG di publik?');">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 fw-bold shadow-sm">
                                            <i class="bi bi-check-lg me-1"></i> Approve
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="mb-2"><i class="bi bi-clipboard-check display-4 opacity-25"></i></div>
                                    <h5 class="fw-bold">Tidak ada pengajuan baru.</h5>
                                    <p class="small mb-0">Semua event sudah direview atau belum ada creator yang mengajukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection