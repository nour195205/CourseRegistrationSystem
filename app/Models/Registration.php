<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'semester',      // بناءً على الـ migration
        'academic_year', // بناءً على الـ migration
    ];

    // --- العلاقات ---

    /**
     * التسجيل ده يخص مستخدم (طالب) واحد
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * التسجيل ده يخص كورس واحد
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}