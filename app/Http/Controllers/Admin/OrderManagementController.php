<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderManagementController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'event'])->orderBy('payment_date', 'desc')->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($transaction_id)
    {
        $order = Order::with(['user', 'event', 'tickets.ticketType'])->findOrFail($transaction_id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $transaction_id)
    {
        $order = Order::findOrFail($transaction_id);
        
        $request->validate([
            'status' => 'required|in:Pending,Verified,Cancelled',
        ]);

        $order->update(['payment_status' => $request->status]);

        return back()->with('success', 'Order status updated successfully.');
    }
}
