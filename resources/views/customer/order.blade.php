<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<!-- POPUP NOTE -->
<div id="noteOverlay" class="overlay"></div>
<div id="noteSheet" class="bottom-sheet">
    <form method="POST" action="{{ route('customer.cart.note') }}" class="w-100">
        @csrf
        <input type="hidden" id="noteMenuId" name="menu_id">

        <div class="sheet-content">
            <h6 class="mb-2">Add Note</h6>
            <textarea id="noteText" name="note" class="form-control"
                      placeholder="Add note for this item..." rows="3"></textarea>
        </div>

        <div class="overlay-btn">
            <button type="button" id="closeNoteBtn" class="btn-Outline">Cancel</button>
            <button type="submit" class="btn-solidd">Save</button>
        </div>
    </form>
</div>


<div class="page-wrapper">

    {{-- HEADER --}}
    <div class="order-header-box">
        <a href="{{ route('customer.menuCustomer') }}" class="back-btn">←</a>
        <div class="order-header">
            <h1 class="order-title">Order</h1>
            <div class="order-spacer"></div>
        </div>
    </div>

    {{-- CUSTOMER NAME --}}
    <div class="customer-box mx-auto mt-3">
        <label class="customer-label">Customer Name</label>
       <input type="text" name="customer_name" class="customer-input" 
       placeholder="Enter your name" required id="customerName">
    </div>

    {{-- ORDER SUMMARY --}}
    <div class="order-summary mx-auto mt-3">
        <h2 class="order-summary-title">Order Summary</h2>
        <hr>

        @forelse ($cart as $item)
            <div class="menu-item">
                <div class="menu-info">

                    <div class="menu-left">
                        <p class="menu-name">{{ $item['name'] }}</p>
                        <p class="menu-price">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>

                        <!-- NOTE BUTTON -->
                        <button type="button"
                                class="note-btn"
                                onclick="openNotePopup('{{ $item['menu_id'] }}', '{{ $item['note'] ?? '' }}')">
                            <i class="bi bi-pencil-fill me-1"></i> Note
                        </button>

                        @if (!empty($item['note']))
                            <p class="text-muted" style="font-size:11px;">📝 {{ $item['note'] }}</p>
                        @endif

                    </div>

                    <div class="menu-right">
                        <div class="menu-img-wrapper">
                            <img class="menu-img" src="{{ asset($item['image']) }}">
                        </div>

                        <div class="menu-qty">

                            {{-- MINUS --}}
                            <form action="{{ route('customer.cart.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="menu_id" value="{{ $item['menu_id'] }}">
                                <input type="hidden" name="qty" value="{{ $item['qty'] - 1 }}">
                                <button class="qty-btn">−</button>
                            </form>

                            <span class="qty">{{ $item['qty'] }}</span>

 {{-- PLUS --}}
<form action="{{ route('customer.cart.update') }}" method="POST">
    @csrf
    <input type="hidden" name="menu_id" value="{{ $item['menu_id'] }}">
    <input type="hidden" name="qty" value="{{ $item['qty'] + 1 }}">
    <button class="qty-btn">+</button>
</form>


                        </div>
                    </div>

                </div>
            </div>
            <hr>
        @empty
            <p class="text-center">Cart masih kosong.</p>
        @endforelse

        <div class="add-more">
            <div class="add-text">
                <p class="add-title">Add more items?</p>
                <p class="add-desc">You can still select more items</p>
            </div>
            <a href="{{ route('customer.menuCustomer') }}" class="add-btn">Add Menu</a>
        </div>
    </div>

    {{-- PAYMENT --}}
    <div class="payment-box mx-auto mt-3">
        <h6 class="fw-semibold mb-3">Payment Method</h6>

        <label class="custom-radio">
            <input type="radio" checked>
            <span class="radio-circle"></span>
            QRIS
        </label>
    </div>

</div>



{{-- FOOTER --}}
<div class="order-footer fixed-order-footer mx-auto">
    <div>
        <p class="text-muted small mb-0">Total Payment</p>

        <h6 class="fw-semibold mb-0">
            Rp {{ number_format(collect($cart)->sum(fn ($i) => $i['price'] * $i['qty']), 0, ',', '.') }}
        </h6>
    </div>

    <button 
        class="btn-ordernow px-2 py-2" 
        id="orderNowBtn"
        {{ empty($cart) ? 'disabled style=background:#ccc;cursor:not-allowed;' : '' }}
    >
        Order now
    </button>
</div>



{{-- BOTTOM SHEET KONFIRMASI --}}
<div id="overlay" class="overlay"></div>

<div id="bottomSheet" class="bottom-sheet">
    <div class="sheet-content">
        <img class="content-img" src="{{ asset('image/nanya.png') }}" alt="yakin?" />
        <div class="overlay-textMain"><p>Proceed with the order?</p></div>
        <div class="overlay-textMini"><p>Make sure everything is correct before sending to the kitchen</p></div>
    </div>

    <div class="overlay-btn">
        <button id="reviewBtn" class="btn-Outline">Review</button>

        <form id="processOrderForm" action="{{ route('customer.order.store') }}" method="POST">
            @csrf
            <input type="hidden" id="customerNameField" name="customer_name">
            <button type="submit" id="processBtn" class="btn-solidd">Process now</button>
        </form>
    </div>
</div>


{{-- OVERLAY PROSES --}}
<div id="processOverlay" class="overlay" style="display:none; z-index:20000;">
    <div class="bottom-sheet show" style="height:200px; justify-content:center; align-items:center;">
        <p class="overlay-textMain">Your order is being processed...</p>
    </div>
</div>



{{-- SCRIPT --}}
<script>
/* ============================
      NOTE POPUP SCRIPT
============================= */
function openNotePopup(menuId, note = "") {
    document.getElementById("noteMenuId").value = menuId;
    document.getElementById("noteText").value = note;

    document.getElementById("noteOverlay").style.display = "block";
    document.getElementById("noteSheet").classList.add("show");
}

document.getElementById("closeNoteBtn").addEventListener("click", () => {
    document.getElementById("noteOverlay").style.display = "none";
    document.getElementById("noteSheet").classList.remove("show");
});

document.getElementById("noteOverlay").addEventListener("click", () => {
    document.getElementById("noteOverlay").style.display = "none";
    document.getElementById("noteSheet").classList.remove("show");
});


/* ============================
  KONFIRMASI ORDER SCRIPT
============================= */
const overlay = document.getElementById('overlay');
const bottomSheet = document.getElementById('bottomSheet');
const processOverlay = document.getElementById('processOverlay');

const orderNowBtn = document.getElementById('orderNowBtn');
const reviewBtn = document.getElementById('reviewBtn');
const processBtn = document.getElementById('processBtn');

const customerNameInput = document.getElementById('customerName');
const orderBtn = document.getElementById('orderNowBtn');

// ketika mengetik, cek nama
customerNameInput.addEventListener('input', () => {
    if (customerNameInput.value.trim() === "") {
        orderBtn.disabled = true;
        orderBtn.style.background = "#ccc";
        orderBtn.style.cursor = "not-allowed";
    } else {
        orderBtn.disabled = false;
        orderBtn.style.background = "#f7b42c";
        orderBtn.style.cursor = "pointer";
    }
});

// saat halaman pertama kali dibuka
if (customerNameInput.value.trim() === "") {
    orderBtn.disabled = true;
    orderBtn.style.background = "#ccc";
    orderBtn.style.cursor = "not-allowed";
}

// tampilkan bottom sheet
orderNowBtn.addEventListener('click', () => {
    overlay.style.display = 'block';
    bottomSheet.classList.add('show');
});

// review = tutup
reviewBtn.addEventListener('click', () => {
    bottomSheet.classList.remove('show');
    overlay.style.display = 'none';
});

// proses order
processBtn.addEventListener('click', (e) => {
    e.preventDefault();
    const name = document.getElementById('customerName').value;
    document.getElementById('customerNameField').value = name;

    bottomSheet.classList.remove('show');
    overlay.style.display = 'none';

    processOverlay.style.display = 'block';

    setTimeout(() => {
        document.getElementById('processOrderForm').submit();
    }, 1500);
});

// klik luar = tutup
overlay.addEventListener('click', () => {
    bottomSheet.classList.remove('show');
    overlay.style.display = 'none';
});

// Disable click kalau cart kosong
@if(empty($cart))
    document.getElementById('orderNowBtn').addEventListener('click', (e) => {
        e.preventDefault();
        return false;
    });
@endif

</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>