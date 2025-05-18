<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Dotenv\Exception\ValidationException;

class ReviewController extends Controller
{
    public function store(Request $request, $id)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json(['error' => 'You must be logged in to submit a review.'], 401);
            }

            // Validate request
            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|max:1000',
            ]);

            // Find product
            $product = Product::findOrFail($id);

            // Create review
            $review = $product->reviews()->create([
                'user_id' => Auth::id(),
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]);

            // Manually update review count
            $product->reviews_count = $product->review_count + 1;
            $product->rating = $product->reviews()->avg('rating');

            $product->save();

            return response()->json([
                'success' => 'Review submitted successfully.',
                'review' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }



    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('reviews.index')->with('success', 'Review deleted successfully.');
    }
}
