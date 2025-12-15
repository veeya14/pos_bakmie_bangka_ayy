<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QRIS Payment</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <div class="qris-page">

        <!-- Title -->
        <div class="header-qris">
            <h1 class="qris-title">Scan QR Code</h1>
        </div>

        <div class="qris-content">

            <h4>QRIS</h4>

            <!-- QR Card -->
            <div class="qr-card text-center">
                <img id="qrisImage"
                     src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $order_id }}"
                     class="img-fluid mb-3"
                     alt="QR Code">
            </div>

            <!-- Countdown -->
            <div class="timer-box mb-3">
                <small>Complete the payment within</small><br>
                <span id="timer" class="fw-bold fs-5">00:30:00</span>
            </div>

            <!-- Price -->
            <div class="qris-price">
                <div class="qris-totalPrice">
                    <p>Total Price</p>
                    <p><b>Rp {{ number_format($total, 0, ',', '.') }}</b></p>
                </div>
                <p class="qrisName">Bakmie Bangka Ay-QRIS</p>
            </div>

            <!-- Buttons -->
            <div class="qris-btnBorder">
                <div class="mt-4 d-grid gap-3">
                    <button class="btn-unduh" id="downloadQR">Unduh QR Code</button>
                    <button class="btn-share" id="shareQR">Bagikan QR Code</button>
                </div>
            </div>

            <!-- Pembayaran berhasil (sementara) -->
            <div class="mt-4 d-grid gap-3">
                <a href="{{ route('customer.paysuccess', ['order_id' => $order_id]) }}"
                   class="btn btn-success w-100">
                    Pembayaran Berhasil
                </a>
            </div>

        </div>
    </div>

    <script>
        // ==========================
        // COUNTDOWN 30 MINUTES
        // ==========================
        let duration = 30 * 60;
        let timerElement = document.getElementById("timer");

        setInterval(function () {
            let minutes = Math.floor(duration / 60);
            let seconds = duration % 60;

            timerElement.textContent =
                "00:" +
                (minutes < 10 ? "0" + minutes : minutes) +
                ":" +
                (seconds < 10 ? "0" + seconds : seconds);

            if (duration > 0) duration--;
        }, 1000);


        // ==========================
        // DOWNLOAD QR CODE (WORKING)
        // ==========================
        document.getElementById("downloadQR").addEventListener("click", function () {
            const qrImage = document.getElementById("qrisImage");

            fetch(qrImage.src)
                .then(res => res.blob())
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement("a");
                    a.href = url;
                    a.download = "QRIS-Order-{{ $order_id }}.png";
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);
                });
        });


        // ==========================
        // SHARE QR CODE
        // ==========================
        document.getElementById("shareQR").addEventListener("click", async function () {
            const qrImage = document.getElementById("qrisImage");

            if (navigator.share) {
                try {
                    await navigator.share({
                        title: "QRIS Payment",
                        text: "Silakan scan QRIS untuk pembayaran order {{ $order_id }}",
                        url: qrImage.src
                    });
                } catch (e) {}
            } else {
                navigator.clipboard.writeText(qrImage.src);
                alert("Link QR Code berhasil disalin!");
            }
        });
    </script>

</body>
</html>
