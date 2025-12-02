@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white text-center">
                    <h4 class="mb-0">🎥 Scanner Tiket</h4>
                </div>
                <div class="card-body text-center">
                    
                    <div class="mb-3">
                        <label class="fw-bold">Pilih Event yang Ingin Discan:</label>
                        <select id="event_select" class="form-select">
                            <option value="">-- Pilih Event --</option>
                            @foreach($myEvents as $event)
                                <option value="{{ $event->id }}">{{ $event->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="scanner-container" style="display:none;">
                        <!-- AREA KAMERA -->
                        <div id="reader" style="width: 100%; min-height: 300px; background: #eee;"></div>
                        
                        <!-- HASIL -->
                        <div id="result-area" class="mt-4 d-none">
                            <div id="result-alert" class="alert">
                                <h4 class="alert-heading fw-bold" id="result-title"></h4>
                                <p id="result-message"></p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let html5QrcodeScanner;

    $('#event_select').change(function(){
        if($(this).val()){
            $('#scanner-container').show();
            initScanner();
        } else {
            $('#scanner-container').hide();
        }
    });

    function initScanner(){
        if(html5QrcodeScanner) return; // Jangan init ulang kalau sudah ada

        html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    }

    function onScanSuccess(decodedText) {
        html5QrcodeScanner.clear();
        let eventId = $('#event_select').val();

        $.ajax({
            url: "{{ route('scan.verify') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                qr_code: decodedText,
                event_id: eventId
            },
            success: function(response) {
                $('#result-area').removeClass('d-none');
                
                if(response.status === 'success') {
                    $('#result-alert').attr('class', 'alert alert-success');
                    $('#result-title').text('✅ MASUK');
                } else {
                    $('#result-alert').attr('class', 'alert alert-danger');
                    $('#result-title').text('⛔ GAGAL');
                }
                $('#result-message').text(response.message || response.detail);

                setTimeout(() => {
                    $('#result-area').addClass('d-none');
                    html5QrcodeScanner.render(onScanSuccess);
                }, 3000);
            }
        });
    }
</script>
@endsection