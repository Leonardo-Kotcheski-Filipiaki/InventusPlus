<?php

use App\Http\Controllers\CostumerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\UserController;


Route::get("/login", [UserController::class, 'showLogin'])->name("login")->middleware('guest');  
Route::post("/login", [UserController::class, 'authenticate'])->name("login.post")->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::get('/', [IndexController::class, 'index'])->name('home');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    


    Route::resource("costumers", CostumerController::class);
    // Route::resource("suppliers", SupplierController::class);
    // Route::resource("products", ProductController::class);
    // Route::resource("sales", SaleController::class);
});