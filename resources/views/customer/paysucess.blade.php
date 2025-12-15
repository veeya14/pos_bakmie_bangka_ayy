<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
     <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="paySuccess-body">

    <div class="paySuccess-container">

        <div class="paySuccess-checkIcon">
            <span>&#10003;</span>
        </div>

        <div class="paySuccess-title ">Payment Sucess</div>

        <!-- TANGGAL PESANAN -->
        <div class="paySuccess-info">
            {{ \Carbon\Carbon::parse($order->order_datetime)->translatedFormat('d F Y, H:i') }}
        </div>

        <!-- KODE ORDER -->
        <div class="paySuccess-info">
            {{ 'ORDER-' . str_pad($order->order_id, 6, '0', STR_PAD_LEFT) }}
        </div>

        <!-- TOTAL PEMBAYARAN -->
        <div class="paySuccess-price">
            Rp {{ number_format($order->details->sum('subtotal'), 0, ',', '.') }}
        </div>

        <div class="paySuccess-via">
            via <span>QRIS</span>
        </div>

        <!-- BUTTON VIEW ORDER STATUS -->
        <a href="{{ route('customer.order.status', ['order_id' => $order->order_id]) }}"
            style="background:#FFC548; padding:12px 20px; display:block; width:200px; margin:20px auto; 
                border-radius:10px; text-align:center; color:#000; font-weight:600; text-decoration:none;">
            View order status
        </a>

    </div>

</body>

</html>
