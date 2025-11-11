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
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('courses.update', $course->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <fieldset class="border p-3 mb-3">
                            <legend class="float-none w-auto px-3 h6">بيانات الكورس الأساسية</legend>

                            <div class="mb-3">
                                <label for="course_code" class="form-label">كود الكورس</label>
                                <input type="text" class="form-control" name="course_code" value="{{ old('course_code', $course->course_code) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="course_name" class="form-label">اسم الكورس</label>
                                <input type="text" class="form-control" name="course_name" value="{{ old('course_name', $course->course_name) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="credit_hours" class="form-label">عدد الساعات</label>
                                <input type="number" class="form-control" name="credit_hours" value="{{ old('credit_hours', $course->credit_hours) }}" required min="1">
                            </div>
                            <div class="mb-3">
                                <label for="department_id" class="form-label">القسم الأساسي</label>
                                <select class="form-select" name="department_id" required>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}" {{ (old('department_id', $course->department_id) == $department->id) ? 'selected' : '' }}>
                                            {{ $department->department_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 
                            ====================================
                            (التعديل هنا) اختيار الدكتور 
                            ====================================
                            --}}
                            <div class="mb-3">
                                <label for="user_id" class="form-label">دكتور المادة (Instructor)</label>
                                <select class="form-select @error('user_id') is-invalid @enderror" 
                                        id="user_id" name="user_id" required>
                                    <option value="" disabled>-- اختر الدكتور --</option>
                                    @foreach ($instructors as $instructor)
                                        {{-- (تحديد الدكتور القديم) --}}
                                        <option value="{{ $instructor->id }}" {{ (old('user_id', $course->user_id) == $instructor->id) ? 'selected' : '' }}>
                                            {{ $instructor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="discription" class="form-label">الوصف</label>
                                <textarea class="form-control" name="discription" rows="3">{{ old('discription', $course->discription) }}</textarea>
                            </div>
                        </fieldset>

                        {{-- (باقي الـ Fieldsets زي ما هي...) --}}
                        <fieldset class="border p-3 mb-3">
                            <legend class="float-none w-auto px-3 h6">الأقسام الإضافية المسموح لها</legend>
                            <div style="max-height: 200px; overflow-y: auto;">
                                @foreach ($departments as $department)
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="allowed_departments[]" 
                                               value="{{ $department->id }}" 
                                               {{ in_array($department->id, $selectedAllowedDepts) ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            {{ $department->department_name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </fieldset>

                        <fieldset class="border p-3 mb-3">
                            <legend class="float-none w-auto px-3 h6">المتطلبات السابقة</legend>
                            <div style="max-height: 200px; overflow-y: auto;">
                                @foreach ($allCourses as $c)
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="prerequisites[]" 
                                               value="{{ $c->id }}"
                                               {{ in_array($c->id, $selectedPrerequisites) ? 'checked' : '' }}>
                                        <label class="form-check-label">
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