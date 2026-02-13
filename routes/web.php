<?php

use App\Http\Controllers\AuthController;
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

Route::get("/login", [AuthController::class, 'showLogin'])->name("login");
Route::post("/login", [AuthController::class, "login"])->name("postLogin");
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/admin/dashboard', function(){
    return "Admin Dashboard";
})->middleware('auth');

Route::get('/doctor/dashboard', function(){
    return "Doctor Dashboard";
})->middleware('auth');

Route::get('/reception/dashboard', function(){
    return "Reception Dashboard";
})->middleware('auth');

Route::get('/pharmacy/dashboard', function(){
    return "Pharmacy Dashboard";
})->middleware('auth');
