@extends('layouts.admin')

@section('body')
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-3 rounded">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Orders</a></li>
            <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->id }}</li>
        </ol>
    </nav>

    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white text-center py-3">
            <h4 class="mb-0"><i class="fas fa-receipt"></i> Order #{{ $order->id }}</h4>
        </div>

        <div class="card-body" id="printableArea">
            <div class="row g-3">
                <div class="col-12">
                    <!-- Order Details -->
                    <div class="row"><div class="col-6 col-md-4 fw-bold">Order ID:</div><div class="col-6 col-md-8">{{ $order->id }}</div></div>

                    <div class="row">
                        <div class="col-6 col-md-4 fw-bold">Status:</div>
                        <div class="col-6 col-md-8">
                            @if($order->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($order->status == 'processing')
                                <span class="badge bg-primary">Processing</span>
                            @elseif($order->status == 'shipped')
                                <span class="badge bg-secondary">Shipped</span>
                            @elseif($order->status == 'delivered')
                                <span class="badge bg-success">Delivered</span>
                            @elseif($order->status == 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @else
                                <span class="badge bg-light text-dark">{{ ucfirst($order->status) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 col-md-4 fw-bold">Customer:</div>
                        <div class="col-6 col-md-8">
                            @if($order->user)
                                {{ $order->user->name }}
                            @else
                                <span class="text-muted">Guest</span>
                            @endif
                        </div>
                    </div>

                    <div class="row"><div class="col-6 col-md-4 fw-bold">Email:</div><div class="col-6 col-md-8">{{ $order->user->email ?? 'N/A' }}</div></div>
                    <div class="row"><div class="col-6 col-md-4 fw-bold">Total Price:</div><div class="col-6 col-md-8 text-success fw-bold">${{ number_format($order->total_price, 2) }}</div></div>
                    <div class="row"><div class="col-6 col-md-4 fw-bold">Payment Method:</div><div class="col-6 col-md-8">{{ $order->payment->payment_method }}</div></div>

                    <!-- Delivery Location Section -->
                    @if($order->user_address_id && $order->userAddress)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5 class="fw-bold mb-3">Delivery Location</h5>

                            @if($order->userAddress->location_link)
                                <p class="mb-3">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <a href="{{ $order->userAddress->location_link }}" target="_blank" class="text-decoration-none">
                                        View on Map
                                    </a>
                                </p>
                            @elseif($order->userAddress->latitude && $order->userAddress->longtitude)
                                <div class="mb-3">
                                    <p><i class="fas fa-map-pin"></i>
                                        Coordinates: {{ $order->userAddress->latitude }}, {{ $order->userAddress->longtitude }}
                                    </p>
                                    <div id="map" style="height: 400px; width: 100%; border-radius: 8px;"></div>
                                </div>
                            @else
                                <p class="mb-3"><i class="fas fa-home"></i>
                                    {{ $order->userAddress->street }},
                                    {{ $order->userAddress->city }},
                                    {{ $order->userAddress->country }}
                                </p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="row"><div class="col-6 col-md-4 fw-bold">Created At:</div><div class="col-6 col-md-8">{{ $order->created_at->format('d M Y, H:i A') }}</div></div>
                </div>
            </div>

            <!-- Order Items -->
            <h5 class="mt-4">Order Items</h5>
            @if ($order->orderItems->isEmpty())
                <p class="text-muted">No items in this order</p>
            @else
                <div class="list-group">
                    @foreach($order->orderItems as $item)
                        <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <div class="fw-bold">{{ $item->product->name }}</div>
                            <div class="text-muted">Qty: {{ $item->quantity }}</div>
                            <div class="text-muted">${{ number_format($item->price, 2) }} each</div>
                            <div class="text-success fw-bold">${{ number_format($item->quantity * $item->price, 2) }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Card Footer -->
        <div class="card-footer d-flex flex-column flex-md-row justify-content-between gap-3 p-3">
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
            <div class="d-flex flex-column flex-md-row gap-3">
                <button class="btn btn-warning" onclick="printVoucher()">
                    <i class="fas fa-print"></i> Print
                </button>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteOrderModal">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteOrderModal" tabindex="-1" aria-labelledby="deleteOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteOrderModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to permanently delete this order?</p>
                    <form id="deleteOrderForm" action="{{ route('orders.destroy', $order->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="mb-3">
                            <label class="form-label">Order ID:</label>
                            <input type="text" class="form-control" value="{{ $order->id }}" readonly>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="deleteOrderForm" class="btn btn-danger">Delete Order</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printVoucher() {
        const printContents = document.getElementById('printableArea').innerHTML;
        const originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

    // Initialize Leaflet map
    document.addEventListener('DOMContentLoaded', function() {
        @if($order->user_address_id && $order->userAddress && $order->userAddress->latitude && $order->userAddress->longtitude)
            const map = L.map('map').setView([
                {{ $order->userAddress->latitude }},
                {{ $order->userAddress->longtitude }}
            ], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker([
                {{ $order->userAddress->latitude }},
                {{ $order->userAddress->longtitude }}
            ]).addTo(map)
              .bindPopup('Delivery Location<br>Order #{{ $order->id }}')
              .openPopup();
        @endif
    });
</script>
@endsection
