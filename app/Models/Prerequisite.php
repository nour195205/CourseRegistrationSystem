<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prerequisite extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'prerequisite_course_id', // بناءً على الـ migration
    ];

    // (غالبًا مش بنحتاج علاقات هنا، ده جدول وسيط)
}