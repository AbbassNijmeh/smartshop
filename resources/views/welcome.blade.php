@extends('layouts.app')

@section('body')

<div class="hero-wrap" style="background-image: url({{asset('assets/img/bg-1.jpg')}});"
    data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
            <div class="col-md-8 ftco-animate d-flex align-items-end">
                <div class="text w-100 text-center">
                    <h1 class="mb-4">Good <span>products</span> for Good <span>Moments</span>.</h1>
                    <p>
                        <a href="{{ route('products') }}" class="btn btn-primary py-2 px-4">Shop Now</a>
                        <a href="#" class="btn btn-white btn-outline-white py-2 px-4">Read more</a>
                    </p>
                    <p class="mt-3">
                        <button class="btn btn-outline-light" id="scanBarcodeTrigger">
                            <i class="fa fa-barcode me-2"></i> Scan Barcode
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal HTML -->
<div id="scanModal"
    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.7); justify-content:center; align-items:center; z-index:100;">
    <div style="background:white; padding:20px; border-radius:10px; text-align:center; position:relative;">
        <h2 class="p-2">Welcome To Our Store!</h2>
        <p class="p-2">Scan your product to see details</p>
        <button id="startScanBtn"
            style="padding:10px 20px; background-color: #4CAF50; color:white; border:none; border-radius:5px;">
            Start Scanning
        </button>
        <div id="reader" style="width:300px; margin-top:20px; display:none;"></div>
        <div id="product-details" style="margin-top:20px;"></div>
        <button id="closeModal"
            style="position:absolute; top:10px; right:10px; background:none; border:none; font-size:20px;">&times;</button>
    </div>
</div>


<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<section class="ftco-section ftco-no-pb">
    <div class="container">
        <div class="row g-3 justify-content-center">
            @foreach($categories->take(5) as $category)
            <div class="col-lg-2 col-md-4 col-6">
                <div class="sort w-100 text-center p-3 bg-light border rounded ftco-animate shadow-sm">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2"
                         style="width:80px; height:80px; background-color:#f5f5f5; font-size:28px; color:#333;">
                        {{ strtoupper(substr($category->name, 0, 1)) }}
                    </div>
                    <h6 class="mb-0">{{ $category->name }}</h6>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="ftco-section ftco-no-pb">
    <div class="container">
        <div class="row justify-content-center pb-5">
            <div class="col-md-7 heading-section text-center ftco-animate">
                <span class="subheading">Our Delightful offerings</span>
                <h2>Tastefully Yours</h2>
            </div>
        </div>
        <div class="row">
            @foreach ($products as $product)
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
                                    class="d-flex align-items-center justify-content-center">
                                    <span class="flaticon-visibility"></span></a>
                            </p>
                        </div>
                    </div>
                    <div class="text text-center">
                        <span class="category"><a href="#">{{ $product->category->name }}</a></span>
                        <h2>{{ $product->name }}</h2>
                        <div class="rating">
                            @for ($i = 1; $i <= 5; $i++) @if ($i <=$product->rating)
                                <i class="fas fa-star"></i>
                                @elseif ($i == ceil($product->rating))
                                <i class="fas fa-star-half-alt"></i>
                                @else
                                <i class="far fa-star"></i>
                                @endif
                                @endfor
                        </div>
                        <span class="sale">{{ $product->discount }}% Off</span>
                        <p class="mb-0">
                            <span class="price price-sale">$ {{ number_format($product->price, 2) }}</span>
                            <span class="price">$ {{ number_format(($product->price * (1 - $product->discount / 100)),
                                2) }}</span>
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="row justify-content-center py-2">
            <div class="col-md-4">
                <a href="{{ route('products') }}" class="btn btn-primary d-block">View All Products <span
                        class="fa fa-long-arrow-right"></span></a>
            </div>
        </div>
    </div>

    <!-- Product Detail Modal -->
    <div class="modal fade" id="productDetailModal" tabindex="-1" aria-labelledby="productDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="productDetailModalLabel">Product Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                        aria-label="Close">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <!-- Product Image -->
                        <div class="col-md-5 text-center mb-3 mb-md-0">
                            <img id="modalProductImage" src="" alt="Product Image" class="img-fluid rounded shadow-sm"
                                style="max-height: 300px;">
                        </div>

                        <!-- Product Info -->
                        <div class="col-md-7">
                            <h4 id="modalProductName" class="mb-3 fw-bold">Product Name</h4>

                            <div id="modalProductPrice" class="mb-3 fs-5 text-primary"></div>

                            <div id="modalProductReviews" class="mb-2 text-muted small"></div>

                            <div id="modalProductIngredients" class="mb-3 text-dark small"></div>

                            <!-- Quantity -->
                            <div class="d-flex align-items-center mb-3">
                                <label for="modalQuantity" class="me-2">Qty:</label>
                                <input type="number" id="modalQuantity" class="form-control form-control-sm w-25"
                                    value="1" min="1">
                            </div>

                            <!-- Add to Cart -->
                            <button id="addToCartFromModal" type="button" class="btn btn-success" data-product-id="">
                                <i class="fa fa-cart-plus me-1"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</section>

@endsection

@push('script')
<script>

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

    let html5QrCode;

$('#scanBarcodeTrigger').on('click', function() {
  document.getElementById('scanModal').style.display = 'flex';
});

document.getElementById('closeModal').addEventListener('click', function() {
  document.getElementById('scanModal').style.display = 'none';
  if (html5QrCode) {
    html5QrCode.stop().then(() => {
      document.getElementById('reader').innerHTML = '';
    }).catch(err => console.error("Stop error:", err));
  }
});

document.getElementById('startScanBtn').addEventListener('click', function() {
  document.getElementById('reader').style.display = 'block';
  html5QrCode = new Html5Qrcode("reader");

  Html5Qrcode.getCameras().then(devices => {
    if (devices && devices.length) {
      html5QrCode.start(
        { facingMode: "environment" },
        {
          fps: 10,
          qrbox: { width: 250, height: 250 },
          formatsToSupport: [ Html5QrcodeSupportedFormats.UPC_A, Html5QrcodeSupportedFormats.EAN_13 ]
        },
        onScanSuccess
      ).catch(err => {
        console.error('Error starting scanner:', err);
        alert('Camera access denied or not available.');
      });
    } else {
      alert('No camera devices found.');
    }
  }).catch(err => console.error('Camera list error:', err));
});

function onScanSuccess(decodedText, decodedResult) {
  document.getElementById('barcodeInput').value = decodedText;
  document.getElementById('scanModal').style.display = 'none';
  if (html5QrCode) {
    html5QrCode.stop().then(() => {
      document.getElementById('reader').innerHTML = '';
    }).catch(err => console.error("Failed to stop scanner:", err));
  }
  $.ajax({
    url: "/api/product-by-barcode",
    method: "GET",
    data: { barcode: decodedText },
    success: function (product) {
      if (!product) {
        showMessage("Product not found", "#F44336");
        return;
      }

      $('#modalProductImage').attr('src', product.image_url);
      $('#modalProductName').text(product.name);

      if (product.discounted_price) {
        $('#modalProductPrice').html(
          `<span class="text-danger">$${parseFloat(product.discounted_price).toFixed(2)}</span>
           <del class="text-muted ml-2">$${parseFloat(product.price).toFixed(2)}</del>`
        );
      } else {
        $('#modalProductPrice').html(`$${parseFloat(product.price).toFixed(2)}`);
      }

      $('#addToCartFromModal').data('product-id', product.id);

      if (product.reviews_count !== undefined) {
        $('#modalProductReviews').html(`<small class="text-muted">${product.reviews_count} reviews</small>`);
      } else {
        $('#modalProductReviews').empty();
      }

      if (product.ingredients && product.ingredients.length > 0) {
        $('#modalProductIngredients').html(`<strong>Ingredients:</strong> ${product.ingredients.join(', ')}`);
      } else {
        $('#modalProductIngredients').html('');
      }

      const modal = new bootstrap.Modal(document.getElementById('productDetailModal'));
      modal.show();
    },
    error: function () {
      showMessage("Failed to fetch product details", "#F44336");
    }
  });
}

$('#addToCartFromModal').on('click', function () {
  const productId = $(this).data('product-id');

  $.ajax({
    url: "{{ route('cart.add') }}",
    method: "POST",
    data: {
      _token: "{{ csrf_token() }}",
      product_id: productId,
      quantity: 1
    },
    success: function (response) {
      if (response.success) {
        showMessage('Product added to cart!', '#4CAF50');
        $('#productDetailModal').modal('hide');
      } else {
        showMessage('Failed to add to cart.', '#F44336');
      }
    },
    error: function () {
      showMessage('Error adding to cart.', '#F44336');
    }
  });
});
</script>
@endpush
