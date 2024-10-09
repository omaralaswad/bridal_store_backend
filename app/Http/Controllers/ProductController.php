<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Import Log facade

class ProductController extends Controller
{
    // Fetch all products with category relationships
    public function index()
    {
        // Eager load the category relationships
        $products = Product::with(['category'])->get();

        return $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'stock' => $product->stock,
                'category_name' => $product->category->name ?? null,
                'image' => $product->image ? url($product->image) : null,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        });
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
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // Create a new Product instance
            $product = new Product();
            $product->name = $validatedData['name'];
            $product->description = $validatedData['description'];
            $product->price = $validatedData['price'];
            $product->stock = $validatedData['stock'];
            $product->category_id = $validatedData['category_id'];

            // Handle the image file upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads'), $imageName);

                // Save the image path relative to the public directory
                $product->image = 'uploads/' . $imageName;
                Log::info('Image uploaded:', [$product->image]); // Log the image path
            } else {
                Log::info('No image uploaded');
                $product->image = null; // Set to null if no image is provided
            }

            // Save the product to the database
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
            // Handle other exceptions
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Show a single product by ID
    public function show($id)
    {
        $product = Product::with(['category'])->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
            'category_name' => $product->category->name ?? null,
            'image' => $product->image ? url($product->image) : null,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ]);
    }

    // Update a product by ID
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Validate only the fields that are sent in the request
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'price' => 'sometimes|numeric',
            'stock' => 'sometimes|integer',
            'category_id' => 'sometimes|exists:categories,id',
            'image' => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update only the fields that are provided
        if ($request->has('name')) {
            $product->name = $validatedData['name'];
        }
        if ($request->has('description')) {
            $product->description = $validatedData['description'];
        }
        if ($request->has('price')) {
            $product->price = $validatedData['price'];
        }
        if ($request->has('stock')) {
            $product->stock = $validatedData['stock'];
        }
        if ($request->has('category_id')) {
            $product->category_id = $validatedData['category_id'];
        }

        // Handle image update
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
            $product->image = 'uploads/' . $imageName;
        }

        // Save the updated product
        $product->save();

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ], 200);
    }

    // Delete a product by ID
    public function delete($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(null, 204);
    }

    // Fetch products by category
    public function getByCategoryId($categoryId)
    {
        $products = Product::where('category_id', $categoryId)->get();

        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found for this category'], 404);
        }

        return response()->json($products);
    }

    // Fetch the last 20 products
    public function getLastNProducts($num)
    {
        // Fetch the last $num products, ordered by created_at in descending order
        $products = Product::orderBy('created_at', 'desc')->limit($num)->get();

        return response()->json($products);
    }


    // Fetch all products sorted by creation date
    public function getAllProductsSorted()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return response()->json($products);
    }
}
