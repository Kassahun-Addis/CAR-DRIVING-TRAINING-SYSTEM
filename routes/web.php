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
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CarCategoryController;
use App\Http\Controllers\TheoreticalClassController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\TrainerAssigningController;
use App\Http\Controllers\AccountController; // Make sure to import your controller




// Redirect root to login page
Route::get('/', function () {
    return redirect()->route('login'); // Redirect to the login page
});

// Login and Logout Routes
Route::post('/login', [LoginController::class, 'login'])->name('login'); // Add login route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Home and Welcome Routes
Route::get('/home', [StudentDashboardController::class, 'index'])
    ->middleware('auth:trainee') // Protect the home route
    ->name('home');

Route::get('/welcome', [AdminDashboardController::class, 'index'])
    ->middleware('auth') // Protect the welcome route (admin)
    ->name('welcome');

// Trainee Routes
//Route::middleware('auth')->group(function () {
    Route::get('/trainee/create', [TraineeController::class, 'create'])->name('trainee.create');
    Route::post('/trainee/store', [TraineeController::class, 'store'])->name('trainee.store');
    Route::get('/trainee/list', [TraineeController::class, 'index'])->name('trainee.index');
    Route::get('/trainee/edit/{id}', [TraineeController::class, 'edit'])->name('trainee.edit');
    Route::put('/trainee/update/{id}', [TraineeController::class, 'update'])->name('trainee.update');
    Route::delete('/trainee/destroy/{id}', [TraineeController::class, 'destroy'])->name('trainee.destroy');
    Route::post('trainee/export', [TraineeController::class, 'exportToExcel'])->name('trainee.export');
    Route::get('/trainee/{id}/agreement', [TraineeController::class, 'showAgreement'])->name('trainee.agreement');
    Route::get('/trainee/{id}/agreement', [TraineeController::class, 'showAgreement'])->name('trainee.agreement');
    Route::get('/trainee/{id}/download-agreement', [TraineeController::class, 'downloadAgreement'])->name('download.agreement');
 //});

// Attendance Routes
// Route::middleware('auth:trainee')->group(function () {
    Route::get('/trainee/dashboard', [StudentDashboardController::class, 'showDashboard'])->name('trainee.dashboard'); // Trainee dashboard
    Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/list', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/{id}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit'); // Show edit form
    Route::put('/attendance/{id}', [AttendanceController::class, 'update'])->name('attendance.update'); // Update attendance
    Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy'); // Delete attendance
    //Route::get('/attendance/{trainee_id}', [AttendanceController::class, 'showAttendance'])->name('attendance.show');
    Route::get('/attendance/{traineeId?}', [AttendanceController::class, 'index'])->name('attendance.index');
// });


// // Attendance Routes
// Route::middleware('auth')->group(function () {
//     Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
//     Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
//     Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
//     Route::get('/attendance/{id}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
//     Route::put('/attendance/{id}', [AttendanceController::class, 'update'])->name('attendance.update');
//     Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
//     Route::get('/attendance/{trainee_id}', [AttendanceController::class, 'showAttendance'])->name('attendance.show');
// });

// Trainer Routes
//Route::middleware('auth:trainee')->group(function () {
    Route::middleware('auth')->group(function () {
    Route::get('/trainers', [TrainerController::class, 'index'])->name('trainers.index');
    Route::get('/trainers/create', [TrainerController::class, 'create'])->name('trainers.create');
    Route::post('/trainers/store', [TrainerController::class, 'store'])->name('trainers.store');
    Route::get('/trainers/edit/{trainer}', [TrainerController::class, 'edit'])->name('trainers.edit'); // Update here
    Route::put('/trainers/update/{trainer}', [TrainerController::class, 'update'])->name('trainers.update'); // Update here
    Route::delete('/trainers/destroy/{trainer}', [TrainerController::class, 'destroy'])->name('trainers.destroy');
});

// Training Car Routes
// Route::middleware('auth:trainee')->group(function () {
    Route::middleware('auth')->group(function () {
    Route::get('/training_cars', [TrainingCarController::class, 'index'])->name('training_cars.index');
    Route::get('/training_cars/create', [TrainingCarController::class, 'create'])->name('training_cars.create');
    Route::post('/training_cars/store', [TrainingCarController::class, 'store'])->name('training_cars.store');
    Route::get('/training_cars/{trainingCar}', [TrainingCarController::class, 'show'])->name('training_cars.show');
    Route::get('/training_cars/{trainingCar}/edit', [TrainingCarController::class, 'edit'])->name('training_cars.edit');
    Route::put('/training_cars/{trainingCar}/update', [TrainingCarController::class, 'update'])->name('training_cars.update');
    Route::delete('/training_cars/{trainingCar}/destroy', [TrainingCarController::class, 'destroy'])->name('training_cars.destroy');
});

// Payment Routes
// Route::middleware('auth:trainee')->group(function () {
    Route::middleware('auth')->group(function () {
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments/store', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/edit/{payment}', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/payments/update/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::get('/payments/print/{payment}', [PaymentController::class, 'print'])->name('payments.print');
    Route::get('payments/{payment}/pay_remaining', [PaymentController::class, 'payRemaining'])->name('payments.pay_remaining');
    Route::post('payments/{payment}/pay_remaining_process', [PaymentController::class, 'processRemainingPayment'])->name('payments.pay_remaining_process');
    Route::get('payments/{payment}/history', [PaymentController::class, 'showPaymentHistory'])->name('payments.history');
});

// Bank Routes
Route::resource('banks', BankController::class);

// Bank Routes
// Route::get('/banks', [BankController::class, 'index'])->name('banks.index'); // List all banks
// Route::get('/banks/create', [BankController::class, 'create'])->name('banks.create'); // Show form to create a new bank
// Route::post('/banks', [BankController::class, 'store'])->name('banks.store'); // Store a new bank
// Route::get('/banks/{bank}', [BankController::class, 'show'])->name('banks.show'); // Show a specific bank
// Route::get('/banks/{bank}/edit', [BankController::class, 'edit'])->name('banks.edit'); // Show form to edit a specific bank
// Route::put('/banks/{bank}', [BankController::class, 'update'])->name('banks.update'); // Update a specific bank
// Route::delete('/banks/{bank}', [BankController::class, 'destroy'])->name('banks.destroy'); // Delete a specific bank

// Bank Routes
Route::get('/car_category', [CarCategoryController::class, 'index'])->name('car_category.index'); // List all car_category
Route::get('/car_category/create', [CarCategoryController::class, 'create'])->name('car_category.create'); // Show form to create a new CarCategory
Route::post('/car_category', [CarCategoryController::class, 'store'])->name('car_category.store'); // Store a new CarCategory
Route::get('/car_category/{CarCategory}/edit', [CarCategoryController::class, 'edit'])->name('car_category.edit'); // Show form to edit a specific CarCategory
Route::put('/car_category/{CarCategory}', [CarCategoryController::class, 'update'])->name('car_category.update'); // Update a specific CarCategory
Route::delete('/car_category/{CarCategory}', [CarCategoryController::class, 'destroy'])->name('car_category.destroy'); // Delete a specific bank

// theoretical_class Routes
Route::get('/trainer_assigning', [TrainerAssigningController::class, 'index'])->name('trainer_assigning.index'); // List all trainer_assigning
Route::get('/trainer_assigning/create', [TrainerAssigningController::class, 'create'])->name('trainer_assigning.create'); // Show form to create a new CarCategory
Route::post('/trainer_assigning', [TrainerAssigningController::class, 'store'])->name('trainer_assigning.store'); // Store a new CarCategory
Route::get('/trainer_assigning/{trainer_assigning}/edit', [TrainerAssigningController::class, 'edit'])->name('trainer_assigning.edit'); // Show form to edit a specific trainer_assigning
Route::put('/trainer_assigning/{trainer_assigning}', [TrainerAssigningController::class, 'update'])->name('trainer_assigning.update'); // Update a specific trainer_assigning
Route::delete('/trainer_assigning/{trainer_assigning}', [TrainerAssigningController::class, 'destroy'])->name('trainer_assigning.destroy'); // Delete a specific bank
// Route::get('/car-category/{categoryId}/plates', [TrainerAssigningController::class, 'getPlatesByCategory']); // New route for fetching plates
//Route::get('/trainer_assigning/trainers-with-count', [TrainerAssigningController::class, 'getTrainersWithCount']);
Route::get('/trainers/{trainerName}/details', [TrainerController::class, 'getDetails']);


Route::get('/theoretical_class', [TheoreticalClassController::class, 'index'])->name('theoretical_class.index'); // List all theoretical_class
Route::get('/theoretical_class/create', [TheoreticalClassController::class, 'create'])->name('theoretical_class.create'); // Show form to create a new CarCategory
Route::post('/theoretical_class', [TheoreticalClassController::class, 'store'])->name('theoretical_class.store'); // Store a new CarCategory
Route::get('/theoretical_class/{theoreticalClass}/edit', [TheoreticalClassController::class, 'edit'])->name('theoretical_class.edit'); // Show form to edit a specific theoretical_class
Route::put('/theoretical_class/{theoreticalClass}', [TheoreticalClassController::class, 'update'])->name('theoretical_class.update'); // Update a specific theoretical_class
Route::delete('/theoretical_class/{theoretical_class}', [TheoreticalClassController::class, 'destroy'])->name('theoretical_class.destroy');// Route::get('/car-category/{categoryId}/plates', [TheoreticalClassController::class, 'getPlatesByCategory']); // New route for fetching plates
//Route::get('/theoretical_class/trainers-with-count', [TheoreticalClassController::class, 'getTrainersWithCount']);
Route::get('/trainers/{trainerName}/details', [TrainerController::class, 'getDetails']);


Route::resource('classes', ClassesController::class);


// Auth routes (if you are using built-in authentication)
Auth::routes();

// Route to display the account management page
Route::get('/account/manage', [AccountController::class, 'manage'])->name('account.manage');

// Route to handle the account update
Route::put('/account/update', [AccountController::class, 'update'])->name('account.update');
