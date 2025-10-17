<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'course_code',
        'course_name',
        'credit_hours',
    ];
    public function prerequisites()
    {
        return $this->belongsToMany(Course::class, 'prerequisites', 'course_id', 'prerequisite_id');
    }
    
    public function isPrerequisiteFor()
    {
        return $this->belongsToMany(Course::class, 'prerequisites', 'prerequisite_id', 'course_id');
    }
}
