<!DOCTYPE html>
<html>
<head>
    <title>E-Ticket - {{ $ticket->unique_code }}</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .ticket-container {
            max-width: 700px; margin: 0 auto; border: 1px solid #ddd;
        }
        .header { padding: 20px; text-align: right; border-bottom: 1px solid #eee; }
        .banner { width: 100%; height: 200px; background: #eee; }
        .banner img { width: 100%; height: 100%; object-fit: cover; }
        .content { padding: 20px; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .label { font-size: 10px; color: #888; text-transform: uppercase; }
        .value { font-size: 14px; font-weight: bold; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="header">
            <?php
                // Pastikan file 'logo_karcisin.png' ada di folder public/img
                $path = public_path('img/logo_karcisin.png');
                
                // Cek jika file ada, ubah jadi base64
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                } else {
                    $base64 = ''; // Kosong jika file tidak ditemukan
                }
            ?>
            
            @if($base64)
                <img src="{{ $base64 }}" height="40">
            @else
                <h3>Karcis.in</h3> @endif
        </div>

        <div class="banner">
            @if($ticket->transaction->event->banner)
                <img src="{{ public_path('storage/' . $ticket->transaction->event->banner) }}">
            @endif
        </div>

        <div class="content">
            <table class="info-table">
                <tr>
                    <td width="30%" valign="top">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $ticket->unique_code }}" width="120">
                        <div style="font-size: 10px; text-align: center; margin-top: 5px;">Scan saat masuk</div>
                    </td>
                    <td width="70%" valign="top">
                        <div class="label">Kode Tiket</div>
                        <div class="value" style="color: #0d6efd; font-size: 16px;">{{ $ticket->unique_code }}</div>

                        <div class="label">Nama Pengunjung (Sesuai KTP)</div>
                        <div class="value">{{ $ticket->transaction->customer_name }}</div>

                        <div class="label">NIK / KTP</div>
                        <div class="value">{{ $ticket->transaction->customer_nik }}</div>

                        <div class="label">Nomor HP</div>
                        <div class="value">{{ $ticket->transaction->customer_phone }}</div>
                    </td>
                </tr>
            </table>

            <hr style="border: 0; border-top: 1px dashed #ddd;">

            <div class="label">Event</div>
            <div class="value" style="font-size: 18px;">{{ $ticket->transaction->event->name }}</div>
            
            <div class="label">Waktu</div>
            <div class="value">{{ \Carbon\Carbon::parse($ticket->transaction->event->event_date)->format('d F Y, H:i') }} WIB</div>

            <div class="label">Lokasi</div>
            <div class="value">{{ $ticket->transaction->event->location }}</div>
            
            <div class="label">Harga</div>
            <div class="value">Rp {{ number_format($ticket->transaction->event->price, 0, ',', '.') }}</div>
        </div>
    </div>
</body>
</html>