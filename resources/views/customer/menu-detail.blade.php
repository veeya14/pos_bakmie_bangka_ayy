<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $menu->menu_name }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="menu-detail-body">

<!-- POPUP NOTIFIKASI -->
<div id="addedPopup" class="added-popup">
    <div class="popup-icon">✔</div>
    <span>Added to Cart</span>
</div>


<div class="menu-detail-page">

    <div class="top-wrapper">

        {{-- HEADER --}}
        <div class="header">
            <a href="{{ route('customer.menuCustomer') }}" class="back-btn">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="header-title">Menu Detail</h1>
            <div class="header-spacer"></div>
        </div>

        {{-- GAMBAR --}}
        <div class="banner">
            <img src="{{ asset($menu->menu_image) }}" alt="{{ $menu->menu_name }}">
        </div>

    </div>

    {{-- KONTEN PUTIH --}}
    <div class="content-fixed">

        {{-- NAMA & HARGA --}}
        <div class="title-price">
            <h2>{{ $menu->menu_name }}</h2>
            <span>Rp {{ number_format($menu->menu_price, 0, ',', '.') }}</span>
        </div>

        <hr>

        {{-- DESKRIPSI --}}
        <p class="desc">{{ $menu->menu_description }}</p>

        {{-- QUANTITY --}}
        <div class="quantity">
            <button id="minus">−</button>
            <span id="count">1</span>
            <button id="plus">+</button>
        </div>

        {{-- ADD TO CART --}}
        <form action="{{ route('customer.cart.add') }}" method="POST">
            @csrf
            <input type="hidden" name="menu_id" value="{{ $menu->id_menu }}">
            <input type="hidden" id="qtyField" name="qty" value="1">

            <textarea id="note" name="note" placeholder="Catatan untuk penjual (opsional)"></textarea>

            <button type="submit" class="btn-cart mb-3">Add to Cart</button>
        </form>

    </div>

</div>


{{-- QTY SCRIPT --}}
<script>
    let qty = 1;
    const countEl = document.getElementById('count');
    const qtyField = document.getElementById('qtyField');

    document.getElementById('plus').addEventListener('click', () => {
        qty++;
        countEl.textContent = qty;
        qtyField.value = qty;
    });

    document.getElementById('minus').addEventListener('click', () => {
        if (qty > 1) qty--;
        countEl.textContent = qty;
        qtyField.value = qty;
    });
</script>

<script>
document.querySelector('form[action="{{ route("customer.cart.add") }}"]').addEventListener('submit', function () {

    const popup = document.getElementById('addedPopup');

    popup.classList.add('show');

    // TUNGGU 3 DETIK sebelum fade-out
    setTimeout(() => {
        popup.classList.remove('show');
    }, 9000);
});
</script>

</body>
</html>
