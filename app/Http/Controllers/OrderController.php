<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function Adminindex()
    {
        $orders = Order::all();

        return view('admin.order.index', compact('orders'));
    }
    public function AdminShow($id)
    {
        $order = Order::with('payment', 'orderItems')->find($id);
        return view('admin.order.details', compact('order'));
    }
    function SendToDelivery($orderId)
    {
        //update the statius of the order from pending to chipping
        $order = Order::find($orderId);
        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Order not found.');
        }
        if ($order->status != 'pending') {
            return redirect()->route('orders.index')->with('error', 'Order is not pending.');
        }
        $order->status = 'shipping';
        $order->save();
        return redirect()->route('orders.index')->with('success', 'Order sent for delivery successfully.');
    }
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
    function userHistory()
    {
        $orders = Auth::user()->orders()->get();

        return view('site.orderHistory', compact('orders'));
    }
    public function userShow($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        // dd($order);
        return view('site.orderDetail', compact('order'));
    }

    public function monthlyData()
    {
        $startOfYear = now()->startOfYear();

        $monthlyData = DB::table('orders')
            ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
            ->where('created_at', '>=', $startOfYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month');

        // Prepare data for all months from Jan to current month
        $currentMonth = now()->month;
        $data = [];

        for ($i = 1; $i <= $currentMonth; $i++) {
            $data[] = round($monthlyData->get($i, 0), 2); // Round if needed
        }

        return response()->json($data);
    }
}
