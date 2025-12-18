@extends('layouts.layout')

@section('content')
@include('partials.page-header', ['title' => 'Order History'])

<div class="container-fluid px-4">

    {{-- Filter --}}
    <form method="GET"
      action="{{ route('seller.orders.history') }}"
      class="d-flex flex-wrap align-items-center gap-3 mb-4">

    <input type="date"
           name="date"
           class="form-control w-auto shadow-sm" />

    <input type="text"
           name="search"
           class="form-control w-auto shadow-sm"
           placeholder="Search Order ID"
           value="{{ request('search') }}" />

    <select name="payment"
            class="form-select w-auto shadow-sm">
        <option value="">All Payment Method</option>
        <option value="QRIS" {{ request('payment') === 'QRIS' ? 'selected' : '' }}>
            QRIS
        </option>
    </select>

    {{--  FILTER STATUS  --}}
    <select name="status"
            class="form-select w-auto shadow-sm"
            onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="FINISH" {{ request('status') === 'FINISH' ? 'selected' : '' }}>
            Finish
        </option>
        <option value="CANCEL" {{ request('status') === 'CANCEL' ? 'selected' : '' }}>
            Cancel
        </option>
    </select>

</form>


    {{-- TABLE --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            <table class="table table-hover align-middle mb-0" style="width: 100%;">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Table</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Total Price</th>
                        <th>Order Detail</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($orders as $order)
                    <tr>
                        {{-- DATE --}}
                        <td>{{ \Carbon\Carbon::parse($order->order_datetime)->format('d-m-Y') }}</td>

                        {{-- ORDER ID --}}
                        <td>{{ 'ORD-' . str_pad($order->order_id, 3, '0', STR_PAD_LEFT) }}</td>

                        {{-- CUSTOMER NAME --}}
                        <td>{{ $order->customer_name ?? '-' }}</td>

                        <td>
                          {{ 'MEJA-' . str_pad($order->meja_number, 2, '0', STR_PAD_LEFT) }}
                        </td>

                        {{-- PAYMENT METHOD --}}
                        <td>{{ $order->payment?->payment_method ?? 'QRIS' }}</td>


                        {{-- STATUS FIXED --}}
                        <td>
                        @if ($order->status_order === 'CLOSE' && $order->status_bayar === 'PAID')
    <span class="badge bg-success">Finish</span>

@elseif ($order->status_order === 'CLOSE' && $order->status_bayar === 'UNPAID')
    <span class="badge bg-danger">Cancelled</span>
@endif
</td>

                        {{-- TOTAL --}}
                        <td>
                            Rp {{ number_format($order->details->sum('subtotal'), 0, ',', '.') }}
                        </td>

                        {{-- BUTTON DETAIL --}}
                        <td>
                            <button class="btn btn-outline-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#detailModal{{ $order->order_id }}">
                                Detail
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>

</div>

{{-- =========================
      MODAL DETAIL ORDER
   ========================= --}}
@foreach ($orders as $order)
<div class="modal fade" id="detailModal{{ $order->order_id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4 shadow">

            <div class="modal-header border-0">
                <h5 class="modal-title"> Order {{ 'ORD-' . str_pad($order->order_id, 3, '0', STR_PAD_LEFT) }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <p><strong>Date:</strong>
    {{ \Carbon\Carbon::parse($order->order_datetime)->format('d-m-Y H:i') }}
</p>

<p><strong>Customer Name:</strong>
    {{ $order->customer_name ?? '-' }}
</p>

<p><strong>Table Number:</strong>
{{ 'MEJA-' . str_pad($order->meja_number, 2, '0', STR_PAD_LEFT) }}
</p>

<p><strong>Payment Method:</strong>
    {{ $order->payment?->payment_method ?? 'QRIS' }}
</p>

{{-- STATUS ORDER --}}
<p>
    <strong>Status:</strong>

    @if ($order->status_order === 'CLOSE' && $order->status_bayar === 'UNPAID')
        <span class="badge bg-danger ms-2">Cancelled</span>
    @elseif ($order->status_order === 'CLOSE' && $order->status_bayar === 'PAID')
        <span class="badge bg-success ms-2">Finish</span>
    @endif
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
                            <td>{{ $detail->menu->menu_name ?? 'Unknown' }}</td>

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

<style>
    .table td.notes {
        white-space: normal !important;
        word-wrap: break-word;
        max-width: 250px;
    }
</style>

@endsection
