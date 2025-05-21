@extends('layouts.app')

@section('body')
<section class="hero-wrap hero-wrap-2" style="background-image: url({{asset('assets/img/bg-1.jpg')}});"
    data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
            <div class="col-md-9 ftco-animate mb-5 text-center">
                <p class="breadcrumbs mb-0"><span class="mr-2"><a href="{{ route('home') }}">Home <i
                                class="fa fa-chevron-right"></i></a></span>
                    <a href="{{ route('products') }}"> <span> Products <i class="fa fa-chevron-right"></i></span></a>
                </p>
                <h2 class="mb-0 bread">Filtered Products</h2>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">

        <div class="row mb-4">
            <div class="col-md-12 d-flex justify-content-between align-items-center">
                <h4 class="product-select">Filtered Products</h4>
            </div>
        </div>
        {{-- Filter Form --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-3">
                    <form method="GET" action="{{ route('filtered.products') }}" class="row g-3 align-items-center">
                        <!-- Search Input -->
                        <div class="col-md-3 mb-2">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Product name..."
                                    value="{{ request('search') }}">
                                <span class="input-group-text">
                                    <i class="fa fa-search"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Category Dropdown -->
                        <div class="col-md-2 mb-2">
                            <select name="category" class="form-select select2">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category')==$category->id ? 'selected' :
                                    '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div class="col-md-3 mb-2">
                            <div class="input-group">
                                <input type="number" name="min_price" class="form-control" placeholder="Min"
                                    value="{{ request('min_price') }}">
                                <span class="input-group-text">-</span>
                                <input type="number" name="max_price" class="form-control" placeholder="Max"
                                    value="{{ request('max_price') }}">
                            </div>
                        </div>

                        <!-- Discount Dropdown -->
                        <div class="col-md-2 mb-2">
                            <select name="discount" class="form-select select2">
                                <option value="">Any</option>
                                <option value="1" {{ request('discount')==='1' ? 'selected' : '' }}>On Discount</option>
                                <option value="0" {{ request('discount')==='0' ? 'selected' : '' }}>No Discount</option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="col-md-2 d-flex gap-2 mb-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fa fa-check me-1"></i> Apply
                            </button>
                            <a href="{{ route('filtered.products') }}" class="btn btn-outline-secondary">
                                <i class="fa fa-sync-alt"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            @forelse ($filteredProducts as $product)
            <div class="col-md-3 d-flex">
                <div class="product ftco-animate">
                    <div class="img d-flex align-items-center justify-content-center"
                        style="background-image: url('{{ asset($product->image) }}');">
                        <div class="desc">
                            <p class="meta-prod d-flex">
                                <a href="javascript:void(0);"
                                    class="add-to-cart-btn d-flex align-items-center justify-content-center"
                                    data-id="{{ $product->id }}">
                                    <span class="flaticon-shopping-bag"></span></a>
                                <a href="javascript:void(0)" onclick="addToWsihList({{ $product->id }})"
                                    class="d-flex align-items-center justify-content-center"
                                    data-product-id="{{ $product->id }}">
                                    <span class="flaticon-heart"></span></a>
                                <a href="{{ route('product.show', $product->id) }}"
                                    class="d-flex align-items-center justify-content-center"><span
                                        class="flaticon-visibility"></span></a>
                            </p>
                        </div>
                    </div>
                    <div class="text text-center">
                        @if(
                        $product->discount > 0 &&
                        $product->discount_start !== null &&
                        \Carbon\Carbon::now()->greaterThanOrEqualTo($product->discount_start) &&
                        ($product->discount_end === null ||
                        \Carbon\Carbon::now()->lessThanOrEqualTo($product->discount_end))
                        )

                        <span class="sale">{{ $product->discount }}% Off</span>
                        <p class="mb-0">
                            <span class="price price-sale">${{ number_format($product->price, 2) }}</span>
                            <span class="price">${{ number_format($product->price * (1 - $product->discount /
                                100), 2) }}</span>
                        </p>
                        @else
                        <p class="mb-0">
                            <span class="price">${{ number_format($product->price, 2) }}</span>
                        </p>
                        @endif
                        <span class="category">{{ $product->category->name ?? 'No Category' }}</span>
                        <h2>{{ $product->name }}</h2>
                    </div>
                </div>
            </div>
            @empty

            <div class="col-12 text-center">
                <p>No products found matching your filters.</p>
            </div>
            @endforelse
        </div>

        {{-- pagination links --}}
        {{-- @if (!$filteredProducts->empty()) --}}

        <div class="block-27">
            <ul>
                @if ($filteredProducts->onFirstPage())
                <li class="disabled"><span>&lt;</span></li>
                @else
                <li><a href="{{ $filteredProducts->previousPageUrl() }}">&lt;</a></li>
                @endif
                @foreach ($filteredProducts->getUrlRange(1, $filteredProducts->lastPage()) as $page => $url)
                @if ($page == $filteredProducts->currentPage())
                <li class="active"><span>{{ $page }}</span></li>
                @else
                <li><a href="{{ $url }}">{{ $page }}</a></li>
                @endif
                @endforeach
                @if ($filteredProducts->hasMorePages())
                <li><a href="{{ $filteredProducts->nextPageUrl() }}">&gt;</a></li>
                @else
                <li class="disabled"><span>&gt;</span></li>
                @endif
            </ul>
        </div>

        {{-- @endif --}}
</section>
@endsection
@push('script')
<script>
    $(document).ready(function() {
        $('.select2').select2({
    width: '100%',

});
   });
    // Add to Cart button click event
  $('.add-to-cart-btn').click(function(e) {
        e.preventDefault();

        var product_id = $(this).data('id');
        var quantity = 1; // Quantity is always 1 for this setup

        // AJAX request to add the product to the cart
        $.ajax({
            url: "{{ route('cart.add') }}", // Route to the cart add method
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}", // CSRF token for security
                product_id: product_id,
                quantity: quantity // Always send quantity as 1
            },
            success: function(response) {
                if(response.success) {
                    // Update the cart count in the navbar
                    $('#cart-count').text(response.totalItems);

                    showMessage('Product added to cart successfully!', '#4CAF50'); // Green for success
                } else {
                    showMessage('Failed to add the product to the cart. Please try again.', '#F44336'); // Red for error
                }
            },
            error: function(xhr) {
                // Handle error
                showMessage('Oops! Something went wrong. Please try again later.', '#F44336'); // Red for error
            }
        });
    });
    function addToWsihList(id) {
    $.ajax({
        url: "{{ route('wishlist.store') }}",
        method: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            id: id
        },
        success: function(response) {
            let color = '#4CAF50'; // Default color for success
            let message = response.message;

            // Change color based on response type
            if (response.type === 'cart') {
                color = '#2196F3'; // Blue for cart
                message = 'Product successfully added to your cart!';
            } else if (response.type === 'info') {
                color = '#FFC107'; // Amber for already in wishlist
                message = 'Product is already in your wishlist.';
            }

            showMessage(message, color);  // Display a success message
        },
        error: function(xhr) {
            console.log('Error: ' + xhr.responseText);
            showMessage('Oops! Something went wrong. Please try again later.', '#F44336'); // Red for error
        }
    });
}
</script>
@endpush
