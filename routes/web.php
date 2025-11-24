<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home.index');

Route::middleware(['auth']) -> group(function(){
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
});

Route::middleware(['auth',AuthAdmin::class]) -> group(function(){
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/brands', [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/brands/create', [AdminController::class, 'createbrand'])->name('brand.create');
    Route::post('/admin/brands/store', [AdminController::class, 'storebrand'])->name('brand.store');
    Route::get('/admin/brands/{id}/edit', [AdminController::class, 'editbrand'])->name('brand.edit');
    Route::put('/admin/brands/{id}', [AdminController::class, 'updatebrand'])->name('brand.update');
    Route::delete('/admin/brands/{id}', [AdminController::class, 'deletebrand'])->name('brand.delete');
});
