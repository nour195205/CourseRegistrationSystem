@extends('layouts.naa')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">تعديل الكورس: {{ $course->course_name }}</h5>
                </div>
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <strong>يوجد بعض الأخطاء:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('courses.update', $course->id) }}">
                        @csrf
                        @method('PUT') {{-- 1. مهم جدًا لصفحة التعديل --}}
                        
                        {{-- 
                        ====================================
                        الجزء 1: البيانات الأساسية للكورس
                        ====================================
                        --}}
                        <fieldset class="border p-3 mb-3">
                            <legend class="float-none w-auto px-3 h6">بيانات الكورس الأساسية</legend>

                            <div class="mb-3">
                                <label for="course_code" class="form-label">كود الكورس</label>
                                {{-- 2. عرض القيمة القديمة --}}
                                <input type="text" class="form-control @error('course_code') is-invalid @enderror" 
                                       id="course_code" name="course_code" value="{{ old('course_code', $course->course_code) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="course_name" class="form-label">اسم الكورس</label>
                                <input type="text" class="form-control @error('course_name') is-invalid @enderror" 
                                       id="course_name" name="course_name" value="{{ old('course_name', $course->course_name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="credit_hours" class="form-label">عدد الساعات</label>
                                <input type="number" class="form-control @error('credit_hours') is-invalid @enderror" 
                                       id="credit_hours" name="credit_hours" value="{{ old('credit_hours', $course->credit_hours) }}" required min="1">
                            </div>

                            <div class="mb-3">
                                <label for="department_id" class="form-label">القسم الأساسي (المالك للكورس)</label>
                                <select class="form-select @error('department_id') is-invalid @enderror" 
                                        id="department_id" name="department_id" required>
                                    <option value="" disabled>-- اختر القسم --</option>
                                    @foreach ($departments as $department)
                                        {{-- 3. تحديد القسم القديم --}}
                                        <option value="{{ $department->id }}" {{ (old('department_id', $course->department_id) == $department->id) ? 'selected' : '' }}>
                                            {{ $department->department_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="discription" class="form-label">الوصف</label>
                                <textarea class="form-control @error('discription') is-invalid @enderror" 
                                          id="discription" name="discription" rows="3">{{ old('discription', $course->discription) }}</textarea>
                            </div>
                        </fieldset>

                        {{-- 
                        ====================================
                        الجزء 2: الأقسام المسموحة
                        ====================================
                        --}}
                        <fieldset class="border p-3 mb-3">
                            <legend class="float-none w-auto px-3 h6">الأقسام الإضافية المسموح لها</legend>
                            <div style="max-height: 200px; overflow-y: auto;">
                                @foreach ($departments as $department)
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="allowed_departments[]" 
                                               value="{{ $department->id }}" 
                                               id="dept-{{ $department->id }}"
                                               {{-- 4. عمل Check على الأقسام القديمة --}}
                                               {{ in_array($department->id, $selectedAllowedDepts) ? 'checked' : '' }}
                                               >
                                        <label class="form-check-label" for="dept-{{ $department->id }}">
                                            {{ $department->department_name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </fieldset>

                        {{-- 
                        ====================================
                        الجزء 3: المتطلبات السابقة
                        ====================================
                        --}}
                        <fieldset class="border p-3 mb-3">
                            <legend class="float-none w-auto px-3 h6">المتطلبات السابقة</legend>
                            <div style="max-height: 200px; overflow-y: auto;">
                                @foreach ($allCourses as $c)
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="prerequisites[]" 
                                               value="{{ $c->id }}" 
                                               id="course-{{ $c->id }}"
                                               {{-- 5. عمل Check على المتطلبات القديمة --}}
                                               {{ in_array($c->id, $selectedPrerequisites) ? 'checked' : '' }}
                                               >
                                        <label class="form-check-label" for="course-{{ $c->id }}">
                                            {{ $c->course_code }} - {{ $c->course_name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </fieldset>


                        <div class="text-end">
                            <button type="submit" class="btn btn-warning">حفظ التعديلات</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection