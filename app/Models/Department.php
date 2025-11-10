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
}