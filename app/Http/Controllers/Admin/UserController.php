<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Department;
use App\Models\profiles; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    
    public function index(Request $request)
    {
        $query = User::with('profile.department');

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
        }

        $users = $query->paginate(10);


        return view('admin.users.index', compact('users'));
    }

    
    public function create()
    {
        $departments = Department::all();
        
        return view('admin.users.create', compact('departments'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,instructor,student'], // (لازم نختار دور)
            
            'department_id' => ['required_if:role,student', 'nullable', 'exists:departments,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($request->role === 'student') {
            profiles::create([
                'user_id' => $user->id,
                'department_id' => $request->department_id,
                'gpa' => '0.0',
                'payment_status' => 'unpaid',
            ]);
        }

        return redirect()->route('admin.users.index')
                         ->with('success', 'تم إضافة المستخدم بنجاح!');
    }

    /**
     * عرض صفحة تعديل المستخدم
     */
    public function edit(User $user)
    {
        $departments = Department::all();
        
        return view('admin.users.edit', compact('user', 'departments'));
    }

    /**
     * تحديث بيانات المستخدم
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,instructor,student'],
            'department_id' => ['required_if:role,student', 'nullable', 'exists:departments,id'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        if ($request->role === 'student') {
            if ($user->profile) {
                $user->profile()->update([
                    'department_id' => $request->department_id,
                ]);
            } else {
                profiles::create([
                    'user_id' => $user->id,
                    'department_id' => $request->department_id,
                    'gpa' => '0.0',
                    'payment_status' => 'unpaid',
                ]);
            }
        }

        return redirect()->route('admin.users.index')
                         ->with('success', 'تم تحديث بيانات المستخدم بنجاح');
    }

    /**
     * حذف المستخدم
     */
    public function destroy(User $user)
    {
        if (auth()->id() == $user->id) {
            return redirect()->back()->with('error', 'لا يمكنك حذف حسابك الشخصي أثناء استخدامه.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'تم حذف المستخدم بنجاح');
    }

    
    
}