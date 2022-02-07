<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AjaxController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/users', UserController::class);
    Route::resource('/providers', ProviderController::class);
    Route::resource('/categories', CategoryController::class);
    Route::resource('/sub_categories', SubCategoryController::class);
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/get_providers', [SubscriptionController::class, 'get_providers'])->name('subscriptions.get_providers');
    Route::post('/subscriptions/get_categories', [SubscriptionController::class, 'get_categories'])->name('subscriptions.get_categories');
    Route::get('/subscriptions/get_sub_categories/{id}/{_id}', [SubscriptionController::class, 'get_sub_categories'])->name('subscriptions.get_sub_categories');
    Route::post('/subscriptions/checkout/{id}/{_id}', [SubscriptionController::class, 'checkout'])->name('subscriptions.checkout');
    Route::post('/subscriptions/pay_securely/{id}/{_id}', [SubscriptionController::class, 'pay_securely'])->name('subscriptions.pay_securely');
    Route::resource('/banners', BannerController::class);
    Route::resource('/reviews', ReviewController::class);
    Route::post('/takeChangeStatusAction', [AjaxController::class, 'takeChangeStatusAction'])->name('takeChangeStatusAction');
    Route::post('/takeDeleteAction', [AjaxController::class, 'takeDeleteAction'])->name('takeDeleteAction');
});
