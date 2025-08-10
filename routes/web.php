<?php

use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\ContactController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\PackageController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group whichf
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/migrate-refresh', function () {
//     // Run the migration command
//     Artisan::call('migrate:fresh --seed');

//     // Get the output of the command
//     $output = Artisan::output();

//     // Return a response with the output
//     return response()->json(['message' => 'Migration and seeding completed successfully', 'output' => $output]);
// });


Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {



    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/customer_service', [HomeController::class, 'customer_service'])->name('customer_service');
    Route::get('/mission', [HomeController::class, 'mission'])->name('mission');
    Route::get('/policy', [HomeController::class, 'policy'])->name('policy');

    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.details');
    Route::get('/package/{id}', [PackageController::class, 'show'])->name('package.details');


   
    Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    
    Route::middleware('guest:user')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('user.login');
        Route::get('/register', [AuthController::class, 'showRegister'])->name('user.register');
        Route::post('/login', [AuthController::class, 'login'])->name('user.login.submit');
        Route::post('/register', [AuthController::class, 'register'])->name('user.register.submit');
        Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');
        
        // Google Login
        Route::get('login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
        Route::get('login/google/callback', [AuthController::class, 'handleGoogleCallback']);
    });
    // Auth Route **
    Route::group(['middleware' => 'auth:user'], function () {

      Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
      Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
      Route::get('/profile/edit', [ProfileController::class, 'editProfile'])->name('profile.edit');
      Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    

         // View cart
    Route::get('/cart', 'App\Http\Controllers\User\CartController@index')->name('cart.index');
        
    // Add to cart (AJAX)
    Route::post('/cart/add', 'App\Http\Controllers\User\CartController@add')->name('cart.add');
    
    // Remove from cart
    Route::get('/cart/remove/{id}', 'App\Http\Controllers\User\CartController@remove')->name('cart.remove');
    
    // Update cart
    Route::post('/cart/update', 'App\Http\Controllers\User\CartController@update')->name('cart.update');
    
    // Apply coupon
    Route::post('/cart/apply-coupon', 'App\Http\Controllers\User\CartController@applyCoupon')->name('cart.apply-coupon');
    
    // Checkout
    Route::get('/checkout', 'App\Http\Controllers\User\CheckoutController@index')->name('checkout');

    
      // Process checkout
      Route::post('/checkout/process', 'App\Http\Controllers\User\CheckoutController@process')->name('checkout.process');
      
      // Order confirmation
      Route::get('/orders', 'App\Http\Controllers\User\OrderController@history')->name('orders.history');




        // Create new address form
        Route::get('/create', 'App\Http\Controllers\User\AddressController@create')->name('address.create');
    
        // Store new address
        Route::post('/store', 'App\Http\Controllers\User\AddressController@store')->name('address.store');
        
        // Set address as default
        Route::post('/default/{id}', 'App\Http\Controllers\User\AddressController@setDefault')->name('address.default');
        
        // Delete address
        Route::delete('/delete/{id}', 'App\Http\Controllers\User\AddressController@destroy')->name('address.destroy');
        
        // Ajax get addresses
        Route::get('/user', 'App\Http\Controllers\User\AddressController@getUserAddresses')->name('address.user');

    });



    
});
