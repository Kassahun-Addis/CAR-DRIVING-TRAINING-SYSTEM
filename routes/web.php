<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TraineeController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/trainee/create', [TraineeController::class, 'create'])->name('trainee.create');
Route::post('/trainee/store', [TraineeController::class, 'store'])->name('trainee.store');
Route::get('/trainee/list', [TraineeController::class, 'show'])->name('trainee.show');


