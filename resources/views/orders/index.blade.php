@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title">My Orders</h4>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Order History</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Event</th>
                                            <th>Quantity</th>
                                            <th>Total Price</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders ?? [] as $order)
                                            <tr>
                                                <td>{{ $order->order_number }}</td>
                                                <td>{{ $order->event->title }}</td>
                                                <td>{{ $order->items->sum('quantity') }}</td>
                                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $order->status === 'paid' ? 'success' : ($order->status === 'pending' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <a href="{{ route('orders.show', $order) }}"
                                                        class="btn btn-primary btn-xs">View</a>
                                                    @if ($order->isPending())
                                                        <form method="POST"
                                                            action="{{ route('payments.process', $order) }}"
                                                            style="display: inline;">
                                                            @csrf
                                                            <input type="hidden" name="payment_method" value="dummy">
                                                            <input type="hidden" name="accept_terms" value="1">
                                                            <button type="submit" class="btn btn-success btn-xs">Pay
                                                                Now</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">No orders yet</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            {{ $orders->links() ?? '' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
