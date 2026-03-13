<?php

use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InvoiceController;
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
Route::get('/invoices/calculateCost/{medicalRecordId}', [InvoiceController::class, 'calculateCost']);
Route::get('/invoices/loadData', [InvoiceController::class, 'loadData']);
Route::resource('invoices', InvoiceController::class);
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

Route::prefix('doctor')->middleware('auth')->group(function () {
    Route::get('/dashboard',       [DoctorController::class, 'index'])->name('doctor.dashboard');
    Route::get('/appointments',    [DoctorController::class, 'appointments'])->name('doctor.appointments');
    Route::get('/appointments/load', [DoctorController::class, 'loadAppointments'])->name('doctor.appointments.load');
    Route::put('/appointments/{appointment}/status', [DoctorController::class, 'updateAppointmentStatus'])->name('doctor.appointments.status');
    Route::get('/medical-records', [DoctorController::class, 'medicalRecords'])->name('doctor.medical_records');
    Route::get('/medical-records/load', [DoctorController::class, 'loadMedicalRecords'])->name('doctor.medical_records.load');
    Route::post('/medical-records', [DoctorController::class, 'storeMedicalRecord'])->name('doctor.medical_records.store');
    Route::put('/medical-records/{medical_record}', [DoctorController::class, 'updateMedicalRecord'])->name('doctor.medical_records.update');
    Route::get('/prescriptions',      [DoctorController::class, 'prescriptions'])->name('doctor.prescriptions');
    Route::get('/prescriptions/load',  [DoctorController::class, 'loadPrescriptions'])->name('doctor.prescriptions.load');
    Route::get('/patients/{patient}/history', [DoctorController::class, 'getPatientHistory'])->name('doctor.patients.history');
});

Route::prefix('reception')->middleware('auth')->group(function () {
    Route::get('/dashboard',              [\App\Http\Controllers\ReceptionController::class, 'index'])->name('reception.dashboard');
    Route::get('/appointments',           [\App\Http\Controllers\ReceptionController::class, 'appointments'])->name('reception.appointments');
    Route::get('/appointments/load',      [\App\Http\Controllers\ReceptionController::class, 'loadAppointments'])->name('reception.appointments.load');
    Route::post('/appointments',          [\App\Http\Controllers\ReceptionController::class, 'storeAppointment'])->name('reception.appointments.store');
    Route::put('/appointments/{appointment}/status', [\App\Http\Controllers\ReceptionController::class, 'updateAppointmentStatus'])->name('reception.appointments.status');
    Route::post('/patients',              [\App\Http\Controllers\ReceptionController::class, 'storePatient'])->name('reception.patients.store');
    Route::get('/appointments/{appointment}/invoice', [\App\Http\Controllers\ReceptionController::class, 'getAppointmentInvoice'])->name('reception.appointments.invoice');
});

Route::get('/pharmacy/dashboard', function(){
    return "Pharmacy Dashboard";
})->middleware('auth');
