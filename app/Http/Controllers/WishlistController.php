<?php

namespace App\Http\Controllers;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    // Add an item to the wishlist
    public function addToWishlist(Request $request)
    {
        // Validate that the product and user exist
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ]);

        // Check if the product is already in the user's wishlist
        $wishlistItem = Wishlist::where('user_id', $request->user_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($wishlistItem) {
            return response()->json(['message' => 'Product already in wishlist'], 200);
        }

        // Add the product to the wishlist
        $wishlistItem = Wishlist::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
        ]);

        return response()->json(['message' => 'Product added to wishlist', 'wishlist_item' => $wishlistItem], 201);
    }

    // Retrieve the user's wishlist
    public function getWishlist($user_id)
    {
        // Validate if the user exists
        $user = \App\Models\User::find($user_id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Retrieve the wishlist items for the given user
        $wishlistItems = Wishlist::where('user_id', $user_id)
            ->with('product') // Load the product details
            ->get();

        // Format each wishlist item to include only the specified fields
        $formattedWishlistItems = $wishlistItems->map(function ($wishlistItem) {
            $product = $wishlistItem->product;

            // Decode images field (stored as JSON array)
            $images = $product->images ? json_decode($product->images) : [];

            // Generate full URLs for each image path
            $imageUrls = array_map(function ($image) {
                return url("storage/{$image}");
            }, $images);

            return [
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'images' => $imageUrls,
            ];
        });

        return response()->json($formattedWishlistItems, 200);
    }


    // Remove an item from the wishlist
    public function removeFromWishlist(Request $request)
    {
        // Validate the request to ensure 'user_id' and 'product_id' are provided
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ]);

        // Find the wishlist item based on user_id and product_id
        $wishlistItem = Wishlist::where('user_id', $request->user_id)
            ->where('product_id', $request->product_id)
            ->first();

        if (!$wishlistItem) {
            return response()->json(['message' => 'Product not found in wishlist'], 404);
        }

        // Remove the item from the wishlist
        $wishlistItem->delete();

        return response()->json(['message' => 'Product removed from wishlist'], 200);
    }


}
