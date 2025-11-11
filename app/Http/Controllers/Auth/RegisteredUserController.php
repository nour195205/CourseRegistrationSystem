<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Department;
use App\Models\profiles; // <-- (ضيف السطر ده)

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        // 1. هات كل الأقسام
        $departments = Department::all();

        // 2. ابعتهم للـ view
        return view('auth.register', compact('departments'));
    }

/**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // 1. (التعديل هنا: ضفنا department_id للـ validation)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'department_id' => ['required', 'exists:departments,id'], // <-- (السطر الجديد)
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. إنشاء اليوزر (زي ما هو، الـ "role" هيتاخد "student" من الداتابيز)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. (الخطوة الجديدة: إنشاء البروفايل)
        // بناءً على طلبك، الداتا هتكون "تلقائية"
        profiles::create([
            'user_id' => $user->id,
            'department_id' => $request->department_id,
            'gpa' => '0.0', // (قيمة افتراضية لسه مفيش GPA)
            'payment_status' => 'unpaid', // (قيمة افتراضية إنه لسه مدفعش)
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
