<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role != 'delivery') {
            return redirect()->route('login');
        }
        $orders = Order::with('orderItems')->where('status', '=', 'shipping')->get();
        return view('delivery.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function updateStatus($orderID)
    {
        $order = Order::find($orderID);
        if (!$order) {
            return redirect()->route('delivery.index')->with('error', 'Order not found.');
        }
        $order->status = 'delivered';
        $order->save();
        return redirect()->route('delivery.index')->with('success', 'Order status updated successfully.');
    }
}
