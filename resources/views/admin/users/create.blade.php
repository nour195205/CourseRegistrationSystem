@extends('layouts.naa')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">إضافة مستخدم جديد</h5>
                </div>
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">الاسم</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">كلمة المرور</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">تأكيد كلمة المرور</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label">نوع المستخدم (Role)</label>
                            <select name="role" id="roleSelect" class="form-select" required>
                                <option value="" disabled selected>-- اختر النوع --</option>
                                <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>طالب (Student)</option>
                                <option value="instructor" {{ old('role') == 'instructor' ? 'selected' : '' }}>دكتور (Instructor)</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>أدمن (Admin)</option>
                            </select>
                        </div>

                        <div class="mb-3" id="departmentDiv" style="display: none;">
                            <label class="form-label">القسم (مطلوب للطلاب)</label>
                            <select name="department_id" class="form-select">
                                <option value="" disabled selected>-- اختر القسم --</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->department_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">إضافة المستخدم</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- سكربت بسيط عشان يظهر/يخفي قايمة الأقسام --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('roleSelect');
        const departmentDiv = document.getElementById('departmentDiv');

        function toggleDepartment() {
            if (roleSelect.value === 'student') {
                departmentDiv.style.display = 'block';
            } else {
                departmentDiv.style.display = 'none';
            }
        }

        // شغل الدالة لما نغير الاختيار
        roleSelect.addEventListener('change', toggleDepartment);
        
        // شغل الدالة أول ما الصفحة تفتح (عشان لو فيه old value)
        toggleDepartment();
    });
</script>
@endsection