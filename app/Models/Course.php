<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_code',    // بناءً على الـ migration
        'course_name',    // بناءً على الـ migration
        'credit_hours',   // بناءً على الـ migration
        'department_id',  // بناءً على الـ migration
        'discription',    // بناءً على الـ migration
    ];

    /**
     * الكورس ده يتبع قسم واحد
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * الكورس ده مسجل فيه طلاب كتير (حاليًا)
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * الكورس ده أكمله طلاب كتير (في الماضي)
     */
    public function completedByUsers()
    {
        return $this->hasMany(Completed_course::class);
    }

    /**
     * الكورسات المطلوبة (المتطلبات) عشان تاخد الكورس ده
     */
    public function prerequisites()
    {
        return $this->belongsToMany(Course::class, 'prerequisites', 'course_id', 'prerequisite_course_id');
    }

    /**
     * الكورسات اللي الكورس ده يعتبر متطلب ليها
     */
    public function prerequisiteFor()
    {
        return $this->belongsToMany(Course::class, 'prerequisites', 'prerequisite_course_id', 'course_id');
    }

    /**
     * العلاقة دي بتجيب (الأقسام الإضافية) "المسموح" ليها تاخد الكورس
     * (اللي اخترناهم بالـ Checkbox)
     * * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function allowedDepartments()
    {
        // (اسم الجدول الوسيط اللي لسه عاملينه)
        return $this->belongsToMany(Department::class, 'allowed_departments');
    }
}