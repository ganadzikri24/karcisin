<!DOCTYPE html>
<html>
<head>
    <title>Tiket Anda Telah Terbit</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px;">
        <h2 style="color: #0d6efd;">Halo, {{ $transaction->customer_name }}!</h2>
        
        <p>Terima kasih telah melakukan pemesanan di <strong>Karcis.in</strong>.</p>
        
        <p>Pembayaran Anda untuk event <strong>{{ $transaction->event->name }}</strong> telah kami terima dan verifikasi.</p>
        
        <div style="background: #e9ecef; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>Detail Pesanan:</strong><br>
            Jumlah Tiket: {{ $transaction->quantity }}<br>
            Total Bayar: Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
        </div>

        <p>Silakan unduh <strong>E-Ticket (PDF)</strong> yang telah kami lampirkan pada email ini. Tunjukkan QR Code pada tiket tersebut saat masuk ke lokasi event.</p>
        
        <p>Sampai jumpa di lokasi!</p>
        
        <hr>
        <small style="color: #888;">Email ini dikirim otomatis oleh sistem Karcis.in</small>
    </div>
</body>
</html>