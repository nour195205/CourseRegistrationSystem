<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});





Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // courses
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::get('/courses/store', [CourseController::class, 'store'])->name('courses.store');

    // departments
    Route::get('/departments/dashboard', [DepartmentController::class, 'dashboard'])->name('departments.dashboard');
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');});

require __DIR__.'/auth.php';
