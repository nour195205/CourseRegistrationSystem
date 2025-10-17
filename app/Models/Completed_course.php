<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Completed_course extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
    ];
}
