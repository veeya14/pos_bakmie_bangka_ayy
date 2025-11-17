@extends('layouts.layout')

@section('content')
{{-- Header --}}
@include('partials.page-header', [
    'title' => 'Order List',
])

<div class="container-fluid px-4">
    {{-- Filter dan Pencarian --}}
    <form method="GET" class="d-flex flex-wrap align-items-center gap-3 mb-4">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control w-auto shadow-sm" placeholder="Search Order ID" />
        <select name="status" class="form-select w-auto shadow-sm">
            <option value="">All Status</option>
            <option value="OPEN" {{ request('status')=='OPEN'?'selected':'' }}>In Progress</option>
            <option value="CLOSE" {{ request('status')=='CLOSE'?'selected':'' }}>Finish</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    </form>

    {{-- Tabel Daftar Pesanan --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Total Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->order_datetime?->format('d-m-Y H:i') ?? '-' }}</td>
                        <td>{{ $order->order_id }}</td>
                        <td>{{ $order->meja->nama_customer ?? '-' }}</td>
                        <td>{{ $order->payment->method ?? '-' }}</td>
                        <td>
                            @if($order->status_order == 'OPEN')
                                <span class="badge bg-info text-dark">In Progress</span>
                            @else
                                <span class="badge bg-success text-dark">Finish</span>
                            @endif
                        </td>
                        <td>Rp {{ number_format($order->orderDetails->sum('subtotal'), 0, ',', '.') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                {{-- Button Modal --}}
                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $order->order_id }}">
                                    Order Detail
                                </button>

                                {{-- Dropdown Update Status --}}
                                <div class="btn-group">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                        Update Status
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <form method="POST" action="{{ route('seller.orders.updateStatus', $order->order_id) }}">
                                                @csrf
                                                <input type="hidden" name="status_order" value="OPEN">
                                                <button type="submit" class="dropdown-item">In Progress</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form method="POST" action="{{ route('seller.orders.updateStatus', $order->order_id) }}">
                                                @csrf
                                                <input type="hidden" name="status_order" value="CLOSE">
                                                <button type="submit" class="dropdown-item">Finish</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>

                    {{-- Modal Detail Order --}}
                    <div class="modal fade" id="detailModal{{ $order->order_id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content border-0 rounded-4 shadow">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">Detail Order #{{ $order->order_id }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Date:</strong> {{ $order->order_datetime?->format('d-m-Y H:i') ?? '-' }}</p>
                                    <p><strong>Customer:</strong> {{ $order->meja->nama_customer ?? '-' }}</p>
                                    <p><strong>Payment Method:</strong> {{ $order->payment->method ?? '-' }}</p>
                                    <hr>
                                    <table class="table align-middle">
                                        <thead>
                                            <tr>
                                                <th>Menu</th>
                                                <th>Qty</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order->orderDetails as $detail)
                                            <tr>
                                                <td>{{ $detail->menu->menu_name ?? '-' }}</td>
                                                <td>{{ $detail->quantity }}</td>
                                                <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="text-end fw-bold mt-3">
                                        Total: Rp {{ number_format($order->orderDetails->sum('subtotal'), 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No orders found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- CSS agar table dan modal rapi --}}
<style>
    .modal-body {
        overflow-x: auto;
    }
    .text-end.fw-bold {
        border-top: 2px solid #ddd;
        padding-top: 10px;
    }
</style>
@endsection
