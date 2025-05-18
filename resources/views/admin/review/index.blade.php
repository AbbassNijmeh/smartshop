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
            <li class="breadcrumb-item active" aria-current="page">Allergies & Ingredients</li>
        </ol>
    </nav>

    <!-- Allergies Section -->
    <div class="card shadow-lg border-0 rounded-3 mb-4">
        <div class="card-header bg-danger text-white text-center py-3">
            <h4 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Allergies</h4>
        </div>
        <div class="card-body">
            <ul class="list-group">
                @foreach($allergies as $allergy)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $allergy->name }}
                        <form action="{{ route('allergies.destroy', $allergy->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Ingredients Section -->
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-success text-white text-center py-3">
            <h4 class="mb-0"><i class="fas fa-seedling"></i> Ingredients</h4>
        </div>
        <div class="card-body">
            <ul class="list-group">
                @foreach($ingredients as $ingredient)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $ingredient->name }}
                        <form action="{{ route('ingredients.destroy', $ingredient->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
