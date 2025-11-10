<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_name', // بناءً على الـ migration
    ];

    // --- العلاقات ---

    /**
     * القسم الواحد يحتوي على كورسات كتير
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * العلاقة دي بتجيب الكورسات (بتاعة الأقسام التانية)
     * اللي مسموح للقسم ده ياخدها
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function allowedCourses()
    {
        // (اسم الجدول الوسيط اللي لسه عاملينه)
        return $this->belongsToMany(Course::class, 'allowed_departments');
    }
}