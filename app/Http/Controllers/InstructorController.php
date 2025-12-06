<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Completed_course;
use Illuminate\Support\Facades\DB; // (مهم عشان الـ Transaction)
use App\Models\Registration;
use Illuminate\Support\Facades\Redirect;

class InstructorController extends Controller
{
    /**
 * عرض الكورسات الخاصة بالدكتور
 */
public function index()
{
    // 1. هات الدكتور (اليوزر) اللي مسجل دخول
    $instructor = Auth::user();

    // 2. هات الكورسات اللي هو بيدرسها (من العلاقة اللي عملناها)
    // withCount('registrations') بتجيب "عدد" الطلاب المسجلين في كل كورس
    $courses = $instructor->coursesTaught()
                         ->withCount('registrations')
                         ->get();

    // 3. ابعت الداتا لـ view (لسه هنعمله)
    return view('instructor.my-courses', compact('courses'));
}

/**
     * عرض الطلاب المسجلين في كورس معين (عشان رصد الدرجات)
     */
    public function showStudents(Course $course)
    {
        // 1. (اختياري) اتأكد إن الدكتور اللي فاتح الصفحة هو دكتور المادة
        if (Auth::id() !== $course->user_id) {
            abort(403);
        }

        // 2. هات كل "التسجيلات" بتاعة الكورس ده
        // with('user.profile') بتجيب بيانات الطالب والبروفايل بتاعه
        $registrations = Registration::where('course_id', $course->id)
                                    ->with('user.profile') 
                                    ->get();

        // 3. ابعت الداتا لـ view (لسه هنعمله)
        return view('instructor.grade-students', compact('course', 'registrations'));
    }

    /**
     * تخزين الدرجات ونقل الطلاب
     */
    // public function storeGrades(Request $request, Course $course)
    // {
    //     // 1. التحقق من الأمان (إن الدكتور هو دكتور المادة)
    //     if (Auth::id() !== $course->user_id) {
    //         abort(403);
    //     }

    //     // 2. التحقق من الداتا (إن الداتا جاية صح)
    //     $request->validate([
    //         'registrations' => 'required|array',
    //         'grades' => 'required|array',
    //         'registrations.*' => 'exists:registrations,id',
    //         'grades.*' => 'required|string', // (ممكن نضيف in:A+,A,B...)
    //     ]);

    //     $registrationIds = $request->registrations;
    //     $grades = $request->grades;

    //     // 3. بدء الـ Transaction (عشان نضمن إن كل حاجة تتنفذ صح)
    //     DB::beginTransaction();

    //     try {
    //         // 4. لف على كل طالب الدكتور بعتله درجة
    //         for ($i = 0; $i < count($registrationIds); $i++) {
                
    //             $regId = $registrationIds[$i];
    //             $grade = $grades[$i];

    //             // 5. هات التسجيل الأصلي
    //             $registration = Registration::find($regId);

    //             // (اتأكد إن التسجيل ده تبع الكورس ده فعلاً)
    //             if ($registration && $registration->course_id == $course->id) {
                    
    //                 // 6. (الخطوة المهمة) أنشئ سجل "اكتمل"
    //                 Completed_course::create([
    //                     'user_id' => $registration->user_id,
    //                     'course_id' => $registration->course_id,
    //                     'grade' => $grade,
    //                 ]);

    //                 // 7. (الخطوة الأهم) امسح التسجيل القديم
    //                 $registration->delete();
    //             }
    //         }

    //         // 8. لو كله تمام، احفظ الشغل
    //         DB::commit();

    //         // 9. رجع الدكتور لصفحة كورساته
    //         return Redirect::route('instructor.courses')->with('success', 'تم رصد الدرجات بنجاح!');

    //     } catch (\Exception $e) {
    //         // 10. لو حصل أي مشكلة، الغي كل حاجة
    //         DB::rollBack();
    //         return Redirect::back()->with('error', 'حدث خطأ أثناء رصد الدرجات.');
    //     }
    // }

    public function storeGrades(Request $request, Course $course)
    {
        // 1. التأكد إن الدكتور هو صاحب المادة
        if (Auth::id() !== $course->user_id) {
            abort(403);
        }

        $request->validate([
            'registrations' => 'required|array',
            'grades' => 'required|array',
        ]);

        $registrationIds = $request->registrations;
        $grades = $request->grades;

        DB::beginTransaction();

        try {
            for ($i = 0; $i < count($registrationIds); $i++) {
                $regId = $registrationIds[$i];
                $grade = $grades[$i];

                // لو الدكتور مختارش درجة للطالب ده، تخطاه
                if (empty($grade)) continue;

                $registration = Registration::with('user.profile')->find($regId);

                if ($registration && $registration->course_id == $course->id) {
                    
                    // أ) تسجيل المادة في السجل الأكاديمي (Completed Courses)
                    Completed_course::create([
                        'user_id' => $registration->user_id,
                        'course_id' => $registration->course_id,
                        'grade' => $grade,
                    ]);

                    // ب) حذف التسجيل الحالي (لأنها خلصت خلاص)
                    $registration->delete();

                    // ج) تحديث الـ GPA للطالب فوراً
                    if ($registration->user->profile) {
                        $registration->user->profile->updateGpa();
                    }
                }
            }

            DB::commit();
            return redirect()->route('instructor.courses')->with('success', 'تم رصد الدرجات وتحديث معدلات الطلاب بنجاح!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء الحفظ: ' . $e->getMessage());
        }
    }
}
