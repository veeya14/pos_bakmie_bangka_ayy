@extends('layouts.layout')

@section('title', 'Dashboard Bakmie Bangka AY')

@section('content')

    @include('partials.page-header', [
        'title' => 'Dashboard',
    ])

    <div class="container-fluid">
        {{-- Top Stats --}}
        <div class="row g-3 mb-4 mt-3">
            {{-- Available Dish --}}
            <div class="col-md-3">
                <div class="card shadow-sm border rounded-4 p-3 h-100">
                    <div class="d-flex align-items-center">
                        {{-- Icon kuning --}}
                        <i class="bi bi-egg-fried fs-1 me-3 text-warning"></i>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $availableDish }}</h5>
                            <p class="mb-0 small text-muted">Available Dish</p>
                            <p class="mb-0 text-muted small">Are able to order</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Order --}}
            <div class="col-md-3">
                <div class="card shadow-sm border rounded-4 p-3 h-100">
                    <h5 class="mb-0 fw-bold">{{ $totalOrder }}</h5>
                    <p class="mb-0 small text-muted">Total Order</p>
                    <p class="mb-0 text-muted small">Total per day</p>
                </div>
            </div>

            {{-- Total Sale --}}
            <div class="col-md-3">
                <div class="card shadow-sm border rounded-4 p-3 h-100">
                    <h5 class="mb-0 fw-bold">Rp {{ number_format($totalSalesMonth,0,',','.') }}</h5>
                    <p class="mb-0 small text-muted">Total Sale</p>
                    <p class="mb-0 text-muted small">Total per month</p>
                </div>
            </div>

            {{-- Customers Waiting --}}
            <div class="col-md-3">
                <div class="card shadow-sm border rounded-4 p-3 h-100">
                    <h5 class="mb-0 fw-bold">{{ $waitingCustomers }}</h5>
                    <p class="mb-0 small text-muted">Customers Waiting</p>
                    <p class="mb-0 text-muted small">{{ $waitingCustomers }} orders pending</p>
                </div>
            </div>
        </div>

        {{-- Chart --}}
        <div class="row g-4">
            <div class="col-12">
                <div class="card shadow-sm border rounded-4 p-4 h-100">
                    <h6 class="fw-semibold text-secondary mb-3">Total in a Week</h6>
                    <div class="chart-container" style="height: 400px;">
                        <canvas id="weeklyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('weeklyChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($salesChart['labels']),
            datasets: [{
                label: 'Total',
                data: @json($salesChart['data']),
                backgroundColor: '#A5662B'
            }]
        },
        options: {
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { ticks: { color: '#888' }, beginAtZero: true },
                x: { ticks: { color: '#888' } }
            }
        }
    });
</script>
@endpush
