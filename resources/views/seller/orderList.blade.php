@extends('layouts.layout')

@section('content')

@include('partials.page-header', ['title' => 'Order List'])

<div class="container-fluid px-4">

    {{-- Filter --}}
    <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
        <input type="text" class="form-control w-auto shadow-sm" placeholder="Search Order ID" />
        <select class="form-select w-auto shadow-sm">
            <option value="">All Status</option>
            <option value="In Progress">In Progress</option>
            <option value="Finish">Finish</option>
        </select>
    </div>

    {{-- TABLE --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 table-responsive" style="overflow: visible !important;">

            <table class="table table-hover align-middle mb-0" style="position: relative;">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($orders as $order)
                        <tr>

                            {{-- DATE --}}
                            <td>{{ \Carbon\Carbon::parse($order->order_datetime)->format('d-m-Y') }}</td>

                            {{-- ORDER ID --}}
                            <td>{{ 'ORD-' . str_pad($order->order_id, 3, '0', STR_PAD_LEFT) }}</td>

                            {{-- CUSTOMER --}}
                            <td>{{ $order->customer_name }}</td>

                            {{-- PAYMENT --}}
                            <td>{{ $order->payment?->payment_method ?? 'QRIS' }}</td>

                            {{-- STATUS BADGE (NO WAITING ANYMORE) --}}
                            <td>
                                @if ($order->status_order === 'OPEN' && $order->status_bayar === 'PAID')
                                    <span class="badge bg-info text-dark">In Progress</span>

                                @elseif ($order->status_order === 'CLOSE' && $order->status_bayar === 'PAID')
                                    <span class="badge bg-success">Finish</span>

                                @elseif ($order->status_order === 'CLOSE' && $order->status_bayar === 'UNPAID')
                                    <span class="badge bg-danger">Cancel</span>

                                @else
                                    {{-- DEFAULT → IN PROGRESS --}}
                                    <span class="badge bg-info text-dark">In Progress</span>
                                @endif
                            </td>

                            {{-- TOTAL --}}
                            <td>Rp {{ number_format($order->details->sum('subtotal'), 0, ',', '.') }}</td>

                            {{-- ACTION --}}
                            <td>
                                <div class="d-flex gap-2">

                                    {{-- DETAIL --}}
                                    <button class="btn btn-outline-primary btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#orderDetailModal{{ $order->order_id }}">
                                        Detail
                                    </button>

                                    {{-- UPDATE STATUS --}}
                                    <div class="dropdown order-status-dropdown">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                                type="button"
                                                data-bs-toggle="dropdown">
                                            Update Status
                                        </button>

                                        <ul class="dropdown-menu status-menu">
                                            {{-- IN PROGRESS --}}
                                            <li>
                                                <form action="{{ route('seller.orders.updateStatus', $order->order_id) }}"
                                                      method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status_ui" value="IN_PROGRESS">
                                                    <button type="submit" class="dropdown-item">
                                                        In Progress
                                                    </button>
                                                </form>
                                            </li>

                                            {{-- SENT --}}
                                            <li>
                                                <form action="{{ route('seller.orders.updateStatus', $order->order_id) }}"
                                                      method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status_ui" value="FINISHED">
                                                    <button type="submit" class="dropdown-item">
                                                        Finish
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>

</div>


{{-- DETAIL MODALS --}}
@foreach ($orders as $order)
<div class="modal fade" id="orderDetailModal{{ $order->order_id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4 shadow">

            <div class="modal-header border-0">
                <h5 class="modal-title">Order {{ 'ORD-' . str_pad($order->order_id, 3, '0', STR_PAD_LEFT) }}</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <p><strong>Date:</strong>
    {{ \Carbon\Carbon::parse($order->order_datetime)->format('d-m-Y') }}
</p>

<p><strong>Name:</strong>
    {{ $order->customer_name }}
</p>

<p><strong>Table Number:</strong>
{{ 'MEJA-' . str_pad(max(1, (int) $order->meja_number), 2, '0', STR_PAD_LEFT) }}
</p>

<p><strong>Payment:</strong>
    {{ $order->payment?->payment_method ?? 'QRIS' }}
</p>

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
                        @foreach ($order->details as $detail)
                            <tr>
                                <td>{{ $detail->menu->menu_name ?? 'Menu deleted' }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td class="notes">{{ $detail->notes ?? '-' }}</td>
                                <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="text-end fw-bold mt-3">
                    Total: Rp {{ number_format($order->details->sum('subtotal'), 0, ',', '.') }}
                </div>

            </div>

        </div>
    </div>
</div>
@endforeach


{{-- FIX DROPDOWN NOT CLICKABLE & WHITE BACKGROUND --}}
<style>
    .dropdown-menu.status-menu {
        background: white !important;
        border-radius: 10px;
        box-shadow: 0px 4px 12px rgba(0,0,0,0.15);
        padding: 8px 0;
        position: absolute !important;
        z-index: 999999 !important;
    }

    .dropdown-menu.status-menu .dropdown-item:hover {
        background: #f0f0f0 !important;
    }

    /* Notes wrapping */
    .table td.notes {
        white-space: normal !important;
        max-width: 280px;
    }
</style>

@endsection
