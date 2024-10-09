<?php


use App\Models\Users;
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

  Route::get('/find',function(){
     $user = Users::find(1);

     if ($user) {
        foreach($user->archives as $archive)
         echo$archive->total_amount_of_donation."<br />";
        
     } else {
         // Handle the case where the user with ID 1 is not found.
         echo "User not found.";
     }
    
  });

  Route::get('/test-product', function () {
    return view('test-product');
});


