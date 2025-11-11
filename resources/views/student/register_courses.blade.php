@extends('layouts.naa')

@section('content')
<div class="container py-4">

    {{-- 
    ====================================
    الجزء 1: ملخص الساعات (بناءً على الـ GPA)
    ====================================
    --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">ملخص الساعات المعتمدة</h5>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-4">
                    <h6 class="text-muted">الحد الأقصى المسموح (GPA)</h6>
                    <h4 class="fw-bold">{{ $maxHours }}</h4>
                </div>
                <div class="col-4">
                    <h6 class="text-muted">الساعات المسجلة حاليًا</h6>
                    <h4 class="fw-bold">{{ $currentHours }}</h4>
                </div>
                <div class="col-4">
                    <h6 class="text-muted">الساعات المتبقية</h6>
                    <h4 class="fw-bold text-success">{{ $remainingHours }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- 
    ====================================
    الجزء 2: الكورسات المتاحة (بعد الفلترة)
    ====================================
    --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">الكورسات المتاحة للتسجيل</h5>
        </div>
        <div class="card-body">
            
            {{-- لو فيه أي رسائل خطأ (زي "الساعات تعدت الحد") --}}
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">كود الكورس</th>
                            <th scope="col">اسم الكورس</th>
                            <th scope="col">الساعات</th>
                            <th scope="col">المتطلبات</th>
                            <th scope="col">تسجيل</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($availableCourses as $course)
                            <tr>
                                <td>{{ $course->course_code }}</td>
                                <td>{{ $course->course_name }}</td>
                                <td>{{ $course->credit_hours }}</td>
                                <td>
                                    {{-- عرض المتطلبات (لو موجودة) --}}
                                    @foreach($course->prerequisites as $prereq)
                                        <span class="badge bg-secondary">{{ $prereq->course_code }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    {{-- 
                                      هنا الشرط الأخير:
                                      هل الساعات المتبقية كافية لتسجيل الكورس ده؟
                                    --}}
                                    @if ($remainingHours >= $course->credit_hours)
                                        <form action="{{ route('registration.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                                            <button type="submit" class="btn btn-success btn-sm">تسجيل</button>
                                        </form>
                                    @else
                                        {{-- لو الساعات مش كافية، اقفل الزرار --}}
                                        <button class="btn btn-secondary btn-sm" disabled title="لا توجد ساعات كافية">تسجيل</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="alert alert-info mb-0">
                                        لا توجد كورسات متاحة لك للتسجيل حاليًا. (إما أنك أنهيت المتطلبات أو أنك مسجلها بالفعل).
                                    </div>
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