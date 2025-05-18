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
        <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
    </ol>
</nav>

<div class="container-fluid">
    <div class="card p-4 shadow-sm">
        <!-- User Information -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-3">{{ $user->name }}</h4>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                <p><strong>Joined On:</strong> {{ $user->created_at->format('d M Y') }}</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p><strong>Last Updated:</strong> {{ $user->updated_at->format('d M Y') }}</p>
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit User
                </a>
            </div>
        </div>

        <!-- Allergy Information -->
        <div class="mb-4">
            <h5>Allergy Information</h5>
            @if($user->allergies->isEmpty())
            <p>No allergies found for this user.</p>
            @else
            <ul class="list-group list-group-flush">
                @foreach($user->allergies as $allergy)
                <li class="list-group-item">{{ $allergy->name }}</li>
                @endforeach
            </ul>
            @endif
        </div>

        <!-- Order Summary -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Order Summary</h5>
                <p><strong>Total Orders:</strong> {{ $user->orders->count() }}</p>
                <p><strong>Total Amount Paid:</strong> ${{ number_format($user->orders->sum('total_price'), 2) }}</p>
            </div>
        </div>

        <!-- Order History DataTable -->
        <h5 class="mb-3">Order History</h5>
        @if($user->orders->isEmpty())
        <p>No orders placed by this user.</p>
        @else
        <div class="table-responsive">
            <table id="ordersTable" class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Total Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td>${{ number_format($order->total_price, 2) }}</td>

                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection

@push('script')
<script>
    new DataTable('#ordersTable');
    $(document).ready(function() {
        $('#ordersTable').DataTable({
            "responsive": true,
            "order": [[0, 'desc']],
            "pageLength": 10,
        });
    });
</script>
@endpush
