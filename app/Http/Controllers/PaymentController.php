<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\User;

class PaymentController extends Controller
{
    // Create a new payment
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id', // Ensure user exists
            'booking_id' => 'required|exists:bookings,id', // Ensure booking exists
            'amount' => 'required|numeric|min:0', // Ensure valid amount
            'status' => 'required|in:pending,completed,failed', // Ensure valid status
            'payment_date' => 'required|date', // Ensure valid payment date
        ]);

        // Check if validation passed
        if ($validated) {
            // Create the payment
            $payment = Payment::create([
                'user_id' => $request->user_id,
                'booking_id' => $request->booking_id,
                'amount' => $request->amount,
                'status' => $request->status,
                'payment_date' => $request->payment_date,
            ]);

            // Return success response
            return response()->json([
                'message' => 'Payment created successfully',
                'data' => $payment,
            ], 201);
        }
    }




    // Get all payments
    public function index()
    {
        $payments = Payment::with(['user', 'booking'])->get();
        return response()->json($payments);
    }

    // Get a single payment by ID
    public function show($id)
    {
        $payment = Payment::with(['user', 'booking'])->find($id);
        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }
        return response()->json($payment);
    }

    // Update payment status
    public function update(Request $request, $id)
    {
        $payment = Payment::find($id);
        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        $request->validate([
            'status' => 'required|in:pending,completed,failed',
        ]);

        $payment->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Payment status updated successfully',
            'data' => $payment
        ]);
    }

    // Delete a payment
    public function destroy($id)
    {
        $payment = Payment::find($id);
        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        $payment->delete();
        return response()->json(['message' => 'Payment deleted successfully']);
    }
}
