{{-- 1. بنستخدم الـ layout الأساسي بتاعنا --}}
@extends('layouts.naa')

{{-- 3. بنحط المحتوى الأساسي (الفورم) --}}
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">تعديل قسم</h5>
                </div>
                <div class="card-body">

                    {{-- عرض أخطاء الـ Validation فوق --}}
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

                    <form method="POST" action="{{ route('departments.update' , $department->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="department_name" class="form-label">اسم القسم</label>
                            <input type="text" 
                                   class="form-control @error('department_name') is-invalid @enderror" 
                                   id="department_name" 
                                   name="department_name" 
                                   value="{{ old('department_name', $department->department_name) }}"
                                   required 
                                   autofocus>
                            @error('department_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">إضافة القسم</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection