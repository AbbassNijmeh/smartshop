@extends('layouts.admin')
@section('body')
<nav class="breadcrumb-container" aria-label="breadcrumb">
    <ol class="breadcrumb"><button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Payments</li>
    </ol>
</nav>

<div class="container-fluid">


    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Payment Transactions</h5>
        </div>
        <div class="card-body">
            <table id="paymentTable" class="table table-striped table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Order ID</th>
                        <th>User Name</th>
                        <th>Payment Method</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $payment)
                    <tr>
                        <td>{{ $payment->id }}</td>
                        <td>
                            @if ($payment->order)
                                <a href="{{ route('orders.show', $payment->order->id) }}" class="text-decoration-none">
                                    {{ $payment->order->id }}
                                </a>
                            @else
                                <span>No Order</span>
                            @endif
                        </td>

                        <td><a href="{{ route('users.show', $payment->user->id) }}" class="text-decoration-none"> {{
                                $payment->user->name }}</a></td>
                        <td>{{ ucfirst($payment->payment_method) }}</td>
                        <td>${{ number_format($payment->total_amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Order ID</th>
                        <th>User Name</th>
                        <th>Payment Method</th>
                        <th>Total Amount</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function () {
        new DataTable('#paymentTable');

        $('#paymentTable').DataTable({
            responsive: true,
            "order": [[0, "desc"]],
        });
    });
</script>
@endpush
