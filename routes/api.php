<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PayPalController;
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
    Route::put('update_user/{id}', [UserController::class, 'updateUser']);
    Route::post('update_password', [UserController::class, 'changePassword']);

});

Route::get('/users', [UserController::class, 'getAllUsers']);

Route::get('/services', [ServiceController::class, 'index']);
Route::post('/services', [ServiceController::class, 'store']); // Add a service
Route::get('/services/{id}', [ServiceController::class, 'show']); // Get a service by ID
Route::delete('/services/{id}', [ServiceController::class, 'destroy']); // Delete a service


Route::post('/payments', [PaymentController::class, 'store']);      // Create Payment
Route::get('/payments', [PaymentController::class, 'index']);       // Get All Payments
Route::get('/payments/{id}', [PaymentController::class, 'show']);    // Get Payment by ID
Route::put('/payments/{id}', [PaymentController::class, 'update']);  // Update Payment Status
Route::delete('/payments/{id}', [PaymentController::class, 'destroy']); // Delete Payment


Route::post('/bookings', [BookingController::class, 'store']); // Create a booking
Route::get('/bookings', [BookingController::class, 'index']); // Get all bookings
Route::get('/bookings/{id}', [BookingController::class, 'show']); // Get a booking by ID
Route::put('/bookings/{id}', [BookingController::class, 'update']); // Update a booking
Route::delete('/bookings/{id}', [BookingController::class, 'destroy']); // Delete a booking


Route::get('/paypal/pay', [PayPalController::class, 'createPayment'])->name('paypal.pay');
Route::get('/paypal/success', [PayPalController::class, 'paymentSuccess'])->name('paypal.success');
Route::get('/paypal/cancel', [PayPalController::class, 'paymentCancel'])->name('paypal.cancel');






