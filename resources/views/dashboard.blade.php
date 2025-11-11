@extends('layouts.naa')

@section('content')
<div class="container py-4">

    {{-- لو فيه رسالة نجاح (زي "تم الحذف") --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- 
    ====================================
    الجزء 1: بيانات الطالب (من التصميم)
    ====================================
    --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">بيانات الطالب</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>الاسم:</strong> 
                    <p class="fs-5">{{ $student->name }}</p>
                </div>
                <div class="col-md-4">
                    <strong>القسم:</strong>
                    <p class="fs-5">{{ $student->profile->department->department_name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4">
                    <strong>المعدل التراكمي (GPA):</strong>
                    <p class="fs-5">{{ $student->profile->gpa }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- 
    ====================================
    الجزء 2: الكورسات المسجلة (مع زرار الحذف)
    ====================================
    --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">الكورسات المسجلة حاليًا</h5>
            <a href="{{ route('registration.index') }}" class="btn btn-primary btn-sm">
                + تسجيل كورسات جديدة
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">كود المادة</th>
                            <th scope="col">اسم المادة</th>
                            <th scope="col">عدد الساعات</th>
                            <th scope="col">إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registrations as $reg)
                        <tr>
                            <td>{{ $reg->course->course_code }}</td>
                            <td>{{ $reg->course->course_name }}</td>
                            <td>{{ $reg->course->credit_hours }}</td>
                            <td>
                                {{-- (الخطوة الجديدة) زرار الحذف --}}
                                <form action="{{ route('registration.destroy', $reg->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                <div class="alert alert-info mb-0">لا يوجد كورسات مسجلة حاليًا.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 
    ====================================
    الجزء 3: الكورسات المكتملة (عرض فقط)
    ====================================
    --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">الكورسات المكتملة</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">كود المادة</th>
                            <th scope="col">اسم المادة</th>
                            <th scope="col">عدد الساعات</th>
                            <th scope="col">التقدير</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($completedCourses as $comp)
                        <tr>
                            <td>{{ $comp->course->course_code }}</td>
                            <td>{{ $comp->course->course_name }}</td>
                            <td>{{ $comp->course->credit_hours }}</td>
                            <td>{{ $comp->grade }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                <div class="alert alert-info mb-0">لم تكمل أي كورسات بعد.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection