<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    // Fetch all products with category relationships
    public function index()
    {
        $products = Product::with('category')->get();

        return response()->json($products->map(function ($product) {
            // Decode the images field (which is stored as a JSON array)
            $images = $product->images ? json_decode($product->images) : [];

            // Generate full URLs for each image
            $imageUrls = array_map(function ($image) {
                return url("storage/{$image}"); // Assuming image is stored in 'uploads/product_images/{filename}'
            }, $images);

            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'stock' => $product->stock,
                'category_name' => $product->category->name ?? null,
                'images' => $imageUrls, // Return the array of full URLs for the images
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        }));
    }

    // Store a new product
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'category_id' => 'required|exists:categories,id',
                'images' => 'required|array|min:1|max:5', // Ensure between 1 and 5 images
                'images.*' => 'image|mimes:jpg,jpeg,png,gif', // Validate each image is an image file
            ]);

            // Store images in the custom 'uploads/product_images' directory
            $imagePaths = [];
            foreach ($validatedData['images'] as $image) {
                // Store each image in 'uploads/product_images' directory
                $imagePath = $image->store('uploads/product_images', 'public');
                $imagePaths[] = $imagePath; // Store the relative path
            }

            // Create the product
            $product = new Product();
            $product->name = $validatedData['name'];
            $product->description = $validatedData['description'];
            $product->price = $validatedData['price'];
            $product->stock = $validatedData['stock'];
            $product->category_id = $validatedData['category_id'];
            $product->images = json_encode($imagePaths); // Store image paths as a JSON array
            $product->save();

            return response()->json([
                'message' => 'Product created successfully',
                'product' => $product,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Log and handle other exceptions
            Log::error('Error saving product: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Show a single product by ID
    public function show($id)
    {
        // Retrieve the product along with its category (using eager loading)
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Decode the images field (which is stored as a JSON array)
        $images = $product->images ? json_decode($product->images) : [];

        // Generate full URLs for each image path in the 'images' field
        $imageUrls = array_map(function ($image) {
            return url("storage/{$image}"); // Generate the full URL for each image
        }, $images);

        // Return the product details along with the category name and image URLs
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
            'category_name' => $product->category->name ?? null,
            'images' => $imageUrls, // Return the array of full URLs for the images
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ]);
    }

    // Fetch products by category
    public function getByCategoryId($categoryId)
    {
        $products = Product::with('category')->where('category_id', $categoryId)->get();

        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found for this category'], 404);
        }

        return response()->json($products->map(function ($product) {
            // Decode the images field (which is stored as a JSON array)
            $images = $product->images ? json_decode($product->images) : [];

            // Generate full URLs for each image
            $imageUrls = array_map(function ($image) {
                return url("storage/{$image}"); // Assuming image is stored in 'uploads/product_images/{filename}'
            }, $images);

            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'stock' => $product->stock,
                'category_name' => $product->category->name ?? null,
                'images' => $imageUrls, // Return the array of full URLs for the images
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        }));
    }

    // Fetch the last N products
    public function getLastNProducts($num)
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->limit($num)->get();

        return response()->json($products->map(function ($product) {
            // Decode the images field (which is stored as a JSON array)
            $images = $product->images ? json_decode($product->images) : [];

            // Generate full URLs for each image
            $imageUrls = array_map(function ($image) {
                return url("storage/{$image}"); // Assuming image is stored in 'uploads/product_images/{filename}'
            }, $images);

            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'stock' => $product->stock,
                'category_name' => $product->category->name ?? null,
                'images' => $imageUrls, // Return the array of full URLs for the images
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        }));
    }

    // Fetch all products sorted by creation date
    public function getAllProductsSorted()
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->get();

        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found'], 404);
        }

        return response()->json($products->map(function ($product) {
            // Decode the images field (which is stored as a JSON array)
            $images = $product->images ? json_decode($product->images) : [];

            // Generate full URLs for each image
            $imageUrls = array_map(function ($image) {
                return url("storage/{$image}"); // Assuming image is stored in 'uploads/product_images/{filename}'
            }, $images);

            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'stock' => $product->stock,
                'category_name' => $product->category->name ?? null,
                'images' => $imageUrls, // Return the array of full URLs for the images
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        }));
    }
}
