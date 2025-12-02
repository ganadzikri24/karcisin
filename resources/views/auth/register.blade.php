@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-primary">Gabung Karcis.in</h3>
                <p class="text-muted">Buat akun untuk mulai membeli atau membuat event seru.</p>
            </div>

            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- PILIHAN TIPE AKUN (STICKY) -->
                        <div class="alert alert-primary bg-primary bg-opacity-10 border-0 rounded-3 mb-4 d-flex align-items-center">
                            <i class="bi bi-info-circle-fill text-primary fs-4 me-3"></i>
                            <div class="w-100">
                                <label class="fw-bold text-primary small text-uppercase mb-1">Saya mendaftar sebagai:</label>
                                <select id="role" class="form-select border-0 shadow-sm fw-bold text-dark" name="role" required onchange="toggleCreatorFields()">
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>👤 Pembeli Tiket (User Biasa)</option>
                                    <option value="creator" {{ old('role') == 'creator' ? 'selected' : '' }}>📢 Event Creator (Panitia Event)</option>
                                </select>
                            </div>
                        </div>

                        <!-- DATA UMUM -->
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted">Nama Lengkap</label>
                                <input type="text" class="form-control bg-light border-0 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Nama Lengkap Anda">
                                @error('name')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted">Email</label>
                                <input type="email" class="form-control bg-light border-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="email@contoh.com">
                                @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- DATA KHUSUS CREATOR (STICKY DATA) -->
                        <div id="creator-fields" class="mt-4 p-3 bg-light rounded-3 border border-dashed" style="display: none;">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-check text-success"></i> Verifikasi Panitia</h6>
                            
                            <!-- Tampilkan Error Global untuk bagian ini jika ada -->
                            @if($errors->has('phone') || $errors->has('nik') || $errors->has('address'))
                                <div class="alert alert-danger small py-2">
                                    Mohon lengkapi data verifikasi dengan benar.
                                </div>
                            @endif

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">No. WhatsApp</label>
                                    <input type="number" id="phone" name="phone" value="{{ old('phone') }}" class="form-control border-0 bg-white @error('phone') is-invalid @enderror" placeholder="08xxx">
                                    @error('phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">NIK / KTP (Wajib 16 Digit)</label>
                                    <input type="number" id="nik" name="nik" value="{{ old('nik') }}" class="form-control border-0 bg-white @error('nik') is-invalid @enderror" placeholder="16 Digit">
                                    @error('nik') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label small text-muted">Alamat Lengkap</label>
                                    <textarea id="address" name="address" class="form-control border-0 bg-white @error('address') is-invalid @enderror" rows="2">{{ old('address') }}</textarea>
                                    @error('address') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- PASSWORD -->
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Password</label>
                                <input type="password" class="form-control bg-light border-0 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
                                @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Konfirmasi Password</label>
                                <input type="password" class="form-control bg-light border-0" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password">
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary w-100 btn-lg rounded-pill fw-bold shadow-sm">
                                Daftar Sekarang
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <small class="text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="fw-bold text-decoration-none">Masuk</a></small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleCreatorFields() {
        var role = document.getElementById('role').value;
        var fields = document.getElementById('creator-fields');
        var phone = document.getElementById('phone');
        var nik = document.getElementById('nik');
        var address = document.getElementById('address');

        if (role === 'creator') {
            fields.style.display = 'block';
            phone.required = true;
            nik.required = true;
            address.required = true;
        } else {
            fields.style.display = 'none';
            phone.required = false;
            nik.required = false;
            address.required = false;
        }
    }

    // Jalankan saat halaman dimuat (agar kalau ada error, form tetap terbuka)
    document.addEventListener("DOMContentLoaded", function() {
        toggleCreatorFields();
    });
</script>
@endsection