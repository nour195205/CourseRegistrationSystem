<?php

namespace App\Http\Controllers;
use App\Models\Course;
use App\Models\Department;
use Illuminate\Support\Facades\DB; // <-- 1. مهم عشان الـ Transaction
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Models\User;

class CourseController extends Controller
{
    
    public function dashboard()
    {
       //
        $courses = Course::all();
        return view('courses.dashboard', ['courses' => $courses]);
    }
        
    // public function create()
    // {
    //    $courses = Course::all();
    //    $departments = Department::all();
    //     return view('courses.create', ['courses' => $courses , 'departments' => $departments]);
    // }

    /**
    * Show the form for creating a new resource.
    */
    public function create()
    {
        $departments = Department::all();
        $courses = Course::all();

        // (السطر الجديد اللي ضفناه)
        $instructors = User::where('role', 'instructor')->get();

        // (التعديل هنا: ضفنا $instructors)
        return view('courses.create', compact('departments', 'courses', 'instructors'));
    }

    
    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     // 1. التحقق من صحة كل البيانات اللي جاية
    //     $validatedData = $request->validate([
    //         // بيانات الكورس الأساسية
    //         'course_code' => 'required|string|unique:courses|max:255',
    //         'course_name' => 'required|string|max:255',
    //         'credit_hours' => 'required|string',
    //         'discription' => 'required|string',
    //         'department_id' => 'required|exists:departments,id',
            
    //         // الأقسام المسموحة (لازم تكون array وممكن تكون فاضية)
    //         'allowed_departments' => 'nullable|array',
    //         'allowed_departments.*' => 'exists:departments,id', // اتأكد إن كل id فيهم موجود
            
    //         // المتطلبات (لازم تكون array وممكن تكون فاضية)
    //         'prerequisites' => 'nullable|array',
    //         'prerequisites.*' => 'exists:courses,id', // اتأكد إن كل id فيهم موجود
    //     ]);

    //     // 2. بدء الـ Transaction
    //     DB::beginTransaction();

    //     try {
    //         // ============ الخطوة 1: إنشاء الكورس الأساسي ============
    //         $course = Course::create([
    //             'course_code' => $validatedData['course_code'],
    //             'course_name' => $validatedData['course_name'],
    //             'credit_hours' => $validatedData['credit_hours'],
    //             'department_id' => $validatedData['department_id'],
    //             'discription' => $validatedData['discription'],
    //         ]);

    //         // ============ الخطوة 2: إضافة الأقسام المسموحة (في الجدول الوسيط) ============
    //         if ($request->has('allowed_departments')) {
    //             // استخدم العلاقة اللي عملناها في الموديل
    //             $course->allowedDepartments()->attach($validatedData['allowed_departments']);
    //         }

    //         // ============ الخطوة 3: إضافة المتطلبات (في الجدول الوسيط) ============
    //         if ($request->has('prerequisites')) {
    //             // استخدم العلاقة اللي في الموديل (اللي اسمها prerequisites)
    //             $course->prerequisites()->attach($validatedData['prerequisites']);
    //         }

    //         // ============ الخطوة 4: لو كله تمام، احفظ الشغل ============
    //         DB::commit();
            
    //         // 3. إعادة التوجيه لصفحة الـ index مع رسالة نجاح
    //         return Redirect::route('courses.dashboard')->with('success', 'تم إضافة الكورس بنجاح!');

    //     } catch (\Exception $e) {
    //         // ============ الخطوة 5: لو حصل أي مشكلة، الغي كل حاجة ============
    //         DB::rollBack();
            
    //         // 4. رجع اليوزر لنفس الصفحة مع رسالة خطأ
    //         // withInput() عشان الفورم متفضاش
    //         return Redirect::back()->with('error', 'حدث خطأ أثناء إضافة الكورس.')->withInput();
    //     }
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. التحقق من صحة كل البيانات اللي جاية
        $validatedData = $request->validate([
            'course_code' => 'required|string|unique:courses|max:255',
            'course_name' => 'required|string|max:255',
            'credit_hours' => 'required|string',
            'discription' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            
            // (السطر الجديد 1: اتأكد إن الـ user_id موجود وإنه instructor)
            'user_id' => 'required|exists:users,id,role,instructor',
            
            'allowed_departments' => 'nullable|array',
            'allowed_departments.*' => 'exists:departments,id',
            
            'prerequisites' => 'nullable|array',
            'prerequisites.*' => 'exists:courses,id',
        ]);

        // 2. بدء الـ Transaction
        DB::beginTransaction();

        try {
            // ============ الخطوة 1: إنشاء الكورس الأساسي ============
            $course = Course::create([
                'course_code' => $validatedData['course_code'],
                'course_name' => $validatedData['course_name'],
                'credit_hours' => $validatedData['credit_hours'],
                'department_id' => $validatedData['department_id'],
                'discription' => $validatedData['discription'],
                'user_id' => $validatedData['user_id'], // <-- (السطر الجديد 2: خزن الدكتور)
            ]);

            // ============ الخطوة 2: إضافة الأقسام المسموحة ============
            if ($request->has('allowed_departments')) {
                $course->allowedDepartments()->attach($validatedData['allowed_departments']);
            }

            // ============ الخطوة 3: إضافة المتطلبات ============
            if ($request->has('prerequisites')) {
                $course->prerequisites()->attach($validatedData['prerequisites']);
            }

            // ============ الخطوة 4: لو كله تمام، احفظ الشغل ============
            DB::commit();
            
            return Redirect::route('courses.index')->with('success', 'تم إضافة الكورس بنجاح!');

        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('error', 'حدث خطأ أثناء إضافة الكورس.')->withInput();
        }
    }


    
    // public function show(string $id)
    // {
    //     //
    // }

    
    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Course $course) // (هنستخدم Route Model Binding)
    // {
    //     // 1. هات كل الأقسام
    //     $departments = Department::all();
        
    //     // 2. هات كل الكورسات (ما عدا الكورس ده، عشان مينفعش يبقى متطلب لنفسه)
    //     $allCourses = Course::where('id', '!=', $course->id)->get();
        
    //     // 3. هات الـ IDs بتاعة الأقسام المسموحة (اللي عملنالها check قبل كده)
    //     $selectedAllowedDepts = $course->allowedDepartments()->pluck('departments.id')->toArray();
    //     // 4. هات الـ IDs بتاعة المتطلبات (اللي عملنالها check قبل كده)
    //     $selectedPrerequisites = $course->prerequisites()->pluck('prerequisite_course_id')->toArray();

        
    //     // 5. ابعت كل الداتا دي للـ view
    //     return view('courses.edit', compact(
    //         'course', 
    //         'departments', 
    //         'allCourses', 
    //         'selectedAllowedDepts', 
    //         'selectedPrerequisites'
    //     ));
    // }


    public function edit(Course $course)
    {
        $departments = Department::all();
        $allCourses = Course::where('id', '!=', $course->id)->get();
        
        // (السطر الجديد اللي ضفناه)
        $instructors = User::where('role', 'instructor')->get();

        $selectedAllowedDepts = $course->allowedDepartments()->pluck('departments.id')->toArray();
        $selectedPrerequisites = $course->prerequisites()->pluck('prerequisite_course_id')->toArray();

        
        return view('courses.edit', compact(
            'course', 
            'departments', 
            'allCourses', 
            'instructors', // (ضفناها هنا)
            'selectedAllowedDepts', 
            'selectedPrerequisites'
        ));
    }
   
   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        // 1. التحقق من صحة البيانات (زي store بس بنستثني الكود الحالي من unique)
        $validatedData = $request->validate([
            'course_code' => 'required|string|max:255|unique:courses,course_code,' . $course->id,
            'course_name' => 'required|string|max:255',
            'credit_hours' => 'required|string',
            'discription' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            
            // (السطر الجديد 1: اتأكد إن الـ user_id موجود وإنه instructor)
            'user_id' => 'required|exists:users,id,role,instructor',

            'allowed_departments' => 'nullable|array',
            'allowed_departments.*' => 'exists:departments,id',
            
            'prerequisites' => 'nullable|array',
            'prerequisites.*' => 'exists:courses,id',
        ]);

        // 2. بدء الـ Transaction
        DB::beginTransaction();

        try {
            // ============ الخطوة 1: تحديث الكورس الأساسي ============
            $course->update([
                'course_code' => $validatedData['course_code'],
                'course_name' => $validatedData['course_name'],
                'credit_hours' => $validatedData['credit_hours'],
                'department_id' => $validatedData['department_id'],
                'discription' => $validatedData['discription'],
                'user_id' => $validatedData['user_id'], // <-- (السطر الجديد 2: خزن الدكتور)
            ]);

            // ============ الخطوة 2: مزامنة الأقسام المسموحة ============
            $course->allowedDepartments()->sync($validatedData['allowed_departments'] ?? []);

            // ============ الخطوة 3: مزامنة المتطلبات ============
            $course->prerequisites()->sync($validatedData['prerequisites'] ?? []);

            // ============ الخطوة 4: لو كله تمام، احفظ الشغل ============
            DB::commit();
            
            return Redirect::route('courses.index')->with('success', 'تم تعديل الكورس بنجاح!');

        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('error', 'حدث خطأ أثناء تعديل الكورس.')->withInput();
        }
    }

    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        try {
            // 1. امسح الكورس
            $course->delete();
            
            // 2. ارجع لصفحة الـ index مع رسالة نجاح
            return Redirect::route('courses.index')->with('success', 'تم حذف الكورس بنجاح.');

        } catch (\Exception $e) {
            // 3. لو حصل مشكلة (زي إن الكورس ده متسجل في حتة تانية)
            return Redirect::route('courses.index')->with('error', 'لا يمكن حذف هذا الكورس لوجود طلاب مسجلين فيه أو كونه متطلب لمواد أخرى.');
        }
    }
}
