@extends('layouts.app')

@section('body')
<section class="hero-wrap hero-wrap-2" style="background-image: url({{ asset('assets/img/bg-1.jpg') }});"
    data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
            <div class="col-md-9 text-center mb-5">
                <p class="breadcrumbs mb-0">
                    <span class="mr-2"><a href="{{ route('home') }}">Home <i class="fa fa-chevron-right"></i></a></span>
                    <span>Order History <i class="fa fa-chevron-right"></i></span>
                </p>
                <h2 class="mb-0 bread">My Orders</h2>
            </div>
        </div>
    </div>
</section>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Your Order History</h4>
                <span class="badge bg-info text-white p-2 rounded-pill">{{ $orders->count() }} orders</span>
            </div>

            @if($orders->count())
            <div class="row g-4">
                @foreach($orders as $order)
                <div class="col-12 mb-3 shadow">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                                <div class="mb-3 mb-md-0">
                                    <h5 class="mb-1">Order #{{ $order->id }}</h5>
                                    <small class="text-muted">Placed on {{ $order->created_at->format('d M Y')
                                        }}</small>
                                </div>
                                <div class="d-flex flex-column flex-sm-row align-items-sm-end gap-3">
                                    <div class="text-end">
                                        <h4 class="mb-0 text-primary">${{ number_format($order->total_price, 2) }}</h4>
                                    </div>
                                    <div>
                                        <a href="{{ route('orders.show', $order->id) }}"
                                            class="btn btn-sm btn-outline-primary px-3 mx-2">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- Order Status --}}
                            <div class="mt-3">
                                @if($order->status === 'ready')
                                <p class="text-success mb-0"><i class="fas fa-store"></i> Picked up from store</p>
                                @elseif($order->status === 'delivered')
                                <p class="text-success mb-0"><i class="fas fa-check-circle"></i> Delivered to your
                                    address</p>
                                @elseif($order->status === 'pending')
                                <p class="text-warning mb-0"><i class="fas fa-hourglass-half"></i> The shop is
                                    processing your order</p>
                                @elseif($order->status === 'shipping')
                                <p class="text-info mb-0"><i class="fas fa-truck"></i> Out for delivery</p>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer text-muted">
                            {{-- Location --}}
                            @if($order->user_address_id)
                            @if($order->userAddress->building && $order->userAddress->street &&
                            $order->userAddress->city && $order->userAddress->country)
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $order->userAddress->country }},
                                {{ $order->userAddress->city }},
                                {{ $order->userAddress->street }},
                                {{ $order->userAddress->building }}
                            </p>
                            @elseif($order->userAddress->location_link)
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt"></i>
                                <a href="{{ $order->userAddress->location_link }}" target="_blank"
                                    class="text-decoration-none">
                                    View on Map
                                </a>
                            </p>
                            @elseif($order->userAddress->latitude && $order->userAddress->longtitude)
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt"></i>
                                Coordinates: {{ $order->userAddress->latitude }}, {{ $order->userAddress->longtitude }}
                            </p>
                            @endif
                            @else
                            <p class="mb-0"><i class="fas fa-home"></i> No address provided</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="fas fa-shopping-bag fa-3x text-muted mb-4"></i>
                    <h5 class="mb-3">No orders yet</h5>
                    <p class="text-muted mb-4">You haven't placed any orders yet. Start shopping to see your order
                        history here.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary px-4">
                        Start Shopping
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
