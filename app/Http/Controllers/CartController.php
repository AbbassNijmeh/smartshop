<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);
        $userId = Auth::id();
        $productId = $request->product_id;
        $quantity = $request->quantity;

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($productId);

            if ($quantity > $product->stock_quantity) { // Assuming 'stock_quantity' column exists
                return response()->json(['error' => 'Requested quantity exceeds available stock.'], 400);
            }

            $cartItem = Cart::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $quantity;

                if ($newQuantity > $product->stock_quantity) {
                    return response()->json(['error' => 'Total quantity exceeds available stock.'], 400);
                }

                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                Cart::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
            }

            $totalItems = Cart::where('user_id', $userId)->sum('quantity');

            DB::commit();

            return response()->json([
                'success' => 'Product added to cart successfully!',
                'totalItems' => $totalItems
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred while adding the product to the cart. Please try again.'], 500);
        }
    }
    function ShowCart()
    {
        $userId = Auth::id();
        $cartItems = Cart::where('user_id', $userId)
            ->with('product')
            ->get();
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        return view('site.cart', compact('cartItems', 'totalPrice'));
    }

    public function remove($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to modify your cart.');
        }

        $userId = Auth::id();
        $cartItem = Cart::where('id', $id)->where('user_id', $userId)->first();

        if (!$cartItem) {
            return redirect()->route('cart.show')->with('error', 'Cart item not found.');
        }

        $cartItem->delete();

        return redirect()->route('cart.show')->with('success', 'Item removed from cart successfully.');
    }
}
