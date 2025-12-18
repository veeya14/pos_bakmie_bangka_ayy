@extends('layouts.layout')

@section('title', 'Dashboard Bakmie Bangka AY')

@section('content')

    @include('partials.page-header', [
        'title' => 'Dashboard',
    ])


    <div class="container-fluid">
 {{-- Top Stats (STATIC VERSION) --}}
<div class="row g-3 mb-4 mt-3">

    {{-- Available Menu --}}
    <div class="col-md-4">
        <a href="/seller/menus" class="text-decoration-none text-dark">
            <div class="card shadow-sm rounded-4 p-3 h-100 card-clickable">
                <div class="d-flex align-items-center">
                    <i class="bi bi-egg-fried fs-1 me-3 text-warning"></i>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ $totalMenu }}</h5>
                        <p class="mb-0 small text-muted">Available Menu</p>
                        <p class="mb-0 text-muted small">Click to view menu</p>
                    </div>
                </div>
            </div>
        </a>
    </div>

  {{-- Best Seller --}}
<div class="col-md-4">
    <a href="#" class="text-decoration-none text-dark" id="openBestSeller">
        <div class="card shadow-sm rounded-4 p-3 h-100 card-clickable">
            <div class="d-flex align-items-center">
                <i class="bi bi-star-fill fs-1 me-3 text-warning"></i>
                <div>
                    <h5 class="mb-0 fw-bold">Best Seller</h5>
                    <p class="mb-0 small text-muted">Click to view</p>
                </div>
            </div>
        </div>
    </a>
</div>



    {{-- Pending Orders --}}
    <div class="col-md-4">
        <a href="/seller/orders" class="text-decoration-none text-dark">
            <div class="card shadow-sm rounded-4 p-3 h-100 card-clickable">
                <div class="d-flex align-items-center">
                    <i class="bi bi-clock-history fs-1 me-3 text-danger"></i>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ $pendingOrders }}</h5>
                        <p class="mb-0 small text-muted">Pending Orders</p>
                        <p class="mb-0 text-muted small">Click to view orders</p>
                    </div>
                </div>
            </div>
        </a>
    </div>

</div>


{{-- Weekly Sales Chart (Full Width) --}}
<div class="row g-4">
    <div class="col-12">
        <div class="card shadow-sm rounded-4 p-4">
            <h6 class="fw-semibold text-secondary mb-3">Weekly Sales</h6>
            <div style="height: 400px;">
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- MODAL BEST SELLER -->
<div class="modal fade" id="bestSellerModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-5 border-0 shadow-sm">

            {{-- HEADER --}}
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="modal-title fw-semibold">
                    Best Seller Menu ✨
                </h5>
                <button class="btn-close"></button>
            </div>

            {{-- BODY --}}
            <div class="modal-body px-4 pt-3 pb-4">

                <div class="d-flex flex-column gap-3">

                    @foreach ($bestSellers as $index => $item)
                        <div class="d-flex align-items-center gap-3 p-3 rounded-4 bg-light">

                            {{-- IMAGE --}}
                            <div class="flex-shrink-0">
                                <img src="{{ asset($item->menu->menu_image ?? 'image/default.png') }}"
                                     class="rounded-circle"
                                     style="width: 64px; height: 64px; object-fit: cover;">
                            </div>

                            {{-- INFO --}}
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h6 class="fw-semibold mb-1">
                                        {{ $item->menu->menu_name }}
                                    </h6>
                                    <span class="small text-muted">
                                        #{{ $index + 1 }}
                                    </span>
                                </div>

                                <p class="small text-muted mb-1" style="line-height: 1.3;">
                                    {{ $item->menu->menu_description ?? 'Best choice menu' }}
                                </p>

                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <span class="fw-bold text-dark">
                                        Rp {{ number_format($item->menu->menu_price, 0, ',', '.') }}
                                    </span>

                                    <span class="small text-muted">
                                        {{ $item->total_qty }} sold
                                    </span>
                                </div>
                            </div>

                        </div>
                    @endforeach

                </div>

            </div>

        </div>
    </div>
</div>



@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ==========================
    // CHART WEEKLY SALES
    // ==========================
    const ctx = document.getElementById('weeklyChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($weekDays) !!},
            datasets: [{
                label: 'Sales',
                data: {!! json_encode($salesData) !!},
                backgroundColor: 'rgba(165, 102, 43, 0.6)',
                borderColor: '#A5662B',
                borderWidth: 2,
                borderRadius: 8,
                maxBarThickness: 50 
            }]
        },
        options: {
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    ticks: { color: '#888' },
                    beginAtZero: true
                },
                x: {
                    ticks: { color: '#888' }
                }
            }
        }
    });

document.getElementById("openBestSeller")?.addEventListener("click", function (e) {
        e.preventDefault();
        new bootstrap.Modal(document.getElementById("bestSellerModal")).show();
    });
</script>
@endpush
