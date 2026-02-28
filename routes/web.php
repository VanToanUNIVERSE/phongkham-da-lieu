<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\User;
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
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/admin/dashboard', [AdminController::class, "index"])->middleware('auth')->name('adminDashboard');
Route::resource('users', UserController::class);
Route::get('/patients/loadData', [PatientController::class, 'loadData']);
Route::resource('patients', PatientController::class);
Route::get('/appointments/loadData', [AppointmentController::class, 'loadData']);
Route::resource('appointments', AppointmentController::class);
Route::get('/medical_records/loadData', [MedicalRecordController::class, 'loadData']);
Route::resource('medical_records', MedicalRecordController::class);
Route::get('/medicines/loadData', [MedicineController::class, 'loadData']);
Route::resource('medicines', MedicineController::class);
Route::get('/prescriptions/loadData', [PrescriptionController::class, 'loadData']);
Route::resource('prescriptions', PrescriptionController::class);


Route::get('/doctor/dashboard', function(){
    return "Doctor Dashboard";
})->middleware('auth');

Route::get('/reception/dashboard', function(){
    return "Reception Dashboard";
})->middleware('auth');

Route::get('/pharmacy/dashboard', function(){
    return "Pharmacy Dashboard";
})->middleware('auth');
