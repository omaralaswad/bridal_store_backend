<?php

namespace App\Http\Controllers;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // Create a new booking
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id', // Ensure user exists
            'service_id' => 'required|exists:services,id', // Ensure service exists
            'date' => 'required|date|after:today', // Ensure valid date
            'status' => 'required|in:pending,confirmed,completed,canceled', // Ensure valid status
        ]);

        // Check if validation passed
        if ($validated) {
            // Create the booking
            $booking = Booking::create([
                'user_id' => $request->user_id,
                'service_id' => $request->service_id,
                'date' => $request->date,
                'status' => $request->status,
            ]);

            // Return success response
            return response()->json([
                'message' => 'Booking created successfully',
                'data' => $booking,
            ], 201);
        }
    }


    // Get all bookings
    public function index()
    {
        $bookings = Booking::all();
        return response()->json(['data' => $bookings], 200);
    }

    // Get a booking by ID
    public function show($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        return response()->json(['data' => $booking], 200);
    }

    // Update a booking by ID
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'sometimes|date|after:today', // Booking date must be in the future if provided
            'status' => 'sometimes|in:pending,confirmed,completed,canceled', // Ensure valid status
        ]);

        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        // Update the booking
        $booking->update($request->all());

        return response()->json(['message' => 'Booking updated successfully', 'data' => $booking], 200);
    }

    // Delete a booking
    public function destroy($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        $booking->delete();

        return response()->json(['message' => 'Booking deleted successfully'], 200);
    }
}
