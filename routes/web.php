<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TraineeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\TrainingCarController;



Route::get('/', function () {
    return view('welcome');
});

// Trainee Routes
Route::get('/trainee/create', [TraineeController::class, 'create'])->name('trainee.create'); // Route to show form for adding a new trainee
Route::post('/trainee/store', [TraineeController::class, 'store'])->name('trainee.store'); // Route to handle form submission for storing trainee
Route::get('/trainee/list', [TraineeController::class, 'index'])->name('trainee.index'); // Route to display the list of trainees
Route::get('/trainee/edit/{id}', [TraineeController::class, 'edit'])->name('trainee.edit'); // Route to show form for editing a trainee
Route::put('/trainee/update/{id}', [TraineeController::class, 'update'])->name('trainee.update'); // Route to handle form submission for updating trainee
Route::delete('/trainee/destroy/{id}', [TraineeController::class, 'destroy'])->name('trainee.destroy'); // Route to delete a trainee

// Attendance Routes
Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create'); // Route to show form for adding attendance
Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store'); // Route to handle form submission for storing attendance
Route::get('/attendance/list', [AttendanceController::class, 'show'])->name('attendance.show'); // Route to display the list of attendance


Route::get('trainers/create', [TrainerController::class, 'create'])->name('trainers.create');
Route::post('trainers', [TrainerController::class, 'store'])->name('trainers.store');


Route::get('training_cars', [TrainingCarController::class, 'index'])->name('training_cars.index');
Route::get('training_cars/create', [TrainingCarController::class, 'create'])->name('training_cars.create');
Route::post('training_cars', [TrainingCarController::class, 'store'])->name('training_cars.store');
Route::get('training_cars/{trainingCar}', [TrainingCarController::class, 'show'])->name('training_cars.show');
Route::get('training_cars/{trainingCar}/edit', [TrainingCarController::class, 'edit'])->name('training_cars.edit');
Route::put('training_cars/{trainingCar}', [TrainingCarController::class, 'update'])->name('training_cars.update');
Route::delete('training_cars/{trainingCar}', [TrainingCarController::class, 'destroy'])->name('training_cars.destroy');