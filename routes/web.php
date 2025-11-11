<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentRegistrationController;
use App\Http\Controllers\StudentDashboardController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', [StudentDashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // courses
    Route::get('/courses/dashboard', [CourseController::class, 'dashboard'])->name('courses.dashboard');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');

    // departments
    Route::get('/departments/dashboard', [DepartmentController::class, 'dashboard'])->name('departments.dashboard');
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');});


    // 1. الصفحة اللي بتعرض الكورسات المتاحة (اللي عملناها)
    Route::get('/register-courses', [StudentRegistrationController::class, 'index'])
         ->name('registration.index');
         
    // 2. الراوت اللي الطالب هيبعتله الكورس عشان يسجله (لسه هنعمله)
    Route::post('/register-courses', [StudentRegistrationController::class, 'store'])
         ->name('registration.store');


require __DIR__.'/auth.php';
