<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // بناءً على الـ migration
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- العلاقات ---

    /**
     * كل مستخدم له بروفايل واحد
     */
    public function profile()
    {
        return $this->hasOne(profiles::class); // هنستخدم اسم الموديل (profiles)
    }

    /**
     * كل مستخدم (طالب) له كورسات مسجلها حاليًا
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * كل مستخدم (طالب) له كورسات اكتملت
     */
    public function completedCourses()
    {
        return $this->hasMany(Completed_course::class); // هنستخدم اسم الموديل (Completed_course)
    }

    /**
     * الكورسات اللي الدكتور ده بيدرسها
     */
    public function coursesTaught()
    {
        return $this->hasMany(Course::class, 'user_id');
    }
}