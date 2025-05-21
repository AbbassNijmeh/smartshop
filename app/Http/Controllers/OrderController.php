<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function Adminindex()
    {
        $orders = Order::all();
        $deliveryUsers = User::where('role', '=', 'delivery')->get();
        // dd($deliveryUsers);
        return view('admin.order.index', compact('orders', 'deliveryUsers'));
    }
    public function AdminShow($id)
    {
        $order = Order::with('payment', 'orderItems')->find($id);
        return view('admin.order.details', compact('order'));
    }
    function SendToDelivery(Request $request)
    {
        $validated = $request->validate([
            'delivery_guy_id' => 'required|exists:users,id',
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::find($validated['order_id']);

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Order not found.');
        }

        if ($order->status != 'pending') {
            return redirect()->route('orders.index')->with('error', 'Order is not pending.');
        }

        $order->update([
            'status' => 'shipping',
            'delivery_id' => $validated['delivery_guy_id']
        ]);

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
        $order = Auth::user()->orders()->with('orderItems.product')->findOrFail($id);
        // dd($order);
        return view('site.orderDetail', compact('order'));
    }

    public function monthlyData(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($validated['start_date'])->startOfDay();
        $endDate = Carbon::parse($validated['end_date'])->endOfDay();

        // Fetch orders within the date range
        $orders = Order::with(['orderItems.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Initialize arrays for metrics using year-month keys
        $monthlySales = [];
        $monthlyProfits = [];

        foreach ($orders as $order) {
            $monthKey = $order->created_at->format('Y-m');

            $monthlySales[$monthKey] = ($monthlySales[$monthKey] ?? 0) + $order->total_price;
            $monthlyProfits[$monthKey] = ($monthlyProfits[$monthKey] ?? 0) + $order->getProfit();
        }

        // Generate all months in the range
        $currentMonth = $startDate->copy()->startOfMonth();
        $endMonth = $endDate->copy()->startOfMonth();
        $monthsInRange = [];

        while ($currentMonth <= $endMonth) {
            $monthsInRange[] = $currentMonth->copy();
            $currentMonth->addMonth();
        }

        // Prepare response data
        $salesData = [];
        $profitData = [];
        $monthLabels = [];
        $showYear = $startDate->year !== $endDate->year;

        foreach ($monthsInRange as $month) {
            $key = $month->format('Y-m');

            $salesData[] = round($monthlySales[$key] ?? 0, 2);
            $profitData[] = round($monthlyProfits[$key] ?? 0, 2);
            $monthLabels[] = $this->formatMonthLabel($month, $showYear);
        }

        return response()->json([
            'sales' => $salesData,
            'profits' => $profitData,
            'months' => $monthLabels
        ]);
    }

    private function formatMonthLabel(Carbon $date, bool $showYear = false)
    {
        return $date->format('M') . ($showYear ? " '" . $date->format('y') : '');
    }

    public function topProducts()
    {
        $products = Product::select('products.name')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->selectRaw('products.name, SUM(order_items.quantity) as total_sold')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        return response()->json([
            'labels' => $products->pluck('name'),
            'data' => $products->pluck('total_sold')
        ]);
    }
    // In your DashboardController or AnalyticsController
    public function categorySales()
    {
        $salesData = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as category, SUM(order_items.quantity * order_items.price) as total_sales')
            ->groupBy('categories.name')
            ->orderByDesc('total_sales')
            ->get();
        // echo $salesData;
        return response()->json([
            'labels' => $salesData->pluck('category'),
            'data' => $salesData->pluck('total_sales'),
            'colors' => ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'] // Bootstrap theme colors
        ]);
    }
}
