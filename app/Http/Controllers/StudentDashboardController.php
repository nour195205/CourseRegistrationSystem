<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // (مهم عشان نجيب اليوزر)

class StudentDashboardController extends Controller
{
    /**
     * الدالة دي هي اللي هتعرض الداشبورد
     */
    public function index()
    {
        // 1. هات الطالب اللي مسجل دخول
        $student = Auth::user();

        // 2. هات الكورسات المسجلة حاليًا (مع بيانات الكورس)
        $registrations = $student->registrations()->with('course')->get();

        // 3. هات الكورسات المكتملة (مع بيانات الكورس)
        $completedCourses = $student->completedCourses()->with('course')->get();

        // 4. ابعت كل الداتا دي للـ view
        return view('dashboard', compact(
            'student', 
            'registrations', 
            'completedCourses'
        ));
    }
}