<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Recombee\RecommApi\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\RecommendationService;
use App\Http\Middleware\AdminMiddleware;
use Recombee\RecommApi\Requests\AddDetailView;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showWelcome()
    {
        // app(RecommendationController::class)->syncAllProducts();

        $products = Product::select('products.*')
            ->selectSub(function ($query) {
                $query->from('order_items')
                    ->selectRaw('SUM(quantity)')
                    ->whereColumn('product_id', 'products.id');
            }, 'total_sold')
            ->selectSub(function ($query) {
                $query->from('reviews')
                    ->selectRaw('AVG(rating)')
                    ->whereColumn('product_id', 'products.id');
            }, 'avg_rating')
            ->where('stock_quantity', '>', 0)
            ->orderByDesc('avg_rating')
            ->orderByDesc('total_sold')
            ->get();
        $categories = Category::all();
        return view('welcome', compact('products', 'categories'));
    }

    public function showDashboard()
    {
        $orders = Order::whereYear('created_at', Carbon::now()->year)->get();
        $totalProfit = $orders->sum(function ($order) {
            return $order->getProfit();
        });
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $orders = Order::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->get();
        $monthlyProfit = $orders->sum(function ($order) {
            return $order->getProfit();
        });
        $totalOrders = Order::all()->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        return view('admin.index', compact('totalProfit', 'monthlyProfit', 'totalOrders', 'pendingOrders'));
    }



    public function Products()
    {

        $categories = Category::all();
        $products = Product::with('category')
            ->where('stock_quantity', '>', 0)  // Exclude products with 0 stock_quantity
            ->paginate(9);

        return view('site.products', compact('products', 'categories'));
    }

    public function showFilteredProducts(Request $request)
    {
        $categories = Category::all();
        $query = Product::query()->where('stock_quantity', '>', 0);

        // Search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Price range filter
        $this->applyPriceFilter($query, $request);

        // Discount filter
        $this->applyDiscountFilter($query, $request);

        // Pagination with query string preservation
        $filteredProducts = $query->paginate(9)->withQueryString();

        return view('site.filteredProducts', compact('filteredProducts', 'categories'));
    }

    protected function applyPriceFilter($query, $request)
    {
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('price', [
                $request->min_price,
                $request->max_price
            ]);
        } elseif ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        } elseif ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
    }

    protected function applyDiscountFilter($query, $request)
    {
        if (!$request->has('discount')) return;

        if ($request->discount == 1) {
            // Active discounts
            $query->where('discount', '>', 0)
                ->where(function ($q) {
                    $q->where('discount_start', '<=', now())
                        ->where(function ($q) {
                            $q->where('discount_end', '>=', now())
                                ->orWhereNull('discount_end');
                        });
                });
        } else {
            // No active discounts
            $query->where(function ($q) {
                $q->where('discount', 0)
                    ->orWhere('discount_start', '>', now())
                    ->orWhere('discount_end', '<', now())
                    ->orWhere(function ($q) {
                        $q->whereNull('discount_end')
                            ->where('discount_start', '>', now());
                    });
            });
        }
    }

    public function showProduct($id)
    {
        $product = Product::where('id', $id)
            ->where('stock_quantity', '>', 0)
            ->with(['ingredients', 'reviews'])  // Exclude products with 0 stock_quantity
            ->firstOrFail();  // Ensure it returns a 404 if the product is not found
        $user = Auth::user();
        $allergicIngredients = [];

        if ($user) {
            $userAllergyIngredients = $user->allergies()->with('ingredients')->get()
                ->pluck('ingredients')
                ->flatten()
                ->pluck('id')
                ->unique();

            $productIngredientIds = $product->ingredients->pluck('id');

            $allergicIngredients = $product->ingredients
                ->whereIn('id', $userAllergyIngredients);
        }
        // app(RecommendationController::class)->logView($product, Auth::id());

        $total = $product->orderItems->sum('quantity');
        return view('site.singleProduct', compact('product', 'total', 'allergicIngredients'));
    }
    function showUserProfile()
    {
        $user = Auth::user();
        return view('site.profile', compact('user'));
    }
    public function userProfileUpdate(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255,',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
