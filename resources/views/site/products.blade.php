@extends('layouts.app')

@section('body')
<section class="hero-wrap hero-wrap-2" style="background-image: url({{asset('assets/img/bg-1.jpg')}});"
    data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
            <div class="col-md-9 ftco-animate mb-5 text-center">
                <p class="breadcrumbs mb-0"><span class="mr-2"><a href="{{ route('home') }}">Home <i
                                class="fa fa-chevron-right"></i></a></span> <span>Products <i
                            class="fa fa-chevron-right"></i></span></p>
                <h2 class="mb-0 bread">Products</h2>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">
        <!-- Filter Row -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-3">
                    <form method="GET" action="{{ route('filtered.products') }}" class="row g-3 align-items-center">
                        <!-- Search -->
                        <div class="col-md-3 mb-2">
                            <div class="input-group">
                                <input type="text" id="search" name="search" class="form-control"
                                    placeholder="Product name..." value="{{ request('search') }}">
                                <span class="input-group-text bg-light">
                                    <i class="fa fa-search"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="col-md-2 mb-2">
                            <select id="category" name="category" class="form-select select2">
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

                        <!-- Discount -->
                        <div class="col-md-2 mb-2">
                            <select name="discount" id="discount" class="form-select select2">
                                <option value="">Any</option>
                                <option value="1" {{ request('discount')==='1' ? 'selected' : '' }}>On Discount</option>
                                <option value="0" {{ request('discount')==='0' ? 'selected' : '' }}>No Discount</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
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
            {{-- @include('components.recommendations', ['recommendedProducts' => $recommendedProducts]) --}}

            @forelse ($products as $product)
            <div class="col-md-6 col-lg-3 d-flex">
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
                        @if(($product->discount > 0 && $product->discount_end === null) || ($product->discount >
                        0 &&
                        \Carbon\Carbon::now()->lessThanOrEqualTo($product->discount_end)))
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

        <!-- Pagination -->
        <div class="block-27 mt-5">
            <ul>
                @if ($products->onFirstPage())
                <li class="disabled"><span>&lt;</span></li>
                @else
                <li><a href="{{ $products->previousPageUrl() }}">&lt;</a></li>
                @endif
                @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                @if ($page == $products->currentPage())
                <li class="active"><span>{{ $page }}</span></li>
                @else
                <li><a href="{{ $url }}">{{ $page }}</a></li>
                @endif
                @endforeach
                @if ($products->hasMorePages())
                <li><a href="{{ $products->nextPageUrl() }}">&gt;</a></li>
                @else
                <li class="disabled"><span>&gt;</span></li>
                @endif
            </ul>
        </div>
    </div>
</section>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        $('.select2').select2({
    width: '100%',

});
   });


    $('.add-to-cart-btn').click(function(e) {
        e.preventDefault();
        var product_id = $(this).data('id');
        var quantity = 1;

        $.ajax({
            url: "{{ route('cart.add') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: product_id,
                quantity: quantity
            },
            success: function(response) {
                if(response.success) {
                    $('#cart-count').text(response.totalItems);
                    showMessage('Product added to cart successfully!', '#4CAF50');
                } else {
                    showMessage('Failed to add the product to the cart. Please try again.', '#F44336');
                }
            },
            error: function(xhr) {
                showMessage('Oops! Something went wrong. Please try again later.', '#F44336');
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
                let color = '#4CAF50';
                let message = response.message;

                if (response.type === 'cart') {
                    color = '#2196F3';
                    message = 'Product successfully added to your cart!';
                } else if (response.type === 'info') {
                    color = '#FFC107';
                    message = 'Product is already in your wishlist.';
                }

                showMessage(message, color);
            },
            error: function(xhr) {
                console.log('Error: ' + xhr.responseText);
                showMessage('Oops! Something went wrong. Please try again later.', '#F44336');
            }
        });
    }
</script>
@endpush
