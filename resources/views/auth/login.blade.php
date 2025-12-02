@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="text-center mb-4">
                <!-- Pastikan logo ada di public/img/karcisin_logotype.png -->
                <img src="{{ asset('img/karcisin_logotype.png') }}" 
                     alt="Logo" 
                     height="50" 
                     class="mb-3"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                
                <!-- Fallback text jika logo tidak ketemu -->
                <h2 style="display:none; color: #0118d8; font-weight: bold;">Karcis.in</h2>

                <h4 class="fw-bold mt-2">Selamat Datang Kembali!</h4>
                <p class="text-muted">Silakan masuk untuk melanjutkan.</p>
            </div>

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white text-center py-3" style="background-color: #0118d8 !important;">
                    <h5 class="mb-0 fw-bold">{{ __('Masuk Akun') }}</h5>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold small text-uppercase text-muted">{{ __('Alamat Email') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-envelope"></i></span>
                                <input id="email" type="email" class="form-control form-control-lg bg-light border-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="nama@email.com">
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <label for="password" class="form-label fw-bold small text-uppercase text-muted">{{ __('Password') }}</label>
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link p-0 text-decoration-none small" href="{{ route('password.request') }}" style="color: #0118d8;">
                                        {{ __('Lupa Password?') }}
                                    </a>
                                @endif
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-lock"></i></span>
                                <input id="password" type="password" class="form-control form-control-lg bg-light border-0 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="********">
                            </div>
                            @error('password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label text-muted" for="remember">
                                {{ __('Ingat Saya') }}
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm" style="background-color: #0118d8; border: none;">
                                {{ __('Masuk Sekarang') }}
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <p class="text-muted small">Belum punya akun? <a href="{{ route('register') }}" class="fw-bold text-decoration-none" style="color: #0118d8;">Daftar di sini</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection