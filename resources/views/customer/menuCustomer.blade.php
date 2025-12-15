<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Bakmie Bangka AY</title>

    <!-- Bootstrap & Icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>

    <div class="container py-3 px-3">

        {{-- Logo dan salam --}}
        <div class="d-flex justify-content-between align-items-start mb-3 ms-2 me-3">
            <div class="banner-text text-start">
                <img src="{{ asset('image/logo.jpg') }}" alt="Logo" width="70">
                <h5 class="fw-bold mt-2 mb-0">Selamat Datang di</h5>
                <h5 class="fw-bold">Bakmie Bangka AY!</h5>
            </div>

           <a href="{{ route('customer.cart') }}" class="cart-icon-btn position-relative">
             <i class="bi bi-cart2"></i>

             @if ($cartCount > 0)
              <span class="cart-badge-custom">{{ $cartCount }}</span>
            @endif
            </a>

        </div>

        {{-- Search bar --}}
<div class="top d-flex align-items-center gap-2 mb-3">

    {{-- Search Bar --}}
    <div class="search-bar">
        <div class="left-section">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Search">
        </div>
    </div>

    {{-- Order ID Button --}}
    <a href="{{ route('customer.viewOrder') }}" class="btn-order-id">
        Order ID
    </a>
</div>



</div>
       {{-- Promo banner --}}
        <div class="promo-banner mb-4">
            <div class="promo-text py-2">
                <h5>Bakmie + Es Teh<br>cuma 22k</h5>
                <button class="promo-btn px-1 py-1">Pesan Sekarang</button>
            </div>
            <div class="promo-image">
                <img src="{{ asset('image/Bakmie.png') }}" alt="Bakmie" class="Bakmie">
                <img src="{{ asset('image/esTeh.png') }}" alt="Es Teh" class="Esteh gap-2">
            </div>
        </div>


        {{-- Category --}}
<h6 class="head-category mb-2">Category</h6>
<div class="category-scroll mb-3">
    @foreach ($categories as $category)
        <form action="{{ route('customer.menuCustomer') }}" method="GET" class="d-inline">
            <input type="hidden" name="category" value="{{ $category->name }}">
            <button type="submit"
                class="px-3 py-1 
                {{ $selectedCategory == $category->name ? 'category-borderOn' : 'category-borderOff' }}">
                {{ $category->name }}
            </button>
        </form>
    @endforeach
</div>

        {{-- Menu list --}}
        <div class="row g-4">
            @foreach ($menus as $menu)
                <div class="col">
                    <div class="menu-card p-2">

                        {{-- Gambar dari database --}}
                       <img src="{{ asset($menu->menu_image) }}" alt="{{ $menu->menu_name }}" class="img-fluid mx-auto d-block">
                        <div class="card-body d-flex justify-content-between align-items-end">
                            <div class="text-start">
                                <h6 class="fw-semibold mb-1" style="font-size: 12px;">
                                    {{ $menu->menu_name }}
                                </h6>

                                {{-- HARGA --}}
                                <small class="text-muted d-block mb-2">
                                    Rp {{ number_format($menu->menu_price, 0, ',', '.') }}
                                </small>
                            </div>

                            {{-- ADD TO CART --}}
                            <a href="{{ route('customer.menu.detail', $menu->id_menu) }}"
                                class="btn btn-sm d-flex justify-content-center align-items-center">
                                <i class="bi bi-plus btn-plus"></i>
                            </a>


                        </div>
                    </div>
                </div>
            @endforeach
        </div>


    </div> {{-- penutup .container --}}

    {{-- Script Bootstrap kalau perlu --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('form[action="{{ route("customer.cart.add") }}"]');
        const cartIcon = document.querySelector('.cart-icon-btn');
        const badge = document.querySelector('.cart-badge-custom');

        forms.forEach(form => {
            form.addEventListener('submit', function () {

                // TAMBAH ANIMASI CART WOBBLE
                cartIcon.classList.add('cart-wobble-animate');

                // HAPUS SETELAH SELESAI
                setTimeout(() => {
                    cartIcon.classList.remove('cart-wobble-animate');
                }, 600);

                // ANIMASI BADGE POP (kalau badge sudah ada)
                if (badge) {
                    badge.classList.add('cart-badge-animate');
                    setTimeout(() => {
                        badge.classList.remove('cart-badge-animate');
                    }, 400);
                }
            });
        });
    });
</script>

</body>

</html>