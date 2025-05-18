<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    function index()
    {
        $wishlistItems = Auth::user()->wishlist()->with('product')->get();
        return view('site.wishlist', compact('wishlistItems'));
    }
    public function store(Request $request)
    {
        $user = Auth::user();
        $productId = $request->input('id');

        $alreadyInWishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->exists();

        if (!$alreadyInWishlist) {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product added to wishlist.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product is already in your wishlist.',
        ]);
    }


    public function moveToCart($id)
    {
        $wishlistItem = Wishlist::findOrFail($id);
        $request = new \Illuminate\Http\Request([
            'product_id' => $wishlistItem->product_id,
            'quantity' => 1,
        ]);

        $response = app()->call('App\Http\Controllers\CartController@addToCart', ['request' => $request]);

        if ($response->getStatusCode() === 200) {
            $wishlistItem->delete();
            return redirect()->route('wishlist.index')->with('success', 'Item moved to cart.');
        }

        $content = json_decode($response->getContent(), true);
        return redirect()->route('wishlist.index')->with('error', $content['error'] ?? 'Something went wrong.');
    }

    public function destroy($id)
    {
        Wishlist::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Item removed from wishlist.');
    }
}
