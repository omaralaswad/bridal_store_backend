<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PromoCodeController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auths')->get('/users', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::get('approve/{id}', [AuthController::class, 'approve']);
Route::post('login', [AuthController::class, 'login']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router): void {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('direct', [AuthController::class, 'direct']);
    Route::delete('delete_user/{id}', [UserController::class, 'delete_user']);
    Route::put('update_user/{id}', [UserController::class, 'update_user']);
    Route::post('update_password', [UserController::class, 'changePassword']);
});

//Category Routes
Route::get('categories', [CategoryController::class, 'index']);       // List all categories
Route::post('categories', [CategoryController::class, 'store']);      // Create a new category
Route::get('categories/{id}', [CategoryController::class, 'show']);   // Get a single category
Route::put('categories/{id}', [CategoryController::class, 'update']); // Update a category
Route::delete('categories/{id}', [CategoryController::class, 'destroy']); // Delete a category

//Product Routes
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);                       // Fetch all products
    Route::post('/', [ProductController::class, 'store']);                      // Create a new product
    Route::get('{id}', [ProductController::class, 'show']);                     // Fetch a specific product
    Route::put('{id}', [ProductController::class, 'update']);                   // Update a specific product
    Route::delete('{id}', [ProductController::class, 'delete']);                // Delete a specific product
    Route::get('category/{categoryId}', [ProductController::class, 'getByCategoryId']); // Fetch products by category ID
    Route::get('last-products/{num}', [ProductController::class, 'getLastNProducts']);
    Route::get('last/sorted', [ProductController::class, 'getAllProductsSorted']);   // Fetch all products sorted by creation date
});

//Orders Routes
Route::prefix('orders')->group(function () {
    Route::post('/', [OrderController::class, 'store']); // Create a new order (Place an order)
    Route::get('/{id}', [OrderController::class, 'show']); // Get a specific order by ID
    Route::post('/checkout/{userId}', [OrderController::class, 'checkout']);
    Route::get('/user/{userId}', [OrderController::class, 'getUserOrders']); // Get all orders for a specific user
    Route::put('/{id}/cancel', [OrderController::class, 'cancelOrder']); // Cancel an order before it's processed/shipped
});

Route::post('payments/process', [PaymentController::class, 'processPayment']); // Process payment
Route::get('payments/status/{paymentId}', [PaymentController::class, 'getPaymentStatus']); // Get payment status


Route::prefix('cart')->group(function () {
    Route::post('add', [CartController::class, 'addToCart']);             // Add items to the cart
    Route::delete('remove', [CartController::class, 'removeFromCart']);   // Remove items from the cart
    Route::put('update', [CartController::class, 'updateCart']);          // Update cart items (e.g., quantity)
    Route::get('get/{user_id}', [CartController::class, 'getCart']);      // Get all items in the user's cart
});

Route::prefix('promo-codes')->group(function () {
    Route::post('add', [PromoCodeController::class, 'addPromoCode']); // Insert a new promo code
    Route::post('apply', [PromoCodeController::class, 'applyPromoCode']);  // Apply promo code to an order
    Route::delete('remove/{order_id}', [PromoCodeController::class, 'removePromoCode']); // Remove promo code from an order
    Route::post('/promo-code-discount', [PromoCodeController::class, 'getPromoCodeDiscount']);
});


Route::prefix('wishlist')->group(function () {
    Route::post('add', [WishlistController::class, 'addToWishlist']);  // Add item to wishlist
    Route::get('/{user_id}', [WishlistController::class, 'getWishlist']);       // Retrieve wishlist items
    Route::delete('/remove', [WishlistController::class, 'removeFromWishlist']); // Remove item from wishlist
});

Route::prefix('reviews')->group(function () {
    Route::post('submit', [ReviewController::class, 'submitReview']);  // Submit a review
    Route::get('{product_id}', [ReviewController::class, 'getReviews']);  // Get reviews for a product
});
