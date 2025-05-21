@extends('layouts.admin')

@section('body')
<div class="container mt-4">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="glass-morphism">
        <ol class="breadcrumb bg-transparent p-3 rounded-3">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.index') }}" class="text-decoration-none text-primary">
                    <i class="fas fa-home me-1"></i>Dashboard
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('products.index') }}" class="text-decoration-none text-primary">
                    <i class="fas fa-boxes me-1"></i>Products
                </a>
            </li>
            <li class="breadcrumb-item active text-muted" aria-current="page">
                <i class="fas fa-tag me-1"></i>#{{ $product->id }}
            </li>
        </ol>
    </nav>

    <!-- Product Card -->
    <div class="card glass-morphism border-0 overflow-hidden">
        <div class="card-header bg-gradient-primary text-white py-4 position-relative">
            <div class="floating-shapes">
                <div class="shape circle"></div>
                <div class="shape triangle"></div>
            </div>
            <h2 class="mb-0 fw-light">
                <i class="fas fa-box-open me-2"></i>{{ $product->name }}
            </h2>
        </div>

        <div class="card-body">
            <!-- Image Gallery -->
            <div class="row g-4 mb-5">
                <div class="col-md-5">
                    <div class="image-container hover-scale">
                        @if ($product->image)
                        <img src="{{ asset($product->image) }}" alt="Product Image"
                            class="img-fluid rounded-3 shadow-sm w-100">
                        <button class="btn btn-danger btn-floating delete-pic-btn" data-id="{{ $product->id }}"
                            data-image="{{ $product->image }}" data-bs-toggle="modal" data-bs-target="#deletePicModal">
                            <i class="fas fa-trash"></i>
                        </button>
                        @else
                        <div class="no-image-placeholder d-flex align-items-center justify-content-center">
                            <i class="fas fa-image fa-4x text-muted"></i>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Product Details -->
                <div class="col-md-7">
                    <div class="detail-grid">
                        <div class="detail-card">
                            <i class="fas fa-hashtag text-primary"></i>
                            <div>
                                <span class="text-muted">Product ID</span>
                                <h5 class="mb-0">#{{ $product->id }}</h5>
                            </div>
                        </div>

                        <div class="detail-card">
                            <i class="fas fa-layer-group text-success"></i>
                            <div>
                                <span class="text-muted">Category</span>
                                <h5 class="mb-0">{{ $product->category->name }}</h5>
                            </div>
                        </div>

                        <div class="detail-card">
                            <i class="fas fa-info-circle text-info"></i>
                            <div>
                                <span class="text-muted">Description</span>
                                <p class="mb-0">{{ $product->description }}</p>
                            </div>
                        </div>

                        <!-- Pricing Section -->
                        <div class="pricing-card bg-gradient-warning text-white">
                            <div class="price-item">
                                <span>Cost Price</span>
                                <h3>${{ number_format($product->cost_price, 2) }}</h3>
                            </div>
                            <div class="price-item">
                                <span>Sale Price</span>
                                <h3>${{ number_format($product->price, 2) }}</h3>
                            </div>
                            <div class="discount-badge">
                                {{ $product->discount }}% OFF
                            </div>
                        </div>

                        <!-- Inventory Details -->
                        <div class="detail-card">
                            <i class="fas fa-cubes text-danger"></i>
                            <div>
                                <span class="text-muted">Stock</span>
                                <h5 class="mb-0">{{ $product->stock_quantity }} units</h5>
                            </div>
                        </div>

                        <!-- Additional Details -->
                        <div class="specs-grid">
                            <div class="spec-item">
                                <i class="fas fa-weight-hanging"></i>
                                <span>{{ number_format($product->weight,2) }}g</span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-expand"></i>
                                <span>{{ $product->dimensions }}</span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-barcode"></i>
                                <span>{{ $product->barcode }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Products
                </a>
                <div class="btn-group">
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash-alt me-2"></i>Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="reviews-section glass-morphism">
        <h4 class="section-title"><i class="fas fa-comments me-2"></i>Customer Reviews</h4>

        @if ($product->reviews->isEmpty())
        <div class="empty-state">
            <i class="fas fa-comment-slash"></i>
            <p>No reviews yet</p>
        </div>
        @else
        <div class="reviews-grid">
            @foreach($product->reviews as $review)
            <div class="review-card">
                <div class="user-info">
                    <div class="avatar">
                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h6>{{ $review->user->name }}</h6>
                        <div class="rating">
                            @for($i = 0; $i < 5; $i++) <i
                                class="fas fa-star{{ $i < $review->rating ? ' text-warning' : ' text-secondary' }}"></i>
                                @endfor
                        </div>
                    </div>
                </div>
                <p class="review-text">{{ $review->comment }}</p>
                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-icon">
                        <i class="fas fa-trash text-danger"></i>
                    </button>
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

@push('styles')
<style>
    /* Glass Morphism Effect */
    .glass-morphism {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Image Container */
    .image-container {
        position: relative;
        border-radius: 1rem;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .hover-scale:hover {
        transform: scale(1.02);
    }

    .btn-floating {
        position: absolute;
        bottom: 1rem;
        right: 1rem;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Detail Grid */
    .detail-grid {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }

    .detail-card {
        background: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: transform 0.3s ease;
    }

    .detail-card:hover {
        transform: translateY(-3px);
    }

    /* Pricing Card */
    .pricing-card {
        padding: 2rem;
        border-radius: 1rem;
        position: relative;
        overflow: hidden;
    }

    .discount-badge {
        position: absolute;
        top: -20px;
        right: -20px;
        background: #fff;
        color: #ff4757;
        padding: 2rem 3rem;
        transform: rotate(45deg);
        font-weight: bold;
    }

    /* Reviews Section */
    .reviews-grid {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    }

    .review-card {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        position: relative;
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #3498db;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    /* Animations */
    @keyframes float {
        0% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }

        100% {
            transform: translateY(0px);
        }
    }

    .floating-shapes .shape {
        position: absolute;
        opacity: 0.1;
    }

    .circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #fff;
        animation: float 3s infinite;
    }

    .triangle {
        width: 0;
        height: 0;
        border-left: 50px solid transparent;
        border-right: 50px solid transparent;
        border-bottom: 100px solid #fff;
        animation: float 4s infinite;
    }
</style>
@endpush

@push('scripts')
<script>
    // Add interactive animations
    document.querySelectorAll('.detail-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-5px)';
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
        });
    });
</script>
@endpush
