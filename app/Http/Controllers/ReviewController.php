<?php

namespace App\Http\Controllers;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Submit a product review
    public function submitReview(Request $request)
    {
        // Validate the request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5', // Ensure rating is between 1 and 5
            'comment' => 'required|string',
        ]);

        // Check if the user has already reviewed the product (optional)
        $existingReview = Review::where('user_id', $request->user_id)
            ->where('product_id', $request->product_id)
            ->first();
        if ($existingReview) {
            return response()->json(['message' => 'You have already reviewed this product'], 409);
        }

        // Create a new review
        $review = Review::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json(['message' => 'Review submitted successfully', 'review' => $review], 201);
    }

    // Get reviews for a specific product
    public function getReviews($product_id)
    {
        // Validate if the product exists
        $product = Product::find($product_id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Get all reviews for the product
        $reviews = Review::where('product_id', $product_id)
            ->with('user') // Load user details (optional)
            ->get();

        return response()->json($reviews, 200);
    }
}
