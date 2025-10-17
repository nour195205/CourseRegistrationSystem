<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class profiles extends Model
{
    protected $fillable = [
        'user_id',
        'gpa',
        'payment_status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
