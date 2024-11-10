<?php


use App\Models\User;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/find',function(){
//     $donation = Donations::find(1);
//     $donation->user1->full_name;
// return ($donation);
// });

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard'); // Ensure this view exists
})->middleware('auth'); // Ensure this middleware is applied
// In routes/web.php
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login'); // Show login form
Route::post('login', [AuthController::class, 'login']); // Handle login

// Route::get('/admin/dashboard', function () {
//     return view('admin.dashboard');
// })->middleware('auth'); // Ensure only authenticated users can access


Route::get('/test-product', function () {
    return view('test-product');
});


