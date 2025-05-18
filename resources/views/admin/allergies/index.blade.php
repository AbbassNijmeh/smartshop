@extends('layouts.admin')

@section('body')
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-3 rounded">
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Manage Allergies & Ingredients</li>
        </ol>
    </nav>

    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white text-center py-3">
            <h4 class="mb-0"><i class="fas fa-allergies"></i> Manage Allergies & Ingredients</h4>
        </div>

        <div class="card-body">
            <!-- Allergies Table -->
            <h5><strong>Allergies List</strong></h5>
            <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addAllergyModal">Add Allergy</button>

            <table class="table table-bordered responsive" id="allergiesTable">
                <thead class="table-dark">
                    <tr>
                        <th>Allergy</th>
                        <th>Caused by Ingredients</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allergies as $allergy)
                    <tr>
                        <td>{{ $allergy->name }}</td>
                        <td>{{ $allergy->ingredients->pluck('name')->join(', ') ?? 'N/A' }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-toggle="modal"
                                data-target="#editAllergyModal{{ $allergy->id }}">Edit</button>
                            <form action="{{ route('allergies.destroy', $allergy->id) }}" method="POST"
                                class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Allergy Modal -->
                    <div class="modal fade" id="editAllergyModal{{ $allergy->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Allergy</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal">&times;</button>
                                </div>
                                <form method="POST" action="{{ route('allergies.update', $allergy->id) }}">
                                    @csrf @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Allergy Name</label>
                                            <input type="text" class="form-control" name="name"
                                                value="{{ $allergy->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Caused by Ingredients</label>
                                            <select name="ingredients[]" class="form-control select2" multiple
                                                style="width: 100%;">
                                                @foreach($ingredients as $ingredient)
                                                <option value="{{ $ingredient->id }}" @if($allergy->
                                                    ingredients->contains($ingredient->id)) selected @endif>
                                                    {{ $ingredient->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>

            <!-- Ingredients Table -->
            <h5 class="mt-4"><strong>Ingredients List</strong></h5>
            <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addIngredientModal">Add
                Ingredient</button>

            <table class="table table-bordered" id="ingredientsTable">
                <thead class="table-dark">
                    <tr>
                        <th>Ingredient</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ingredients as $ingredient)
                    <tr>
                        <td>{{ $ingredient->name }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-toggle="modal"
                                data-target="#editIngredientModal{{ $ingredient->id }}">Edit</button>
                            <form action="{{ route('ingredients.destroy', $ingredient->id) }}" method="POST"
                                class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Ingredient Modal -->
                    <div class="modal fade" id="editIngredientModal{{ $ingredient->id }}" tabindex="-1"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Ingredient</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal">&times;</button>
                                </div>
                                <form method="POST" action="{{ route('ingredients.update', $ingredient->id) }}">
                                    @csrf @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Ingredient Name</label>
                                            <input type="text" class="form-control" name="name"
                                                value="{{ $ingredient->name }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Allergy Modal -->
<div class="modal fade" id="addAllergyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Allergy</h5>
                <button type="button" class="btn-close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="{{ route('allergies.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Allergy Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Caused by Ingredients</label>
                        <select name="ingredients[]" class="form-control select2" multiple style="width: 100%;">
                            @foreach($ingredients as $ingredient)
                            <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Ingredient Modal -->
<div class="modal fade" id="addIngredientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Ingredient</h5>
                <button type="button" class="btn-close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="{{ route('ingredients.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Ingredient Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    $(document).ready(function() {
        new DataTable('#allergiesTable');
        new DataTable('#ingredientsTable');
        $('.select2').select2();


    });
</script>
@endpush
