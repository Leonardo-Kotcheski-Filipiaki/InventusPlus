<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\UserController;

Route::get('/', [IndexController::class, 'index'])
->name('home')->middleware(['auth', 'verified']);

Route::name("user")->group(function () {
    Route::get("/login", [UserController::class, 'showLogin'])->name("login");
    Route::post("/login", [UserController::class, 'authenticate'])->name("login.post");
});