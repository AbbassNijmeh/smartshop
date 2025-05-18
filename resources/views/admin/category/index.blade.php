@extends('layouts.admin')
@section('body')
<nav style="--breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Categories</li>
    </ol>
</nav>

<div class="container-fluid">
    <!-- Add Category Button -->
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addCategoryModal">
        + Add Category
    </button>

    <table id="example" class="display responsive bcategory text-center">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th>Total Products</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category )
            <tr class="text-center">
                <td>{{$category->id}}</td>
                <td>{{$category->name}}</td>
                <td>{{$category->products_count}}</td>
                <td>{{$category->created_at->format('d M Y')}}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th>Total products</th>
                <th>Created At</th>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="category_name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    new DataTable('#example');
</script>
@endpush
