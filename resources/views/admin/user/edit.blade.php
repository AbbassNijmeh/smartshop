@extends('layouts.admin')

@section('body')
<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
    aria-label="breadcrumb">
    <ol class="breadcrumb">
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit User</li>
    </ol>
</nav>

<div class="container-fluid">
    <div class="card p-4">
        <h5 class="mb-3">Edit User: {{ $user->name }}</h5>
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- This is to indicate an update operation -->

            <div class="row">
                <!-- Name Field -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Email Field -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Password Field -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password (Leave empty to keep current)</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <small class="form-text text-muted">
                        Password must be at least 8 characters long and contain:
                        <ul>
                            <li>One uppercase letter</li>
                            <li>One lowercase letter</li>
                            <li>One number</li>
                            <li>One special character (optional)</li>
                        </ul>
                    </small>
                </div>

                <!-- Confirm Password Field -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation">
                    @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Role Field -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Role</label>
                    <select class="form-control @error('role') is-invalid @enderror" name="role" required>
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Allergies Field (Select2) -->
                <div class="col-md-6 mb-3">
                    <label for="allergies" class="form-label">Select Allergies</label>
                    <select class="form-control select2" id="allergies" name="allergies[]" multiple>
                        @foreach($allergies as $allergy)
                            <option value="{{ $allergy->id }}" {{ in_array($allergy->id, $user->allergies->pluck('id')->toArray()) ? 'selected' : '' }}>
                                {{ $allergy->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('allergies') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

            </div>

            <button type="submit" class="btn btn-success">Update User</button>
        </form>
    </div>
</div>

@endsection

@push('script')
<script>
    $(document).ready(function() {
        // Initialize Select2 for allergies
        $('.select2').select2({
            placeholder: "Select allergies",
            allowClear: true
        });
    });
</script>
@endpush
