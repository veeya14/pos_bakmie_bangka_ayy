@extends('layouts.layout')

@section('content')
    {{-- Header --}}
    @include('partials.page-header', ['title' => 'Order History'])

    <div class="container-fluid px-4">
        {{-- Filter dan Pencarian --}}
        <form method="GET" class="d-flex flex-wrap align-items-center gap-3 mb-4">
            <input type="date" name="date" value="{{ request('date') }}" class="form-control w-auto shadow-sm" />
            <input type="text" name="search" value="{{ request('search') }}" class="form-control w-auto shadow-sm" placeholder="Search Order ID" />
            <select name="payment_method" class="form-select w-auto shadow-sm">
                <option value="">All Payment Method</option>
                <option value="QRIS" {{ request('payment_method')=='QRIS' ? 'selected' : '' }}>QRIS</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        </form>

        {{-- Tabel Riwayat Order --}}
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
                            <th>Order Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->order_datetime?->format('d-m-Y H:i') ?? '-' }}</td>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->meja->nama_customer ?? '-' }}</td>
                            <td>{{ $order->payment->method ?? '-' }}</td>
                            <td>
                                <span class="badge bg-success">Finish</span>
                            </td>
                            <td>Rp {{ number_format($order->orderDetails->sum('subtotal'), 0, ',', '.') }}</td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $order->order_id }}">
                                    Order Detail
                                </button>
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
                                                    <th>Notes</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($order->orderDetails as $detail)
                                                <tr>
                                                    <td>{{ $detail->menu->menu_name ?? '-' }}</td>
                                                    <td>{{ $detail->quantity }}</td>
                                                    <td class="notes">{{ $detail->notes ?? '-' }}</td>
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .table td.notes {
            white-space: normal;
            word-wrap: break-word;
            max-width: 250px;
        }
        .modal-body {
            word-break: break-word;
            overflow-x: auto;
        }
        .text-end.fw-bold {
            border-top: 2px solid #ddd;
            padding-top: 10px;
        }
    </style>
@endsection
