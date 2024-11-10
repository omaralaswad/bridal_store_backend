<?php

namespace App\Http\Controllers;
use App\Models\PromoCode;
use App\Models\Order;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    // Apply promo code to an order
    // Apply promo code to an order and update the total amount
    public function applyPromoCode(Request $request)
    {
        // Validate request data to ensure 'code' and 'order_id' are provided
        $validatedData = $request->validate([
            'code' => 'required|string|exists:promo_codes,code',
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        // Find the promo code using the validated code
        $promoCode = PromoCode::where('code', $validatedData['code'])->first();

        // Check if the promo code has expired
        if ($promoCode->expiry_date < now()) {
            return response()->json(['message' => 'Promo code has expired'], 400);
        }

        // Find the order using the validated order_id
        $order = Order::find($validatedData['order_id']);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Calculate the new total after applying the promo code discount
        $discountedAmount = $order->total_amount * ($promoCode->discount_percentage / 100);
        $newTotalAmount = $order->total_amount - $discountedAmount;

        // Update the order's total amount with the discounted price
        $order->total_amount = $newTotalAmount;
        $order->save();

        return response()->json([
            'message' => 'Promo code applied successfully',
            'discount_percentage' => $promoCode->discount_percentage,
            'new_total_amount' => $newTotalAmount
        ], 200);
    }






    public function getPromoCodeDiscount(Request $request)
    {
        // Validate the request to ensure 'code' is present
        $validatedData = $request->validate([
            'code' => 'required|string|max:255',
        ]);

        // Search for the promo code
        $promoCode = PromoCode::where('code', $validatedData['code'])->first();

        // Check if the promo code was found and is not expired
        if ($promoCode && $promoCode->expiry_date >= now()) {
            return response()->json([
                'code' => $promoCode->code,
                'discount_percentage' => $promoCode->discount_percentage,
                'message' => 'Promo code is valid.',
            ], 200);
        }

        // If promo code was not found or is expired
        return response()->json([
            'message' => 'Promo code is invalid or expired.',
        ], 404);
    }


    // Remove applied promo code from an order
    public function removePromoCode(Request $request, $order_id)
    {
        // Find the order
        $order = Order::find($order_id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Retrieve the promo code from the request
        // You may store the applied promo code in the session, or pass it in the request
        $promoCode = $request->input('promo_code'); // Assuming promo_code is sent with the request

        // Find the promo code details
        $promo = PromoCode::where('code', $promoCode)->first();

        if (!$promo) {
            return response()->json(['message' => 'Promo code not found'], 404);
        }

        // Calculate the original total amount based on the applied discount
        $discountPercentage = $promo->discount_percentage;
        $originalTotalAmount = $order->total_amount / (1 - ($discountPercentage / 100));

        // Update the order's total_amount to the original value
        $order->total_amount = $originalTotalAmount;
        $order->save();

        return response()->json([
            'message' => 'Promo code removed successfully, total amount restored',
            'new_total_amount' => $order->total_amount,
        ], 200);
    }


    public function addPromoCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:promo_codes,code',
            'discount_percentage' => 'required|integer|min:0|max:100',
            'expiry_date' => 'required|date|after:today', // Ensure the expiry date is in the future
        ]);

        // Create the promo code
        $promoCode = PromoCode::create([
            'code' => $request->code,
            'discount_percentage' => $request->discount_percentage,
            'expiry_date' => $request->expiry_date,
        ]);

        return response()->json(['message' => 'Promo code added successfully', 'promo_code' => $promoCode], 201);
    }
}
