<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Add item to cart and order items
    public function addToCart(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        // Check if there's an existing order for the user
        $order = Order::where('user_id', $request->user_id)
            ->where('status', 'pending')  // Only consider pending orders
            ->first();

        // If no pending order exists, create a new one
        if (!$order) {
            $order = Order::create([
                'user_id' => $request->user_id,
                'status' => 'pending',  // Default status for new orders
                'total_amount' => 0.00, // Initial amount (will be updated later)
            ]);
        }

        // Check if the item is already in the cart
        $cartItem = Cart::where('user_id', $request->user_id)
            ->where('product_id', $request->product_id)
            ->first();

        // Retrieve product price
        $product = Product::find($request->product_id);

        if ($cartItem) {
            // If item is already in the cart, update quantity
            $cartItem->quantity += $request->quantity;
            $cartItem->save();

            // Update or create the order item
            $orderItem = OrderItem::where('order_id', $order->id)
                ->where('product_id', $request->product_id)
                ->first();

            if ($orderItem) {
                $orderItem->quantity += $request->quantity;
                $orderItem->save();
            } else {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'price' => $product->price,
                ]);
            }
        } else {
            // Add a new item to the cart
            $cartItem = Cart::create([
                'user_id' => $request->user_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);

            // Create order item
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $product->price,
            ]);
        }

        // Update the total amount of the order
        $order->total_amount += $product->price * $request->quantity;
        $order->save();

        return response()->json(['message' => 'Item added to cart and order', 'cart' => $cartItem], 201);
    }




    // Remove item from cart and order items
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id'
        ]);

        // Find the item in the cart
        $cartItem = Cart::where('user_id', $request->user_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            // Remove the item from the cart
            $cartItem->delete();

            // Find the order associated with the user
            $order = Order::where('user_id', $request->user_id)
                ->where('status', 'pending')
                ->first();

            if ($order) {
                // Remove the item from the order items
                $orderItem = OrderItem::where('order_id', $order->id)
                    ->where('product_id', $request->product_id)
                    ->first();

                if ($orderItem) {
                    $orderItem->delete();
                    // Update the total amount (optional)
                    $order->total_amount -= $orderItem->price * $orderItem->quantity;
                    $order->save();
                }
            }

            return response()->json(['message' => 'Item removed from cart'], 200);
        }

        return response()->json(['message' => 'Item not found in cart'], 404);
    }

    // Update item quantity in cart and order items
    public function updateCart(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        // Find the item in the cart
        $cartItem = Cart::where('user_id', $request->user_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            // Update the quantity in the cart
            $cartItem->quantity = $request->quantity;
            $cartItem->save();

            // Find the associated order
            $order = Order::where('user_id', $request->user_id)
                ->where('status', 'pending')
                ->first();

            if ($order) {
                // Update or create the order item
                $orderItem = OrderItem::where('order_id', $order->id)
                    ->where('product_id', $request->product_id)
                    ->first();

                if ($orderItem) {
                    // Update the quantity in the order item
                    $orderItem->quantity = $request->quantity;
                    $orderItem->save();
                }
            }

            return response()->json(['message' => 'Cart updated successfully', 'cart' => $cartItem], 200);
        }

        return response()->json(['message' => 'Item not found in cart'], 404);
    }

    // Get all items in user's cart
    public function getCart($user_id)
    {
        // Retrieve the pending order for the user
        $order = Order::where('user_id', $user_id)
            ->where('status', 'pending')
            ->with('orderItems.product.category')
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Cart is empty'], 404);
        }

        // Format the response data
        $cartItems = $order->orderItems->map(function ($orderItem) {
            // Decode the images field (stored as a JSON array)
            $images = $orderItem->product->images ? json_decode($orderItem->product->images) : [];

            // Generate full URLs for each image path
            $imageUrls = array_map(function ($image) {
                return url("storage/{$image}");
            }, $images);

            return [
                'name' => $orderItem->product->name,
                'description' => $orderItem->product->description,
                'price' => $orderItem->price,
                'item_total' => $orderItem->price * $orderItem->quantity,  // Rename to 'item_total'
                'category_name' => $orderItem->product->category->name ?? null,
                'images' => $imageUrls,
                'quantity' => $orderItem->quantity,
            ];
        });

        return response()->json([
            'order_id' => $order->id,
            'total_amount' => $order->total_amount,  // Total for the entire order
            'cart_items' => $cartItems,
        ]);
    }


}
