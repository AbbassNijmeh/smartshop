<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function Adminindex()
    {
        $payments = Payment::with('order', 'user')->get();
        return view('admin.payment.index', compact('payments'));
    }
    public function show()
    {
        $userId = Auth::id();
        $cartItems = Cart::where('user_id', $userId)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'Your cart is empty.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        $user = Auth::user(); // Get the currently authenticated user
        $addresses = $user->addresses; // Assuming there's a relationship in User model for addresses


        return view('site.checkout', compact('cartItems', 'total', 'addresses'));
    }





    public function process(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'delivery_option' => 'required|in:yes,no',
            'address_id' => 'nullable|exists:user_addresses,id',
            'phone' => 'nullable|string|max:20',
            'payment_method' => 'required|string|max:50',
        ];

        // Only validate location_type if no existing address is chosen
        if ($request->delivery_option === 'yes' && !$request->filled('address_id')) {
            $rules['location_type'] = 'required|in:current,custom,link';

            if ($request->location_type === 'current') {
                $rules['location_coordinates'] = 'required|string';
            } elseif ($request->location_type === 'custom') {
                $rules['country'] = 'required|string|max:255';
                $rules['city'] = 'required|string|max:255';
                $rules['street'] = 'required|string|max:255';
                $rules['building'] = 'required|string|max:255';
            } elseif ($request->location_type === 'link') {
                $rules['location_link'] = 'required|url';
            }
        }

        $request->validate($rules);


        // Update phone if changed
        if (!empty($request->phone) && $request->phone !== $user->phone) {
            $user->phone = $request->phone;
            $user->save();
        }

        $isDelivery = $request->delivery_option === 'yes';
        $addressId = null;

        // Handle delivery address
        if ($isDelivery) {
            if ($request->filled('address_id')) {
                $address = UserAddress::find($request->address_id);
            } elseif ($request->location_type === 'current' && $request->filled('location_coordinates')) {
                $coordinates = explode(',', $request->location_coordinates);
                $address = UserAddress::create([
                    'user_id' => $user->id,
                    'latitude' => $coordinates[0] ?? null,
                    'longtitude' => $coordinates[1] ?? null,
                ]);
            } elseif ($request->location_type === 'custom') {
                $address = UserAddress::create([
                    'user_id' => $user->id,
                    'country' => $request->country,
                    'city' => $request->city,
                    'street' => $request->street,
                    'building' => $request->building,
                ]);
            } elseif ($request->location_type === 'link') {
                $address = UserAddress::create([
                    'user_id' => $user->id,
                    'location_link' => $request->location_link,
                ]);
            }

            $addressId = $address->id;
            // dd($addressId);
        }

        // Validate cart
        $cartItems = Cart::where('user_id', $user->id)->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('home')->with('error', 'Your cart is empty.');
        }

        $totalPrice = 0;
        $orderItemsData = [];

        foreach ($cartItems as $cartItem) {
            $product = Product::find($cartItem->product_id);
            if (!$product) continue;

            $itemTotal = $product->price * $cartItem->quantity;
            $totalPrice += $itemTotal;

            $orderItemsData[] = [
                'product_id' => $product->id,
                'quantity' => $cartItem->quantity,
                'price' => $product->price,
            ];
        }

        // Create the order
        $order = Order::create([
            'user_id' => $user->id,
            'status' => $isDelivery ? 'pending' : 'ready',
            'total_price' => $totalPrice,
            'user_address_id' => $addressId,
        ]);

        foreach ($orderItemsData as $itemData) {
            OrderItem::create([
                'order_id' => $order->id,
                ...$itemData
            ]);

            $product = Product::find($itemData['product_id']);
            // app(RecommendationController::class)->logPurchase($product, Auth::id());
        }

        Payment::create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'payment_method' => $request->payment_method,
            'total_amount' => $totalPrice,
        ]);

        Cart::where('user_id', $user->id)->delete();

        return redirect()->route('home')->with('success', 'Order placed successfully!');
    }
}
