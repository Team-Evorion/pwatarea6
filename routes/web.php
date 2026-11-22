<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DishController;

Route::get('/', [DishController::class, 'catalog'])->name('home');
Route::get('/menu', [DishController::class, 'catalog'])->name('dishes.catalog');
Route::resource('dishes', DishController::class)->except(['show']);