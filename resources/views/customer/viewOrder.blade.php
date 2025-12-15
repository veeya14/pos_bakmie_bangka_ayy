<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Order</title>

    <!-- Bootstrap & Icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/viewOrder.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

<div class="container py-3 px-3">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-start mb-3 ms-2 me-3">
        <div class="banner-text text-start">
            <img src="{{ asset('image/logo.jpg') }}" alt="Logo" width="70">
            <h5 class="fw-bold mt-2 mb-0">View Order</h5>
            <h5 class="fw-bold">See your active order from this table</h5>
        </div>
    </div>

    {{-- SEARCH + VIEW MENU --}}
    <div class="top d-flex align-items-center gap-2">

        {{-- SEARCH --}}
        <form action="{{ route('customer.searchOrderView') }}"
              method="GET"
              class="search-bar mb-1 flex-grow-1">

            <div class="left-section">
                <i class="bi bi-search"></i>
                <input type="text"
                       name="keyword"
                       placeholder="Search Order ID / Customer Name"
                       value="{{ request('keyword') }}">
            </div>
        </form>

        {{-- VIEW MENU --}}
             <a href="{{ route('customer.menuCustomer') }}"
                class="btn-order-id text-decoration-none"
                style="white-space: nowrap;">
                 View Menu
             </a>

    </div>

    {{-- ORDER LIST --}}
    @forelse ($orders as $order)
    <div class="order-info card mt-3 mb-3 border-0 shadow-sm rounded-4">
        <div class="card-body py-3 px-3">

            {{-- ORDER ID + STATUS --}}
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <div class="text-muted small">Order ID</div>
                    <h5 class="mb-0">
                        #ORD-{{ str_pad($order->order_id, 3, '0', STR_PAD_LEFT) }}
                    </h5>
                </div>

                <span class="font me-2">
                    @if ($order->status_order === 'OPEN' && $order->status_bayar === 'PAID')
                        In Progress
                    @elseif ($order->status_order === 'CLOSE' && $order->status_bayar === 'PAID')
                        Finish
                    @elseif ($order->status_order === 'CLOSE' && $order->status_bayar === 'UNPAID')
                        Cancel
                    @else
                        In Progress
                    @endif
                </span>
            </div>

            <hr class="my-2">

            {{-- CUSTOMER + DETAIL --}}
            <div class="d-flex justify-content-between">
                <div>
                    <div class="text-muted small font">Customer</div>
                    <div class="fw-semibold font">
                        {{ $order->customer_name ?? '-' }}
                    </div>
                </div>

                <button class="viewOrder-btn"
                    onclick="window.location='{{ route('customer.order.status', ['order_id' => $order->order_id, 'from' => 'view-order']) }}'">
                    View Detail
                </button>
            </div>

        </div>
    </div>
    @empty
        <div class="text-center text-muted mt-4">
            No orders found
        </div>
    @endforelse

</div>

</body>
</html>
