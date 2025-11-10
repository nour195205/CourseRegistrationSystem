@extends('layouts.naa')

{{-- 1. الهيدر --}}
@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('لوحة التحكم') }}
    </h2>
@endsection

{{-- 2. المحتوى --}}
@section('content')
<div class="container py-4">

   
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">بيانات الطالب</h5>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- 
                    ملحوظة: هنحتاج نجيب البيانات دي من الكنترولر 
                    $user (من auth) و $profile (من الـ relation)
                --}}
                <div class="col-md-4">
                    <strong>الاسم:</strong> 
                    <p class="fs-5">{{ auth()->user()->name ?? 'Nour Ashour' }}</p>
                </div>
                <div class="col-md-4">
                    <strong>الكود:</strong>
                    <p class="fs-5">{{ auth()->user()->profile->student_id ?? '4432' }}</p>
                </div>
                <div class="col-md-4">
                    <strong>المعدل التراكمي (GPA):</strong>
                    <p class="fs-5">{{ auth()->user()->profile->gpa ?? '3.35' }}</p>
                </div>
            </div>
        </div>
    </div>

   
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">الكورسات المسجلة حاليًا</h5>
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
                        {{-- 
                            ملحوظة: هنحتاج نجيب $registrations من الكنترولر 
                        --}}
                        @forelse ($registrations ?? [] as $reg)
                        <tr>
                            <td>{{ $reg->course->code ?? 'CS101' }}</td>
                            <td>{{ $reg->course->name ?? 'Programming 1' }}</td>
                            <td>{{ $reg->course->credits ?? 3 }}</td>
                            <td>
                                {{-- ده المفروض يكون فورم بيبعت لـ route بتاع الحذف --}}
                                <form method="POST" action="#">
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
                            <th scope="col">إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- 
                            ملحوظة: هنحتاج نجيب $completedCourses من الكنترولر 
                        --}}
                        @forelse ($completedCourses ?? [] as $comp)
                        <tr>
                            <td>{{ $comp->course->code ?? 'GEN100' }}</td>
                            <td>{{ $comp->course->name ?? 'Ethics' }}</td>
                            <td>{{ $comp->course->credits ?? 2 }}</td>
                            <td>
                                <button class="btn btn-secondary btn-sm" disabled>حذف</button>
                            </td>
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