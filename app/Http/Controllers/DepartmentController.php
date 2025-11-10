<?php

namespace App\Http\Controllers;

use App\Models\department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        $departments = department::all();
        return view('departments.dashboard', ['departments' => $departments]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $department = new department();
        department::create([
            'department_name' => $request->input('department_name'),
        ]);
        return to_route('departments.dashboard')->with('success', 'department created successfully!');
    }

    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // 1. استخدم الموديل (بحرف كابيتال) ودور بالـ id
        $department = Department::findOrFail($id);
        
        // 2. ابعت القسم ده (الواحد) للـ view
        return view('departments.edit', ['department' => $department]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $department = department::find($id);

        $department->update([
            'department_name' => $request->input('department_name'),
        ]);

        return redirect()->route('departments.dashboard')->with('success', 'Department updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    // 1. (التصليح: استخدم حرف D كابيتال للموديل)
    $department = Department::findOrFail($id);
    
    // 2. امسح القسم
    $department->delete();
    
    // 3. (التصليح: ارجع لصفحة الأقسام اللي اسمها 'departments.index')
    return redirect()->route('departments.dashboard')
                     ->with('success', 'تم حذف القسم بنجاح!');
}
}
