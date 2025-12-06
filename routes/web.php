<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentRegistrationController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\InstructorController; 
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\student;
use App\Http\Middleware\Instructor;


Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::get('/dashboard', [StudentDashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard')->middleware(student::class);


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // courses
    Route::get('/courses/dashboard', [CourseController::class, 'dashboard'])->name('courses.dashboard')->middleware(Admin::class);
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create')->middleware(Admin::class);
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store')->middleware(Admin::class);
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit')->middleware(Admin::class);
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update')->middleware(Admin::class);
    Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy')->middleware(Admin::class);

    // departments
    Route::get('/departments/dashboard', [DepartmentController::class, 'dashboard'])->name('departments.dashboard')->middleware(Admin::class);
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create')->middleware(Admin::class);
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store')->middleware(Admin::class);
    Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit')->middleware(Admin::class);
    Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update')->middleware(Admin::class);
    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');})->middleware(Admin::class);


    Route::get('/register-courses', [StudentRegistrationController::class, 'index'])->name('registration.index')->middleware(student::class);
    Route::post('/register-courses', [StudentRegistrationController::class, 'store'])->name('registration.store')->middleware(student::class);
    Route::delete('/register-courses/{registration}', [StudentRegistrationController::class, 'destroy'])->name('registration.destroy')->middleware(student::class);


    Route::get('/my-courses', [InstructorController::class, 'index'])->name('instructor.courses')->middleware(Instructor::class);
    Route::get('/my-courses/{course}/grade', [InstructorController::class, 'showStudents'])->name('instructor.grade.show')->middleware(Instructor::class);
    Route::post('/my-courses/{course}/grade', [InstructorController::class, 'storeGrades'])->name('instructor.grade.store')->middleware(Instructor::class);


    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index')->middleware(Admin::class);
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create')->middleware(Admin::class);
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store')->middleware(Admin::class);
    Route::get('/admin/users/{user}', [UserController::class, 'show'])->name('admin.users.show')->middleware(Admin::class);
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit')->middleware(Admin::class);
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update')->middleware(Admin::class);
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy')->middleware(Admin::class);

require __DIR__.'/auth.php';
