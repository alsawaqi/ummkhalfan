<?php

use App\Http\Controllers\CustomersController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductController;
use App\Notifications\OrdersCompleted;
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

// Route::get('/test', function () {
//     // request()->user()->notify(new OrdersCompleted(900));

//     $notification = tap(auth()->user()->unreadNotifications)->markAsRead();

//     return response()->json($notification);
// });

Route::controller(ProductController::class)->group(function () {
    Route::get('/product', 'index');
    Route::get('/product/{products}', 'show');
    Route::get('/prod/{product:id}', 'shows');
    Route::get('/productfeatured', 'getfeatured');
});

Route::controller(CustomersController::class)->group(function () {
    Route::get('/customer', 'index');
    Route::put('/customer', 'update');
});

Route::controller(OrdersController::class)->group(function () {
    Route::get('/orders', 'index');
    Route::get('/orders/{orders:reference}', 'show');
    Route::post('/orders', 'store');
})->middleware('auth');

Route::view('/{any}', 'welcome')->where('any', '.*');
