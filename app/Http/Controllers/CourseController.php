<?php

namespace App\Http\Controllers;
use App\Models\Course;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    
    // public function index()
    // {
    //     return view('courses.index');
    // }
        
    public function create()
    {
        return view('courses.create');
    }

    
    public function store(Request $request)
    {
        // 1. التحقق من صحة البيانات (Validation)
        $validatedData = $request->validate([
            'code' => 'required|string|unique:courses|max:255',
            'name' => 'required|string|max:255',
            'credits' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        // 2. تخزين البيانات في الداتابيز
        Course::create($validatedData);

        // 3. إعادة التوجيه لصفحة الكورسات الرئيسية (اللي لسه هنعملها)
        // with() بتبعت رسالة نجاح مؤقتة للـ session
        return Redirect::route('courses.index')->with('status', 'course-created');
    }

    
    // public function show(string $id)
    // {
    //     //
    // }

    
    // public function edit(string $id)
    // {
    //     //
    // }

   
    // public function update(Request $request, string $id)
    // {
    //     //
    // }

    
    // public function destroy(string $id)
    // {
    //     //
    // }
}
