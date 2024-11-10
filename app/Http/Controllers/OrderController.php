<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Create a new order
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric',
        ]);

        // Create the order
        $order = Order::create([
            'user_id' => $request->user_id,
            'status' => 'pending',  // Default status when placing an order
            'total_amount' => $request->total_amount,
        ]);

        return response()->json(['message' => 'Order placed successfully', 'order' => $order], 201);
    }

    public function show($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order);
    }

    public function checkout(Request $request, $userId)
    {
        // Validate that the user exists
        $userExists = User::find($userId);
        if (!$userExists) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Retrieve the pending order for the user
        $order = Order::where('user_id', $userId)
            ->where('status', 'pending')
            ->first();

        if (!$order) {
            return response()->json(['message' => 'No pending cart found'], 404);
        }

        // Validate the total amount
        $validatedData = $request->validate([
            'total_amount' => 'required|numeric|min:0', // Ensure total amount is provided and is a positive number
        ]);

        // Update the order status to 'completed' and save the total amount
        $order->status = 'completed';
        $order->total_amount = $validatedData['total_amount'];

        // Save the order
        $order->save();

        return response()->json([
            'message' => 'Order placed successfully',
            //'order' => $order->load('orderItems.product'), // Use 'orderItems' instead of 'items'
        ]);
    }


    public function getUserOrders($userId)
    {
        $orders = Order::where('user_id', $userId)->get();

        if ($orders->isEmpty()) {
            return response()->json(['message' => 'No orders found for this user'], 404);
        }

        return response()->json($orders);
    }
    public function cancelOrder($id, Request $request)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Only allow cancellation if the order is not yet shipped or completed
        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Order cannot be canceled at this stage'], 400);
        }

        // Update the order status to 'canceled'
        $order->status = 'canceled';
        $order->save();

        return response()->json(['message' => 'Order canceled successfully', 'order' => $order]);
    }

}
