<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Completed_course extends Model // اسم الكلاس زي اسم الملف
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'grade', // بناءً على الـ migration
    ];

    // --- العلاقات ---

    /**
     * السجل ده يخص مستخدم (طالب) واحد
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * السجل ده يخص كورس واحد
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}