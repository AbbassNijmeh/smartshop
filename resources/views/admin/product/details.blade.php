@extends('layouts.admin')

@section('body')
<div class="container mt-4">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-3 rounded">
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">Product #{{ $product->id }}</li>
        </ol>
    </nav>

    <!-- Product Details Card -->
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white text-center py-3">
            <h4 class="mb-0"><i class="fas fa-box"></i> {{ $product->name }}</h4>
        </div>
        <div class="card-body">
            @if ($product->image)

            <div class="row">

                <div class="col-12 text-center mb-3">
                    <!-- Image Container -->
                    <div class="position-relative d-inline-block">
                        <img src="{{ asset($product->image) }}" class="img-fluid rounded shadow-sm w-100"
                            alt="Product Image" style="max-height: 250px; object-fit: contain;">

                        <!-- Delete Image Badge positioned at the top-right corner -->
                        <span class="badge bg-danger rounded-circle p-2 delete-pic-btn" data-id="{{ $product->id }}"
                            data-image="{{ $product->image }}" data-toggle="modal" data-target="#deletePicModal"
                            style="cursor: pointer; font-size: 18px; position: absolute; top: 5px; right: 5px; z-index: 1;">
                            &times;
                        </span>
                    </div>
                </div>
            </div>
            @else
            <p class="text-muted text-center">No Image Available</p>
            @endif

            <div class="row g-3">
                <div class="col-md-6">
                    <p><strong>Product ID:</strong> {{ $product->id }}</p>
                    <p><strong>Category:</strong> {{ $product->category->name }}</p>
                    <p><strong>Description:</strong> {{ $product->description }}</p>
                    <p><strong>Brand:</strong> {{ $product->brand }}</p>
                    <p><strong>Cost Price:</strong> <span class="text-danger">${{ number_format($product->cost_price, 2)
                            }}</span></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Sale Price:</strong> <span class="text-success fw-bold">${{
                            number_format($product->price, 2) }}</span></p>
                    <p><strong>Stock:</strong> {{ $product->stock_quantity }} units</p>
                    <p><strong>Discount:</strong> {{ $product->discount }}% ({{ $product->discount_start }} - {{
                        $product->discount_end }})</p>
                    <p><strong>Expiration Date:</strong> {{ $product->expiration_date ?? 'N/A' }}</p>
                    <p><strong>Weight:</strong> {{ number_format($product->weight,2) }} g</p>
                </div>
                <div class="col-12">
                    <p><strong>Dimensions:</strong> {{ $product->dimensions }}</p>
                    <p><strong>Barcode:</strong> {{ $product->barcode }}</p>
                    <p><strong>Location:</strong> <span class="badge bg-secondary">Aisle: {{ $product->aisle }},
                            Section: {{ $product->section }}, Floor: {{ $product->floor }}</span></p>
                    <p><strong>Ingredients:</strong> {{ $product->ingredients->pluck('name')->join(', ') }}</p>
                </div>
            </div>
        </div>
        <!-- Action Buttons -->
        <div class="card-footer d-flex flex-column flex-md-row justify-content-between gap-3 p-3">
            <a href="{{ route('products.index') }}"
                class="btn btn-secondary w-100 w-md-auto d-flex align-items-center justify-content-center gap-2 py-3">
                <i class="fas fa-arrow-left"></i> Back to Products
            </a>
            <div class="d-flex flex-column flex-md-row gap-3 w-100 w-md-auto">
                <a href="{{ route('products.edit', $product->id) }}"
                    class="btn btn-warning w-100 w-md-auto d-flex align-items-center justify-content-center gap-2 py-3">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <button
                    class="btn btn-danger w-100 w-md-auto d-flex align-items-center justify-content-center gap-2 py-3"
                    data-toggle="modal" data-target="#deleteModal">
                    <i class="fas fa-trash-alt"></i> Delete
                </button>
            </div>
        </div>


        <!-- Reviews Section -->
        <h5 class="mt-4 px-3">Product Reviews</h5>
        @if ($product->reviews->isEmpty())
        <p class="text-muted px-5 text-center ">No reviews yet.</p>
        @else

        <div class="list-group px-3 pb-3">

            @foreach($product->reviews as $review)
            <div class="list-group-item d-flex justify-content-between align-items-start flex-wrap">
                <div>
                    <div class="fw-bold">{{ $review->user->name }}</div>
                    <div class="text-muted">Rating: {{ $review->rating }} / 5</div>
                    <p class="mb-0">{{ $review->comment }}</p>
                </div>
                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="ms-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
            @endforeach
        </div>
        @endif



    </div>
</div>
<!-- Delete Image Modal -->
<div class="modal fade" id="deletePicModal" tabindex="-1" aria-labelledby="deletePicModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePicModalLabel">Delete Product Image</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <form id="deletePicForm" action="{{ route('products.deletePic') }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" id="deletePicProductId" name="product_id">
                <input type="hidden" id="deletePicImage" name="image">

                <div class="modal-body">
                    <p id="deletePicConfirmationMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Image</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this product?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Event listener for the Delete Image button
        document.addEventListener('click', function (event) {
            if (event.target && event.target.classList.contains('delete-pic-btn')) {
                const productId = event.target.getAttribute('data-id');
                const productImage = event.target.getAttribute('data-image');

                // Update the modal with the product info
                document.getElementById('deletePicProductId').value = productId;
                document.getElementById('deletePicImage').value = productImage;
                document.getElementById('deletePicConfirmationMessage').textContent = "Are you sure you want to delete the image for this product?";
            }
        });
    });
</script>

@endpush
