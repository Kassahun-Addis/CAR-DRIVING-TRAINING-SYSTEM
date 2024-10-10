<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TraineeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\TrainingCarController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\Auth\LoginController; // Import LoginController

// Redirect root to login page
Route::get('/', function () {
    return redirect()->route('login'); // Redirect to the login page
});

// Login and Logout Routes
Route::post('/login', [LoginController::class, 'login'])->name('login'); // Add login route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Home and Welcome Routes
Route::get('/home', [StudentDashboardController::class, 'index'])->name('home');
Route::get('/welcome', [AdminDashboardController::class, 'index'])->name('welcome');

// Trainee Routes
Route::get('/trainee/create', [TraineeController::class, 'create'])->name('trainee.create');
Route::post('/trainee/store', [TraineeController::class, 'store'])->name('trainee.store');
Route::get('/trainee/list', [TraineeController::class, 'index'])->name('trainee.index');
Route::get('/trainee/edit/{id}', [TraineeController::class, 'edit'])->name('trainee.edit');
Route::put('/trainee/update/{id}', [TraineeController::class, 'update'])->name('trainee.update');
Route::delete('/trainee/destroy/{id}', [TraineeController::class, 'destroy'])->name('trainee.destroy');
Route::get('/home', [TraineeController::class, 'showDashboard'])->name('home');
Route::post('trainee/export', [TraineeController::class, 'exportToExcel'])->name('trainee.export');
//Route::get('/trainee/photo/{photoName}', [TraineeController::class, 'showPhoto']);

// Attendance Routes
Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
Route::get('/attendance/list', [AttendanceController::class, 'index'])->name('attendance.index');
Route::get('/attendance/{id}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit'); // Show edit form
Route::put('/attendance/{id}', [AttendanceController::class, 'update'])->name('attendance.update'); // Update attendance
Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy'); // Delete attendance


// Trainer Routes
Route::get('/trainers', [TrainerController::class, 'index'])->name('trainers.index');
Route::get('/trainers/create', [TrainerController::class, 'create'])->name('trainers.create');
Route::post('/trainers/store', [TrainerController::class, 'store'])->name('trainers.store');
Route::get('/trainers/edit/{id}', [TrainerController::class, 'edit'])->name('trainers.edit');
Route::put('/trainers/update/{id}', [TrainerController::class, 'update'])->name('trainers.update');
Route::delete('/trainers/destroy/{id}', [TrainerController::class, 'destroy'])->name('trainers.destroy');

// Training Car Routes
Route::get('/training_cars', [TrainingCarController::class, 'index'])->name('training_cars.index');
Route::get('/training_cars/create', [TrainingCarController::class, 'create'])->name('training_cars.create');
Route::post('/training_cars/store', [TrainingCarController::class, 'store'])->name('training_cars.store');
Route::get('/training_cars/{trainingCar}', [TrainingCarController::class, 'show'])->name('training_cars.show');
Route::get('/training_cars/{trainingCar}/edit', [TrainingCarController::class, 'edit'])->name('training_cars.edit');
Route::put('/training_cars/{trainingCar}/update', [TrainingCarController::class, 'update'])->name('training_cars.update');
Route::delete('/training_cars/{trainingCar}/destroy', [TrainingCarController::class, 'destroy'])->name('training_cars.destroy');

// Payment Routes
Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
Route::post('/payments/store', [PaymentController::class, 'store'])->name('payments.store');
Route::get('/payments/edit/{payment}', [PaymentController::class, 'edit'])->name('payments.edit');
Route::put('/payments/update/{payment}', [PaymentController::class, 'update'])->name('payments.update');
Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
Route::get('/payments/print/{payment}', [PaymentController::class, 'print'])->name('payments.print');

Route::resource('banks', BankController::class);

// Auth routes (if you are using built-in authentication)
Auth::routes();