<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentRegistrationController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\InstructorController; 
use App\Http\Controllers\Admin\UserController;


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
    Route::get('/register-courses', [StudentRegistrationController::class, 'index'])->name('registration.index');
    Route::post('/register-courses', [StudentRegistrationController::class, 'store'])->name('registration.store');


    // الراوت الجديد بتاع الدكتور
    Route::get('/my-courses', [InstructorController::class, 'index'])->name('instructor.courses');
    Route::get('/my-courses/{course}/grade', [InstructorController::class, 'showStudents'])->name('instructor.grade.show');
    Route::post('/my-courses/{course}/grade', [InstructorController::class, 'storeGrades'])->name('instructor.grade.store');


    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
Route::get('/admin/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

require __DIR__.'/auth.php';
