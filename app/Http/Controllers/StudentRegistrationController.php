<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use Illuminate\Support\Facades\Redirect;
use App\Models\Registration;

class StudentRegistrationController extends Controller
{
    public function index()
    {
        // هات الطالب اللي مسجل دخول
        $student = Auth::user();
        
        // هات البروفايل بتاعه (عشان نجيب القسم والـ GPA)
        $profile = $student->profile ?? 0;

        if ($profile === 0) {
            return redirect()->route('dashboard')->with('error', 'بياناتك الأكاديمية غير مكتملة.');
        }

        // --- 1. فلترة الكورسات (الجزء الصعب) ---

        // (أ) هات الكورسات اللي الطالب خلصها أو مسجلها عشان نخفيها
        $completedCourseIds = $student->completedCourses()->pluck('course_id')->toArray();
        $registeredCourseIds = $student->registrations()->pluck('course_id')->toArray();
        $excludeIds = array_merge($completedCourseIds, $registeredCourseIds);

        // (ب) هات القسم بتاع الطالب
        $studentDeptId = $profile->department_id;

        // (ج) هات الكورسات المتاحة (بناءً على قسم الطالب)
        $coursesQuery = Course::where(function ($query) use ($studentDeptId) {
            // 1. الكورسات اللي "تابعة" لقسمه
            $query->where('department_id', $studentDeptId)
                  // 2. أو الكورسات اللي "مسموح" لقسمه ياخدها
                  ->orWhereHas('allowedDepartments', function ($q) use ($studentDeptId) {
                      $q->where('departments.id', $studentDeptId);
                  });
        })
        ->whereNotIn('id', $excludeIds) // (شيل اللي خلصها أو مسجلها)
        ->with('prerequisites'); // (هات المتطلبات بتاعتهم)

        
        // (د) الفلترة النهائية (بناءً على المتطلبات)
        $availableCourses = $coursesQuery->get()->filter(function ($course) use ($completedCourseIds) {
            
            // هات الـ IDs بتاعة متطلبات الكورس ده
            $prereqIds = $course->prerequisites->pluck('prerequisite_course_id')->toArray();
            
            // لو الكورس معندوش متطلبات، يبقى متاح
            if (empty($prereqIds)) {
                return true; 
            }
            
            // شوف هل الطالب خلص "كل" المتطلبات دي ولا لأ
            $diff = array_diff($prereqIds, $completedCourseIds);
            return empty($diff); // لو الفرق "فاضي"، يبقى الطالب خلصهم كلهم
        });


        // --- 2. حساب الساعات ---
        
        // (أ) هات الحد الأقصى للساعات (من الدالة اللي عملناها)
        $maxHours = $profile->getMaxCreditHours();

        // (ب) احسب الساعات اللي الطالب مسجلها حاليًا
        $currentHours = $student->registrations()->with('course')->get()->sum(function($reg) {
            return (float) $reg->course->credit_hours;
        });

        // (ج) احسب الساعات المتبقية
        $remainingHours = $maxHours - $currentHours;


        // --- 3. إرسال البيانات للـ View ---
        
        // (هنعمل الـ view ده في الخطوة الجاية)
        return view('student.register_courses', compact(
            'availableCourses', 
            'maxHours', 
            'currentHours', 
            'remainingHours'
        ));
    }

    /**
     * الدالة دي بتخزن الكورس اللي الطالب بيسجله
     */
    public function store(Request $request)
    {
        // --- 0. التحقق من الطلب ---
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);
        
        $courseId = $request->course_id;
        $student = Auth::user();
        $profile = $student->profile;
        $course = Course::with('prerequisites')->findOrFail($courseId);

        // ===================================
        // --- 1. التحقق من شروط التسجيل ---
        // ===================================

        // (أ) التحقق من الدفع
        if ($profile->payment_status !== 'paid') {
            return Redirect::back()->with('error', 'يجب دفع المصروفات أولاً قبل التسجيل.');
        }

        // (ب) التحقق من الحد الأقصى للساعات (بناءً على الـ GPA)
        $maxHours = $profile->getMaxCreditHours();
        $currentHours = $student->registrations()->with('course')->get()->sum(function($reg) {
            return (float) $reg->course->credit_hours;
        });
        $newHours = (float) $course->credit_hours;

        if (($currentHours + $newHours) > $maxHours) {
            return Redirect::back()->with('error', 'لقد تجاوزت الحد الأقصى للساعات المسموحة (' . $maxHours . ' ساعة).');
        }

        // (ج) التحقق من المتطلبات
        $completedCourseIds = $student->completedCourses()->pluck('course_id')->toArray();
        $prereqIds = $course->prerequisites->pluck('prerequisite_course_id')->toArray();
        
        // (لو الفرق بين المتطلبات واللي خلصه "مش فاضي"، يبقى فيه متطلب ناقص)
        if (!empty(array_diff($prereqIds, $completedCourseIds))) {
            return Redirect::back()->with('error', 'لم تجتاز متطلبات هذا الكورس.');
        }

        // (د) التحقق (الأمني) من إنه مسجلهوش أو خلصه قبل كده
        $isRegistered = $student->registrations()->where('course_id', $courseId)->exists();
        if ($isRegistered) {
            return Redirect::back()->with('error', 'أنت مسجل هذا الكورس بالفعل.');
        }


        // ===================================
        // --- 2. تنفيذ التسجيل ---
        // ===================================

        // (ملحوظة: إنت محتاج تحدد الترم والسنة، أنا هثبتهم مؤقتًا)
        Registration::create([
            'user_id' => $student->id,
            'course_id' => $courseId,
            'semester' => 'Fall', // (ده المفروض ييجي من الإعدادات)
            'academic_year' => '2025-2026', // (ده المفروض ييجي من الإعدادات)
        ]);

        // --- 3. رجعه لنفس الصفحة مع رسالة نجاح ---
        return Redirect::back()->with('success', 'تم تسجيل الكورس (' . $course->course_name . ') بنجاح!');
    }

    /**
     * الدالة دي بتحذف التسجيل (Drop Course)
     */
    public function destroy(Registration $registration) // (هنستخدم Route Model Binding)
    {
        // ===================================
        // --- 1. التحقق من الأمان ---
        // ===================================

        // (نتأكد إن الطالب اللي بيحاول يمسح التسجيل ده هو "صاحبه")
        if (Auth::id() !== $registration->user_id) {
            // (لو مش هو، وقفه)
            abort(403, 'Unauthorized action.');
        }

        // (ممكن نضيف شرط يمنع الحذف لو ميعاد الحذف والإضافة خلص)
        // ... (هنسيبها للمستقبل) ...


        // ===================================
        // --- 2. تنفيذ الحذف ---
        // ===================================
        $registration->delete();

        // --- 3. رجعه للداشبورد مع رسالة نجاح ---
        return Redirect::route('dashboard')->with('success', 'تم حذف الكورس من تسجيلك بنجاح.');
    }
}
