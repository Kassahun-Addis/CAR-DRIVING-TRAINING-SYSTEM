<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TraineeController;
use App\Http\Controllers\AttendanceController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/trainee/create', [TraineeController::class, 'create'])->name('trainee.create');
Route::post('/trainee/store', [TraineeController::class, 'store'])->name('trainee.store');
Route::get('/trainee/list', [TraineeController::class, 'show'])->name('trainee.show');

Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
Route::get('/attendance/list', [AttendanceController::class, 'show'])->name('attendance.show');

