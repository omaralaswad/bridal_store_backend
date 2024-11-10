<?php

namespace App\Http\Controllers;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        // Validate the request data
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|string|in:Stripe,PayPal',
            'amount' => 'required|numeric'
        ]);

        // Retrieve the order
        $order = Order::find($request->order_id);

        // Here you would integrate with a third-party payment gateway (e.g., Stripe or PayPal)
        // For now, we'll simulate payment processing
        $paymentSuccessful = true;  // Simulate a successful payment

        if ($paymentSuccessful) {
            // Record the payment in the payments table
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'amount' => $request->amount,
                'payment_date' => Carbon::now(),
            ]);

            // Update the order status to 'completed' or 'paid' based on your business logic
            $order->status = 'completed';
            $order->save();

            return response()->json(['message' => 'Payment processed successfully', 'payment' => $payment], 201);
        }

        return response()->json(['message' => 'Payment failed'], 400);
    }

    public function getPaymentStatus($paymentId)
    {
        // Retrieve the payment by ID
        $payment = Payment::find($paymentId);

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Assuming that a payment being recorded in the database means it was successful
        return response()->json([
            'payment_id' => $payment->id,
            'order_id' => $payment->order_id,
            'payment_method' => $payment->payment_method,
            'amount' => $payment->amount,
            'status' => 'successful', // Simulated status (you can customize this)
            'payment_date' => $payment->payment_date,
        ]);
    }
}
