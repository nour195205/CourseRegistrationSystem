@extends('layouts.naa')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">إضافة كورس جديد</h5>
                </div>
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('courses.store') }}">
                        @csrf
                        
                        <fieldset class="border p-3 mb-3">
                            <legend class="float-none w-auto px-3 h6">بيانات الكورس الأساسية</legend>

                            <div class="mb-3">
                                <label for="course_code" class="form-label">كود الكورس</label>
                                <input type="text" class="form-control @error('course_code') is-invalid @enderror" 
                                       id="course_code" name="course_code" value="{{ old('course_code') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="course_name" class="form-label">اسم الكورس</label>
                                <input type="text" class="form-control @error('course_name') is-invalid @enderror" 
                                       id="course_name" name="course_name" value="{{ old('course_name') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="credit_hours" class="form-label">عدد الساعات</label>
                                <input type="number" class="form-control @error('credit_hours') is-invalid @enderror" 
                                       id="credit_hours" name="credit_hours" value="{{ old('credit_hours') }}" required min="1">
                            </div>

                            <div class="mb-3">
                                <label for="department_id" class="form-label">القسم الأساسي (المالك للكورس)</label>
                                <select class="form-select @error('department_id') is-invalid @enderror" 
                                        id="department_id" name="department_id" required>
                                    <option value="" disabled selected>-- اختر القسم --</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->department_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 
                            ====================================
                            (القايمة الجديدة) اختيار الدكتور 
                            ====================================
                            --}}
                            <div class="mb-3">
                                <label for="user_id" class="form-label">دكتور المادة (Instructor)</label>
                                <select class="form-select @error('user_id') is-invalid @enderror" 
                                        id="user_id" name="user_id" required>
                                    <option value="" disabled selected>-- اختر الدكتور --</option>
                                    @foreach ($instructors as $instructor)
                                        <option value="{{ $instructor->id }}" {{ old('user_id') == $instructor->id ? 'selected' : '' }}>
                                            {{ $instructor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="discription" class="form-label">الوصف</label>
                                <textarea class="form-control @error('discription') is-invalid @enderror" 
                                          id="discription" name="discription" rows="3">{{ old('discription') }}</textarea>
                            </div>
                        </fieldset>

                        <fieldset class="border p-3 mb-3">
                            <legend class="float-none w-auto px-3 h6">الأقسام الإضافية المسموح لها</legend>
                            <div style="max-height: 200px; overflow-y: auto;">
                                @foreach ($departments as $department)
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="allowed_departments[]" 
                                               value="{{ $department->id }}" 
                                               id="dept-{{ $department->id }}">
                                        <label class="form-check-label" for="dept-{{ $department->id }}">
                                            {{ $department->department_name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </fieldset>

                        <fieldset class="border p-3 mb-3">
                            <legend class="float-none w-auto px-3 h6">المتطلبات السابقة</legend>
                            <div style="max-height: 200px; overflow-y: auto;">
                                @foreach ($courses as $course)
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="prerequisites[]" 
                                               value="{{ $course->id }}" 
                                               id="course-{{ $course->id }}">
                                        <label class="form-check-label" for="course-{{ $course->id }}">
                                            {{ $course->course_code }} - {{ $course->course_name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </fieldset>


                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">إضافة الكورس</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection