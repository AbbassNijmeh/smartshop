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
            <li class="breadcrumb-item active" aria-current="page">Edit Product #{{ $product->id }}</li>
        </ol>
    </nav>

    <!-- Product Edit Card -->
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-warning text-white text-center py-3">
            <h4 class="mb-0"><i class="fas fa-edit"></i> Edit {{ $product->name }}</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Product Image -->
                    <div class="text-center mb-3">
                        <img src="{{ asset( $product->image)  }}"
                            class="img-fluid rounded shadow-sm" alt="Product Image" style="max-height: 250px;">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- Product Name -->
                        <div class="form-group">
                            <label for="name">Product Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                        </div>

                        <!-- Category -->
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select name="category_id" class="form-control" required>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ?
                                    'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Brand -->
                        <div class="form-group">
                            <label for="brand">Brand</label>
                            <input type="text" name="brand" class="form-control" value="{{ $product->brand }}">
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control"
                                rows="3">{{ $product->description }}</textarea>
                        </div>

                        <!-- Cost Price -->
                        <div class="form-group">
                            <label for="cost_price">Cost Price ($)</label>
                            <input type="number" step="0.01" name="cost_price" class="form-control"
                                value="{{ $product->cost_price }}" required>
                        </div>

                        <!-- Sale Price -->
                        <div class="form-group">
                            <label for="price">Sale Price ($)</label>
                            <input type="number" step="0.01" name="price" class="form-control"
                                value="{{ $product->price }}" required>
                        </div>

                        <!-- Stock Quantity -->
                        <div class="form-group">
                            <label for="stock_quantity">Stock Quantity</label>
                            <input type="number" name="stock_quantity" class="form-control"
                                value="{{ $product->stock_quantity }}" required>
                        </div>

                        <!-- Barcode -->
                        <div class="form-group">
                            <label for="barcode">Barcode</label>
                            <input type="text" name="barcode" class="form-control" value="{{ $product->barcode }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Discount -->
                        <div class="form-group">
                            <label for="discount">Discount (%)</label>
                            <input type="number" step="0.01" name="discount" class="form-control"
                                value="{{ $product->discount }}">
                        </div>

                        <!-- Discount Start -->
                        <div class="form-group">
                            <label for="discount_start">Discount Start</label>
                            <input type="date" name="discount_start" class="form-control"
                                value="{{ $product->discount_start }}">
                        </div>

                        <!-- Discount End -->
                        <div class="form-group">
                            <label for="discount_end">Discount End</label>
                            <input type="date" name="discount_end" class="form-control"
                                value="{{ $product->discount_end }}">
                        </div>

                        <!-- Expiration Date -->
                        <div class="form-group">
                            <label for="expiration_date">Expiration Date</label>
                            <input type="date" name="expiration_date" class="form-control"
                                value="{{ $product->expiration_date }}">
                        </div>

                        <!-- Weight -->
                        <div class="form-group">
                            <label for="weight">Weight (kg)</label>
                            <input type="text" name="weight" class="form-control" value="{{ $product->weight }}">
                        </div>

                        <!-- Dimensions -->
                        <div class="form-group">
                            <label for="dimensions">Dimensions</label>
                            <input type="text" name="dimensions" class="form-control"
                                value="{{ $product->dimensions }}">
                        </div>

                        <!-- Location -->
                        <div class="form-group">
                            <label for="aisle">Aisle</label>
                            <input type="text" name="aisle" class="form-control" value="{{ $product->aisle }}">
                        </div>
                        <div class="form-group">
                            <label for="section">Section</label>
                            <input type="text" name="section" class="form-control" value="{{ $product->section }}">
                        </div>
                        <div class="form-group">
                            <label for="floor">Floor</label>
                            <input type="text" name="floor" class="form-control" value="{{ $product->floor }}">
                        </div>

                        <!-- Ingredients (Tags) -->
                        <div class="form-group">
                            <label for="ingredients">Ingredients</label>
                            <select name="ingredients[]" class="form-control select2-tags" multiple="multiple">
                                @foreach($ingredients as $ingredient)
                                <option value="{{ $ingredient->name }}" @if($product->ingredients->contains('name',
                                    $ingredient->name)) selected @endif>
                                    {{ $ingredient->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>


                        <!-- Image Upload -->
                        <div class="form-group">
                            <label for="image">Product Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    $(document).ready(function () {
        $('.select2-tags').select2({
            tags: true,
            tokenSeparators: [','],
            placeholder: "Enter or select ingredients",
            allowClear: true
        });
    });
</script>

@endpush
