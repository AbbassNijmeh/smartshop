@extends('layouts.app')

@section('body')
<section class="hero-wrap hero-wrap-2" style="background-image: url({{ asset('assets/img/bg-1.jpg') }});"
    data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
            <div class="col-md-9 ftco-animate mb-5 text-center">
                <p class="breadcrumbs mb-0"><span class="mr-2"><a href="{{ route('home') }}">Home <i
                                class="fa fa-chevron-right"></i></a></span> <span>Cart <i
                            class="fa fa-chevron-right"></i></span></p>
                <h2 class="mb-0 bread">My Cart</h2>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">
        <div class="row">
            <div class="table-wrap">
                <table class="table">
                    <thead class="thead-primary">
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalPrice = 0; @endphp
                        @if ($cartItems->count() > 0)
                        @foreach($cartItems as $item)
                        @php
                        $product = $item->product;
                        $itemTotal = $product->price * $item->quantity;
                        $totalPrice += $itemTotal;
                        @endphp
                        <tr class="alert" role="alert">
                            <td>
                                <label class="checkbox-wrap checkbox-primary">
                                    <input type="checkbox" checked>
                                    <span class="checkmark"></span>
                                </label>
                            </td>
                            <td>
                                <div class="img"
                                    style="background-image: url({{ asset('storage/' . $product->image) }});"></div>
                            </td>
                            <td>
                                <div class="email">
                                    <span>{{ $product->name }}</span>
                                    <span>{{ $product->description }}</span>
                                </div>
                            </td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td class="quantity">
                                <div class="input-group">
                                    <input type="number" name="quantity"
                                        class="quantity form-control input-number update-cart"
                                        value="{{ $item->quantity }}" min="1" data-id="{{ $item->id }}">
                                </div>
                            </td>
                            <td>${{ number_format($itemTotal, 2) }}</td>
                            <td>
                                <button type="button" class="close remove-item-btn" data-id="{{ $item->id }}"
                                    data-toggle="modal" data-target="#deleteModal">
                                    <span aria-hidden="true"><i class="fa fa-close"></i></span>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7" class="text-center">Your cart is empty.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row justify-content-end">
            <div class="col col-lg-5 col-md-6 mt-5 cart-wrap ftco-animate">
                <div class="cart-total mb-3">
                    <h3>Cart Totals</h3>

                    <p class="d-flex total-price">
                        <span>Total</span>
                        <span>${{ number_format($totalPrice, 2) }}</span>
                    </p>
                </div>
                <p class="text-center">
                    <a href="{{ route('checkout') }}" class="btn btn-primary py-3 px-4">Proceed to Checkout</a>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Removal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to remove this item from your cart?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Remove</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForm = document.getElementById('deleteForm');
        const removeItemButtons = document.querySelectorAll('.remove-item-btn');

        removeItemButtons.forEach(button => {
            button.addEventListener('click', function () {
                const itemId = this.getAttribute('data-id');
                deleteForm.action = `/cart/${itemId}/remove`;
            });
        });
    });

</script>

@endsection
