<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
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

Route::post('register', [AuthController::class,'register']);
Route::get('approve/{id}', [AuthController::class,'approve']);
Route::post('login', [AuthController::class,'login']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router): void {
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('direct', [AuthController::class,'direct']);
    Route::delete('delete_user/{id}',[UserController::class,'delete_user']);
    Route::put('update_user/{id}',[UserController::class,'update_user']);
    Route::post('update_password',[UserController::class,'changePassword']);
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
    Route::get('sorted', [ProductController::class, 'getAllProductsSorted']);   // Fetch all products sorted by creation date
});
