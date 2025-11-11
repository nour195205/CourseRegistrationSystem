@extends('layouts.naa')

@section('content')
<div class="container py-4">

    {{-- 
    ====================================
    الجزء 1: بيانات الكورس
    ====================================
    --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                رصد درجات كورس: {{ $course->course_name }} ({{ $course->course_code }})
            </h5>
        </div>
        <div class="card-body">

            {{-- 
            ====================================
            الجزء 2: الفورم (أهم جزء)
            ====================================
            --}}
            
            {{-- 
              الفورم دي هتبعت (POST) الداتا (الدرجات) لراوت لسه هنعمله 
              اسمه instructor.grade.store
            --}}
            <form action="{{ route('instructor.grade.store', $course) }}" method="POST">
                @csrf

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>اسم الطالب</th>
                                <th>البريد الإلكتروني</th>
                                <th>القسم</th>
                                <th style="width: 150px;">الدرجة (Grade)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($registrations as $reg)
                            <tr>
                                {{-- اسم الطالب --}}
                                <td>{{ $reg->user->name }}</td>
                                
                                {{-- إيميل الطالب --}}
                                <td>{{ $reg->user->email }}</td>
                                
                                {{-- قسم الطالب --}}
                                <td>{{ $reg->user->profile->department->department_name ?? 'N/A' }}</td>
                                
                                {{-- 
                                  ده حقل الدرجات 
                                  الـ name عبارة عن array عشان يبعت كل الدرجات مرة واحدة
                                --}}
                                <td>
                                    {{-- (بنب الما id بتاع التسجيل عشان نعرف الطالب ده مين) --}}
                                    <input type="hidden" name="registrations[]" value="{{ $reg->id }}">
                                    
                                    <select name="grades[]" class="form-select form-select-sm" required>
                                        <option value="" disabled selected>-- اختر التقدير --</option>
                                        <option value="A+">A+</option>
                                        <option value="A">A</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B">B</option>
                                        <option value="B-">B-</option>
                                        <option value="C+">C+</option>
                                        <option value="C">C</option>
                                        <option value="C-">C-</option>
                                        <option value="D+">D+</option>
                                        <option value="D">D</option>
                                        <option value="D-">D-</option>
                                        <option value="F">F</option>
                                    </select>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center alert alert-info">
                                    لا يوجد طلاب مسجلين في هذا الكورس حاليًا.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- لو مفيش طلاب، الزرار ده هيختفي --}}
                @if($registrations->isNotEmpty())
                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-success">
                        حفظ واعتماد الدرجات
                    </button>
                </div>
                @endif

            </form>

        </div>
    </div>
</div>
@endsection