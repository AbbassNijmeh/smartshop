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
                    <span>Order Details <i class="fa fa-chevron-right"></i></span>
                </p>
                <h2 class="mb-0 bread">Order #{{ $order->id }}</h2>
            </div>
        </div>
    </div>
</section>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">Order Information</h4>
                    <p><strong>Order ID:</strong> {{ $order->id }}</p>
                    <p><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</p>
                    <p><strong>Total Price:</strong> ${{ number_format($order->total_price, 2) }}</p>

                </div>
                <div class="card-footer">
                     {{-- location --}}
                            @if ($order->user_address_id)
                            @if (
                            $order->userAddress->building &&
                            $order->userAddress->street &&
                            $order->userAddress->city &&
                            $order->userAddress->country
                            )
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $order->userAddress->country }},
                                {{ $order->userAddress->city }},
                                {{ $order->userAddress->street }},
                                {{ $order->userAddress->building }}
                            </p>
                            @elseif ($order->userAddress->location_link)
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt"></i>
                                <a href="{{ $order->userAddress->location_link }}" target="_blank"
                                    class="text-decoration-none">
                                    View on Map
                                </a>
                            </p>
                            @elseif ($order->userAddress->latitude && $order->userAddress->longtitude)
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

            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-4">Order Items</h4>
                    <ul class="list-group list-group-flush">
                        @foreach($order->orderItems as $item)
                        <li class="list-group-item">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                                <div>
                                    <strong>Product:</strong> {{ $item->product->name }}<br>
                                    <strong>Qty:</strong> {{ $item->quantity }}<br>
                                    <strong>Price:</strong> ${{ number_format($item->price, 2) }}
                                </div>
                                <div class="mt-3 mt-md-0 text-md-right">
                                    <strong>Subtotal:</strong><br>
                                    ${{ number_format($item->quantity * $item->price, 2) }}
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('user.orderHistory') }}" class="btn btn-outline-secondary">← Back to Orders</a>
            </div>
        </div>
    </div>
</div>
@endsection
