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
        // 1. ابدأ الكويري، واطلب منه يجيب العلاقات (عشان نعرض القسم)
        $query = User::with('profile.department');

        // 2. لو فيه بحث (بالاسم أو الإيميل)
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
        }

        // 3. هات النتيجة
        $users = $query->paginate(10);

        // 4. ابعت الداتا لـ view (لسه هنعمله)
        // (المسار ده معناه: admin/users/index.blade.php)
        return view('admin.users.index', compact('users'));
    }

    
    public function create()
    {
        // هات الأقسام عشان نعرضها في القايمة (لو هنضيف طالب)
        $departments = Department::all();
        
        return view('admin.users.create', compact('departments'));
    }

    
    public function store(Request $request)
    {
        // 1. التحقق من البيانات
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,instructor,student'], // (لازم نختار دور)
            
            // (القسم مطلوب فقط لو الدور "طالب")
            'department_id' => ['required_if:role,student', 'nullable', 'exists:departments,id'],
        ]);

        // 2. إنشاء المستخدم (User)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // 3. لو المستخدم "طالب"، لازم نعمله بروفايل
        if ($request->role === 'student') {
            profiles::create([
                'user_id' => $user->id,
                'department_id' => $request->department_id,
                'gpa' => '0.0',
                'payment_status' => 'unpaid',
            ]);
        }

        // 4. الرجوع لصفحة المستخدمين
        return redirect()->route('admin.users.index')
                         ->with('success', 'تم إضافة المستخدم بنجاح!');
    }

    
    public function show(string $id)
    {
        // (غالبًا مش هنحتاجها)
    }

    
    /**
     * عرض صفحة تعديل المستخدم
     */
    public function edit(User $user)
    {
        // 1. هات الأقسام (عشان لو هنعدل قسم الطالب)
        $departments = Department::all();
        
        // 2. ابعت اليوزر والأقسام للـ view
        return view('admin.users.edit', compact('user', 'departments'));
    }

    /**
     * تحديث بيانات المستخدم
     */
    public function update(Request $request, User $user)
    {
        // 1. التحقق من البيانات
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // (unique بس نستثني اليوزر الحالي عشان ميديناش إيرور لو مغيرش الإيميل)
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,instructor,student'],
            'department_id' => ['required_if:role,student', 'nullable', 'exists:departments,id'],
            // (الباسوورد اختياري في التعديل)
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. تجهيز الداتا للتحديث
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // 3. تحديث الباسوورد (فقط لو اتكتبت)
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        // 4. تنفيذ التحديث لجدول users
        $user->update($userData);

        // 5. التعامل مع البروفايل (لو اليوزر طالب)
        if ($request->role === 'student') {
            if ($user->profile) {
                // لو عنده بروفايل، حدث القسم
                $user->profile()->update([
                    'department_id' => $request->department_id,
                ]);
            } else {
                // لو معندوش (مثلاً كان دكتور وحولناه طالب)، اعمله بروفايل جديد
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
        // (حماية بسيطة: ممنوع الأدمن يمسح نفسه وهو مسجل دخول)
        if (auth()->id() == $user->id) {
            return redirect()->back()->with('error', 'لا يمكنك حذف حسابك الشخصي أثناء استخدامه.');
        }

        $user->delete(); // (لو طالب، البروفايل هيتمسح أوتوماتيك عشان cascade)

        return redirect()->route('admin.users.index')
                         ->with('success', 'تم حذف المستخدم بنجاح');
    }

    
    
}