{{-- 1. بنستخدم الـ layout بتاعنا --}}
@extends('layouts.naa')

{{-- 2. بنحط الـ Header --}}
@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('لوحة تحكم الأدمن - إدارة الأقسام') }}
</h2>
@endsection

{{-- 3. بنحط المحتوى الأساسي --}}
@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">قائمة الأقسام</h5>

            {{-- زرار إضافة قسم جديد --}}
            <a href="{{ route("departments.create")}}" class="btn btn-primary btn-sm">
                إضافة قسم جديد +
            </a>
        </div>
        <div class="card-body">


            {{-- شريط البحث (اتشال منه الفورم) --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        {{-- (التعديل 1: ضفنا id للـ input) --}}
                        <input type="text" id="searchInput" class="form-control" placeholder="ابحث باسم القسم...">

                        {{-- (التعديل 2: خلينا الزرار type="button") --}}
                        <button class="btn btn-outline-secondary" type="button">بحث</button>
                    </div>
                </div>
            </div>


            {{-- جدول الأقسام --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">اسم القسم</th>
                            <th scope="col">إجراءات</th>
                        </tr>
                    </thead>
                    {{-- (التعديل 3: ضفنا id للـ tbody) --}}
                    <tbody id="departmentTableBody">
                        @forelse ($departments ?? [] as $department)
                        {{-- (التعديل 4: ضفنا كلاس للـ rows بتوع الداتا) --}}
                        <tr class="data-row">
                            <td>{{ $department->department_name ?? 'لا توجد اقسام' }}</td>

                            <td>
                                <a href="{{route("departments.edit", $department->id)}}" class="btn btn-warning btn-sm">تعديل</a>

                                <form action="{{ route('departments.destroy', $department->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            {{-- (التعديل 5: صلحنا الـ colspan) --}}
                            <td colspan="2" class="text-center">
                                <div class="alert alert-info mb-0">لا يوجد أقسام لعرضهم.</div>
                            </td>
                        </tr>
                        @endforelse

                        {{-- (التعديل 6: ضفنا row لـ "لا توجد نتائج" هنستخدمه في الـ JS) --}}
                        <tr id="noResultsRow" style="display: none;">
                            <td colspan="2" class="text-center">
                                <div class="alert alert-warning mb-0">لا توجد نتائج مطابقة للبحث.</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- لينكات الـ Pagination (لو فيه) --}}
            <div class="d-flex justify-content-center mt-3">

            </div>

        </div>
    </div>
</div>

{{-- (التعديل 7: إضافة كود الـ JavaScript) --}}
<script>
    // انتظر لحد ما الصفحة كلها تحمل
    document.addEventListener('DOMContentLoaded', function() {

        // 1. امسك العناصر اللي هنشتغل عليها
        const searchInput = document.getElementById('searchInput');
        const tableBody = document.getElementById('departmentTableBody');
        const dataRows = tableBody.getElementsByClassName('data-row');
        const noResultsRow = document.getElementById('noResultsRow');

        // 2. ضيف "مستمع" على مربع البحث (بيشتغل مع كل ضغطة زرار)
        searchInput.addEventListener('keyup', function() {

            // هات الكلمة اللي اليوزر بيبحث عنها وخليها حروف كبيرة (عشان البحث ميبقاش حساس)
            const filter = searchInput.value.toUpperCase();
            let matchesFound = 0;

            // 3. لف على كل "صفوف الداتا" بس
            for (let i = 0; i < dataRows.length; i++) {
                const row = dataRows[i];
                const nameCell = row.getElementsByTagName('td')[0]; // امسك أول خلية (اللي فيها الاسم)

                if (nameCell) {
                    const textValue = nameCell.textContent || nameCell.innerText;

                    // 4. قارن الكلمة باللي في الخلية
                    if (textValue.toUpperCase().indexOf(filter) > -1) {
                        row.style.display = ''; // لو مطابقة، اظهر الصف
                        matchesFound++;
                    } else {
                        row.style.display = 'none'; // لو مش مطابقة، اخفي الصف
                    }
                }
            }

            // 5. إظهار أو إخفاء رسالة "لا توجد نتائج"
            if (matchesFound > 0) {
                noResultsRow.style.display = 'none';
            } else {
                noResultsRow.style.display = ''; // استخدم display: '' عشان يرجع لوضعه الطبيعي (tr)
            }
        });
    });

</script>
@endsection
