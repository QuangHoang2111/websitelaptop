<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\VnpayController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home.index');

Route::middleware(['auth'])->group(function(){
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/orders', [UserController::class, 'orders'])->name('user.orders');
    Route::post('/address/save', [UserController::class, 'saveAddress'])->name('user.address.save');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/apply-voucher', [CartController::class, 'applyVoucher'])->name('cart.voucher');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/place', [CartController::class, 'placeOrder'])->name('checkout.place');
    Route::get('/user/orders-list', [UserController::class, 'orderList'])->name('user.orders.list');
    Route::get('/user/orders/{order}', [UserController::class, 'orderDetail'])->name('user.orders.show');
    Route::post('/user/orders/{order}/cancel', [UserController::class, 'cancelOrder'])->name('user.orders.cancel');
    Route::post('/checkout', [CartController::class, 'placeOrder'])->name('checkout.place');
    Route::middleware('auth')->group(function () {
    Route::get('/user/changepassword', [UserController::class, 'changePasswordForm'])->name('user.password.form');
    Route::post('/user/changepassword', [UserController::class, 'changePassword'])->name('user.password.update');
});

});


Route::middleware(['auth', AuthAdmin::class])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::resource('brands', BrandController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::get('search', [AdminController::class, 'search'])->name('search');
    Route::resource('sliders', SliderController::class);
    Route::resource('vouchers', VoucherController::class);
    Route::get('users', [AdminController::class, 'users'])->name('users');
    Route::get('users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('/orders/{order}/update-date',[AdminOrderController::class, 'updateOrderDate'])->name('orders.updateDate');
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::middleware(['auth', AuthAdmin::class])->get('/dashboard/data', [AdminController::class, 'dashboardData'])->name('admin.dashboard.data');

});

Route::get('/products/{slug}', [HomeController::class, 'productDetail'])->name('products.detail');
Route::get('/products', [HomeController::class, 'products'])->name('products.index');
Route::get('/search/suggest', [HomeController::class, 'searchSuggest'])->name('search.suggest');
Route::get('/vnpay/return', [VnpayController::class, 'return'])->name('vnpay.return');
Route::get('/vnpay/pay/{order}', [VnpayController::class, 'pay'])->name('vnpay.pay');