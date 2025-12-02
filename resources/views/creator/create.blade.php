@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold">Ajukan Event Baru</div>
                <div class="card-body">
                    
                    <!-- ALERT ERROR UMUM -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="alert alert-warning small">
                        <i class="bi bi-info-circle"></i> Event Anda akan berstatus <strong>Pending</strong> dan menunggu persetujuan Developer sebelum tampil.
                    </div>

                    <form action="{{ route('event.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="fw-bold">Nama Event</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Contoh: Konser Musik Galau">
                            @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <!-- INPUT KATEGORI -->
                        <div class="mb-3">
                            <label class="fw-bold">Kategori Event</label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Konser" {{ old('category') == 'Konser' ? 'selected' : '' }}>Konser Musik</option>
                                <option value="Seminar" {{ old('category') == 'Seminar' ? 'selected' : '' }}>Seminar / Webinar</option>
                                <option value="Workshop" {{ old('category') == 'Workshop' ? 'selected' : '' }}>Workshop / Pelatihan</option>
                                <option value="Wisata" {{ old('category') == 'Wisata' ? 'selected' : '' }}>Tempat Wisata / Healing</option>
                                <option value="Olahraga" {{ old('category') == 'Olahraga' ? 'selected' : '' }}>Olahraga</option>
                                <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('category') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Deskripsi Lengkap</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required placeholder="Jelaskan detail acara Anda...">{{ old('description') }}</textarea>
                            @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Tanggal & Jam</label>
                                <input type="datetime-local" name="event_date" class="form-control @error('event_date') is-invalid @enderror" value="{{ old('event_date') }}" required>
                                @error('event_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Lokasi</label>
                                <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}" required placeholder="Nama Gedung / Link Zoom">
                                @error('location') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Harga Tiket (Rp)</label>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required placeholder="0 jika gratis">
                                @error('price') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Kuota Tiket</label>
                                <input type="number" name="quota" class="form-control @error('quota') is-invalid @enderror" value="{{ old('quota') }}" required>
                                @error('quota') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Banner Event (Gambar)</label>
                            <input type="file" name="banner" class="form-control @error('banner') is-invalid @enderror" required>
                            <small class="text-muted">Format: JPG, PNG. Max 2MB.</small>
                            @error('banner') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" required id="agree">
                            <label class="form-check-label small text-secondary" for="agree">
                                Saya menyetujui ketentuan dan biaya layanan pembuatan event di Karcis.in.
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">Ajukan Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection