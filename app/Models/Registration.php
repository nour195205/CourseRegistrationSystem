<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'semester',
        'academic_year',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
