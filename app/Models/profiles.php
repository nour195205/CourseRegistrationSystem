<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class profiles extends Model // اسم الكلاس زي اسم الملف
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',    
        'gpa',
        'payment_status', // بناءً على الـ migration
    ];

    // --- العلاقات ---

    /**
     * البروفايل ده يخص مستخدم واحد
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * دالة مساعدة لتحويل التقدير لنقاط
     * (عدّل النقط دي حسب نظام جامعتك)
     */
    protected function getGradePoints($grade)
    {
        $points = [
            'A+' => 4.0, 'A'  => 3.7, 'A-' => 3.7,
            'B+' => 3.3, 'B'  => 3.0, 'B-' => 3.0,
            'C+' => 2.7, 'C'  => 2.4, 'C-' => 2.4,
            'D+' => 2.2, 'D'  => 2.0, 'D-' => 2.0,
            'F'  => 0.0,
        ];
        return $points[$grade] ?? 0;
    }

    /**
     * الدالة الأساسية لحساب الـ GPA
     */
    public function calculateGpa()
    {
        // 1. هات اليوزر (الطالب) صاحب البروفايل ده
        $user = $this->user;

        // 2. هات كل الكورسات اللي اليوزر ده خلصها
        $completedCourses = $user->completedCourses()->with('course')->get();

        if ($completedCourses->isEmpty()) {
            return 0; // لو مخلصش حاجة
        }

        $totalPoints = 0;
        $totalHours = 0;

        foreach ($completedCourses as $completed) {
            $hours = (float) $completed->course->credit_hours;
            $points = $this->getGradePoints($completed->grade);
            $totalPoints += $points * $hours;
            $totalHours += $hours;
        }

        if ($totalHours == 0) {
            return 0;
        }

        // 3. رجع الناتج
        return round($totalPoints / $totalHours, 2);
    }

    /**
     * الدالة دي بتشغل الحسبة وبتخزن الناتج في الداتابيز
     */
    public function updateGpa()
    {
        // 1. شغل الدالة اللي فاتت عشان تجيب الرقم
        $calculatedGpa = $this->calculateGpa();

        // 2. خزّن الرقم ده في العمود (gpa) بتاع البروفايل ده
        $this->update(['gpa' => $calculatedGpa]);
    }

    /**
     * الدالة دي بتحدد الحد الأقصى للساعات بناءً على الـ GPA المتخزن
     */
    public function getMaxCreditHours()
    {
        // 1. هات الـ GPA المتخزن في الداتابيز
        $gpa = (float) $this->gpa;

        // 2. طبق الشروط اللي إنت قلتها
        if ($gpa > 3.7) {
            return 21; // 21 ساعة
        }
        
        if ($gpa < 1.8 && $gpa > 0) { // (لو هو بين 0 و 1.8)
            return 15; // 15 ساعة
        }

        // 3. لو هو 0 (طالب جديد) أو بين 1.8 و 3.7
        return 18; // 18 ساعة
    }
}