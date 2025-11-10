<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class profiles extends Model // اسم الكلاس زي اسم الملف
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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
}