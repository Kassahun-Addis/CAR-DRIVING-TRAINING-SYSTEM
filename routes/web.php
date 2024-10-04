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

// Trainer Routes
Route::get('/trainers', [TrainerController::class, 'index'])->name('trainers.index'); // Route to display the list of trainers
Route::get('/trainers/create', [TrainerController::class, 'create'])->name('trainers.create'); // Route to show form for adding a new trainer
Route::post('/trainers/store', [TrainerController::class, 'store'])->name('trainers.store'); // Route to handle form submission for storing a trainer
Route::get('/trainers/edit/{id}', [TrainerController::class, 'edit'])->name('trainers.edit'); // Route to show form for editing a trainer
Route::put('/trainers/update/{id}', [TrainerController::class, 'update'])->name('trainers.update'); // Route to handle form submission for updating a trainer
Route::delete('/trainers/destroy/{id}', [TrainerController::class, 'destroy'])->name('trainers.destroy'); // Route to delete a trainer

// Training Car Routes
Route::get('/training_cars', [TrainingCarController::class, 'index'])->name('training_cars.index'); // Route to display the list of training cars
Route::get('/training_cars/create', [TrainingCarController::class, 'create'])->name('training_cars.create'); // Route to show form for adding a new training car
Route::post('/training_cars/store', [TrainingCarController::class, 'store'])->name('training_cars.store'); // Route to handle form submission for storing a training car
Route::get('/training_cars/{trainingCar}', [TrainingCarController::class, 'show'])->name('training_cars.show'); // Route to show a specific training car
Route::get('/training_cars/{trainingCar}/edit', [TrainingCarController::class, 'edit'])->name('training_cars.edit'); // Route to show form for editing a training car
Route::put('/training_cars/{trainingCar}/update', [TrainingCarController::class, 'update'])->name('training_cars.update'); // Route to handle form submission for updating a training car
Route::delete('/training_cars/{trainingCar}/destroy', [TrainingCarController::class, 'destroy'])->name('training_cars.destroy'); // Route to delete a training car