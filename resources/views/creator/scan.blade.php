@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- KOLOM KIRI: KAMERA -->
        <div class="col-md-5 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-dark text-white text-center rounded-top-4 py-3">
                    <h5 class="mb-0 fw-bold">🎥 Kamera Scanner</h5>
                    <small>{{ $event->name }}</small>
                </div>
                <div class="card-body text-center bg-black p-0 overflow-hidden rounded-bottom-4 position-relative">
                    <div id="reader" style="width: 100%; min-height: 300px;"></div>
                    <div class="position-absolute bottom-0 w-100 text-white p-2 small bg-dark bg-opacity-75">
                        Pastikan QR Code terlihat jelas
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: TABEL RIWAYAT -->
        <div class="col-md-7">
            
            <!-- ALERT HASIL SCAN -->
            <div id="result-area" class="d-none mb-4">
                <div id="result-box" class="card border-0 shadow text-center p-4">
                    <div class="card-body">
                        <div id="icon-status" class="mb-3" style="font-size: 3rem;"></div>
                        <h2 class="fw-bold mb-1" id="scan-title"></h2>
                        <h4 class="text-primary fw-bold mb-3" id="scan-name"></h4>
                        <div class="alert alert-light border d-inline-block px-4 py-2 rounded-pill mb-0">
                            <i class="bi bi-info-circle me-1"></i> <span id="scan-message"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABEL RIWAYAT (PERSISTEN) -->
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-clock-history me-2 text-primary"></i> Data Pengunjung Masuk
                    </h5>
                    <span class="badge bg-primary rounded-pill">{{ $history->count() }} Orang</span>
                </div>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th width="25%">Waktu Scan</th>
                                <th width="50%">Nama Pengunjung</th>
                                <th width="25%">Status</th>
                            </tr>
                        </thead>
                        <tbody id="history-table-body">
                            <!-- Loop Data dari Database (Agar tidak hilang saat refresh) -->
                            @forelse($history as $ticket)
                                <tr>
                                    <td class="text-muted small">
                                        {{ $ticket->updated_at->format('H:i:s') }}
                                    </td>
                                    <td class="fw-bold">
                                        {{ $ticket->transaction->customer_name }}
                                        <div class="small text-muted" style="font-size: 0.75rem;">
                                            {{ $ticket->unique_code }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success"><i class="bi bi-check-lg"></i> Berhasil</span>
                                    </td>
                                </tr>
                            @empty
                                <tr id="empty-row">
                                    <td colspan="3" class="text-center text-muted py-5">
                                        Belum ada pengunjung yang scan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    const audioSuccess = new Audio('https://actions.google.com/sounds/v1/cartoon/cartoon_boing.ogg');
    const audioFail = new Audio('https://actions.google.com/sounds/v1/alarms/beep_short.ogg');

    function onScanSuccess(decodedText) {
        html5QrcodeScanner.clear();

        $.ajax({
            url: "{{ route('scan.verify') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                qr_code: decodedText,
                event_id: "{{ $event->id }}"
            },
            success: function(response) {
                showResult(response);

                // Restart Scanner
                setTimeout(() => {
                    $('#result-area').addClass('d-none');
                    html5QrcodeScanner.render(onScanSuccess);
                }, 2500);
            },
            error: function() {
                alert('Gagal koneksi ke server');
                html5QrcodeScanner.render(onScanSuccess);
            }
        });
    }

    function showResult(res) {
        $('#result-area').removeClass('d-none');
        
        if(res.status === 'success') {
            // SUKSES
            audioSuccess.play();
            $('#scan-title').text('SILAKAN MASUK').attr('class', 'fw-bold mb-1 text-success');
            $('#icon-status').html('<i class="bi bi-check-circle-fill text-success"></i>');
            $('#result-box').css('border-top', '5px solid #198754');
            
            // HANYA MASUKKAN KE TABEL JIKA SUKSES (Anti Spam)
            addToTable(res);
        } else {
            // GAGAL
            audioFail.play();
            $('#scan-title').text('AKSES DITOLAK').attr('class', 'fw-bold mb-1 text-danger');
            $('#icon-status').html('<i class="bi bi-x-circle-fill text-danger"></i>');
            $('#result-box').css('border-top', '5px solid #dc3545');
            // Data GAGAL TIDAK dimasukkan ke tabel
        }

        $('#scan-name').text(res.detail || '-');
        $('#scan-message').text(res.message);
    }

    function addToTable(res) {
        $('#empty-row').remove(); // Hapus pesan kosong jika ada

        let time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        
        let row = `
            <tr class="table-row-anim" style="background-color: #e8f4ff;">
                <td class="text-muted small">${time}</td>
                <td class="fw-bold">${res.detail}</td>
                <td><span class="badge bg-success"><i class="bi bi-check-lg"></i> Berhasil</span></td>
            </tr>
        `;

        // Tambahkan ke paling atas
        $('#history-table-body').prepend(row);
    }

    let html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess);
</script>
@endsection