@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Konfirmasi & Lengkapi Data Pemesanan</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-light border d-flex align-items-center" role="alert">
                        <img src="{{ $event->banner ? asset('storage/'.$event->banner) : 'https://via.placeholder.com/100' }}" 
                             class="rounded me-3" style="width: 80px; height: 60px; object-fit: cover;">
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $event->name }}</h6>
                            <small class="text-muted">Rp {{ number_format($event->price, 0, ',', '.') }} / tiket</small>
                        </div>
                    </div>

                    <form action="{{ route('transaction.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        <h6 class="fw-bold mt-4 mb-3">1. Data Diri Pembeli</h6>
                        
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap (Sesuai KTP)</label>
                            <input type="text" name="customer_name" class="form-control" value="{{ Auth::user()->name }}" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Email Aktif</label>
                                <input type="email" name="customer_email" class="form-control" value="{{ Auth::user()->email }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nomor WhatsApp / HP</label>
                                <input type="number" name="customer_phone" class="form-control" placeholder="08xxxxxxxxxx" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor KTP / NIK</label>
                            <input type="number" name="customer_nik" class="form-control" placeholder="16 digit NIK" required>
                            <small class="text-muted">*Wajib diisi untuk validasi saat penukaran tiket.</small>
                        </div>

                        <h6 class="fw-bold mt-4 mb-3">2. Detail Pembayaran</h6>

                        <div class="mb-3">
                            <label class="form-label">Jumlah Tiket</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" min="1" max="{{ $event->quota }}" value="1" required>
                        </div>

                        <div class="alert alert-info d-flex justify-content-between align-items-center">
                            <strong>Total Bayar:</strong>
                            <h3 class="mb-0 text-primary">Rp <span id="totalPrice">{{ number_format($event->price, 0, ',', '.') }}</span></h3>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Bank Pengirim</label>
                                <select name="bank_name" class="form-select" required>
                                    <option value="">-- Pilih Bank --</option>
                                    <option value="BCA">BCA</option>
                                    <option value="BRI">BRI</option>
                                    <option value="Mandiri">Mandiri</option>
                                    <option value="BNI">BNI</option>
                                    <option value="Dana/Gopay/OVO">E-Wallet (Dana/Gopay)</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Upload Bukti Transfer</label>
                                <input type="file" name="payment_proof" class="form-control" required>
                            </div>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-success w-100 btn-lg">Bayar Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const price = {{ $event->price }};
    const qtyInput = document.getElementById('quantity');
    const totalSpan = document.getElementById('totalPrice');

    qtyInput.addEventListener('input', function() {
        let qty = this.value;
        if(qty < 1) qty = 1;
        let total = price * qty;
        totalSpan.innerText = new Intl.NumberFormat('id-ID').format(total);
    });
</script>
@endsection