<?php
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderOverviewController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\Orders\KitchenController;
use App\Http\Controllers\Orders\OrderController;
use App\Http\Controllers\Orders\WaiterController;
use Illuminate\Support\Facades\Route;

// Preserve Elkin's public catalog routes and names.
Route::get('/',[DishController::class,'catalog'])->name('home');
Route::get('/menu',[DishController::class,'catalog'])->name('dishes.catalog');
Route::middleware('guest')->group(function(){
    Route::get('/login',[SessionController::class,'loginForm'])->name('login');
    Route::post('/login',[SessionController::class,'login'])->name('login.submit');
    Route::get('/register',[SessionController::class,'registerForm'])->name('register');
    Route::post('/register',[SessionController::class,'register'])->name('register.submit');
});
Route::middleware('auth')->group(function(){
    Route::post('/logout',[SessionController::class,'logout'])->name('logout');
    Route::get('/home',[SessionController::class,'roleHome'])->name('home.for-role');
    Route::middleware('role:Client')->group(function(){
        Route::get('/orders',[OrderController::class,'index'])->name('orders.index');
        Route::post('/orders',[OrderController::class,'store'])->name('orders.store');
        Route::get('/orders/{group}',[OrderController::class,'show'])->name('orders.show')->whereUuid('group');
    });
    Route::middleware('role:Chef')->prefix('kitchen')->name('kitchen.')->group(function(){
        Route::get('/',[KitchenController::class,'index'])->name('index');
        Route::patch('/orders/{order}/{action}',[KitchenController::class,'transition'])->name('transition')->whereIn('action',['start','ready']);
    });
    Route::middleware('role:Waiter')->prefix('waiter')->name('waiter.')->group(function(){
        Route::get('/',[WaiterController::class,'index'])->name('index');
        Route::get('/create',[WaiterController::class,'create'])->name('create');
        Route::post('/orders',[WaiterController::class,'store'])->name('store');
        Route::patch('/orders/{order}/serve',[WaiterController::class,'serve'])->name('serve');
    });
    Route::middleware('role:Administrator')->prefix('admin')->name('admin.')->group(function(){
        Route::get('/',[DashboardController::class,'index'])->name('dashboard');
        Route::get('/orders',[OrderOverviewController::class,'index'])->name('orders.index');
        Route::resource('users',UserController::class)->except('show');
        // Only Admin can perform Elkin's dish CRUD.
    });
    Route::resource('dishes',DishController::class)->except('show')->middleware('role:Administrator');
});
