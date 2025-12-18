<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Order Sent</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/orderSent.css') }}">
</head>

<body>

@php
    $fromViewOrder = request()->query('from') === 'view-order';
@endphp

<div class="orderSent-wrapper">

    <!-- STATUS PESANAN -->
    <div style="text-align: center;">
        <h2 class="orderSent-title mb-3" id="orderStatusText">
            @if ($order->status_order === 'OPEN' && $order->status_bayar === 'PAID')
                Order is being prepared
            @elseif ($order->status_order === 'CLOSE' && $order->status_bayar === 'PAID')
                Order Completed
            @else
                Order is being prepared
            @endif
        </h2>
    </div>

    <!-- ORDER DETAILS -->
    <div class="orderSent-container">

        <div class="orderSent-card">

            <div class="orderSent-row">
                <span class="orderSent-label">Order ID</span>
                <span class="orderSent-value">
                    {{ 'ORD-' . str_pad($order->order_id, 3, '0', STR_PAD_LEFT) }}
                </span>
            </div>

            <div class="orderSent-row">
                <span class="orderSent-label">Customer Name</span>
                <span class="orderSent-value">
                    {{ $order->customer_name ?? '-' }}
                </span>
            </div>

<div class="orderSent-row">
    <span class="orderSent-label">Table Number</span>
    <span class="orderSent-value">
        {{ 'MEJA-' . str_pad($order->meja_number, 2, '0', STR_PAD_LEFT) }}
    </span>
</div>



            <div class="orderSent-row">
                <span class="orderSent-label">Payment Method</span>
                <span class="orderSent-value">QRIS</span>
            </div>

            <div class="orderSent-row">
                <span class="orderSent-label">Date & Time</span>
                <span class="orderSent-value">
                    {{ \Carbon\Carbon::parse($order->order_datetime)->format('d/m/y H:i') }}
                </span>
            </div>

        </div>

        <!-- ORDER SUMMARY -->
        <div class="order-summary mx-auto mt-3">
            <h2 class="order-summary-title">Order Summary</h2>
            <hr>

            @foreach ($order->details as $detail)
                <div class="menu-item">
                    <div class="menu-info">
                        <div class="menu-left">
                            <p class="menu-name">{{ $detail->menu->menu_name }}</p>
                            <p class="menu-price">Rp {{ number_format($detail->menu->menu_price, 0, ',', '.') }}</p>
                            <p class="menu-price">{{ $detail->quantity }}x</p>
                            <p class="menu-price">{{ $detail->notes ?? 'tidak ada catatan' }}</p>
                        </div>

                        <div class="menu-right">
                            <div class="menu-img-wrapper">
                                <img class="menu-img"
                                     src="{{ asset($detail->menu->menu_image) }}"
                                     alt="{{ $detail->menu->menu_name }}">
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            @endforeach

            <div class="total-payment-box" style="padding: 5px 5px 15px;">
                <p style="margin:0;font-size:12px;font-weight:600;color:#737373;">
                    Total Payment
                </p>
                <p style="margin:0;font-size:20px;font-weight:700;color:#232323;">
                    Rp {{ number_format($order->details->sum('subtotal'), 0, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- CANCEL BUTTON (HANYA JIKA BUKAN DARI VIEW ORDER) --}}
        @if (!$fromViewOrder && $order->status_order === 'OPEN' && $order->status_bayar === 'PAID')
        <div style="display:flex;justify-content:center;margin-top:15px;">
            <div class="orderSent-cancelBox" id="cancelBox" style="width:100%;max-width:250px;">

                <div class="orderSent-cancelProgress" id="cancelProgress"></div>

                <form id="cancelOrderForm"
                      action="{{ route('customer.order.cancel', $order->order_id) }}"
                      method="POST">
                    @csrf
                    <button class="orderSent-cancelBtn" id="cancelBtn"
                        style="border:2px solid #d9534f;border-radius:8px;width:100%;">
                        Cancel Order (<span id="countdown">60</span>s)
                    </button>
                </form>

            </div>
        </div>
        @endif

    </div>
</div>

{{-- SCRIPT --}}
@if (!$fromViewOrder && $order->status_order === 'OPEN' && $order->status_bayar === 'PAID')
<script>
document.addEventListener("DOMContentLoaded", function () {
    let countdown = 60;
    let timerDisplay = document.getElementById("countdown");
    let progressBar = document.getElementById("cancelProgress");
    let cancelBox = document.getElementById("cancelBox");
    let cancelBtn = document.getElementById("cancelBtn");

    let interval = setInterval(() => {
        countdown--;
        timerDisplay.textContent = countdown;
        progressBar.style.width = ((60 - countdown) / 60 * 100) + "%";

        if (countdown <= 0) {
            clearInterval(interval);
            cancelBox.style.display = "none";
        }
    }, 1000);

    cancelBtn.addEventListener("click", function (e) {
        e.preventDefault();
        document.getElementById("cancelOrderForm").submit();
    });
});
</script>
@endif

</body>
</html>
