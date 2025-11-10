@extends('layouts.naa')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إدارة الكورسات</h5>
            
            {{-- زرار إضافة كورس جديد --}}
            <a href="{{ route("courses.create")}}" class="btn btn-primary btn-sm">
                إضافة كورس جديد +
            </a>
        </div>
        <div class="card-body">

            
            {{-- شريط البحث (بـ JS زي ما عملنا قبل كده) --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" 
                               id="searchInput" 
                               class="form-control" 
                               placeholder="ابحث باسم الكورس أو الكود...">
                        <button class="btn btn-outline-secondary" type="button">بحث</button>
                    </div>
                </div>
            </div>

            
            {{-- جدول الكورسات --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">كود الكورس</th>
                            <th scope="col">اسم الكورس</th>
                            <th scope="col">القسم (المالك)</th>
                            <th scope="col">الساعات</th>
                            <th scope="col">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="dataTableBody">
                        @forelse ($courses as $course)
                            <tr class="data-row">
                                {{-- كود الكورس --}}
                                <td>{{ $course->course_code }}</td>
                                
                                {{-- اسم الكورس --}}
                                <td>{{ $course->course_name }}</td>
                                
                                {{-- القسم (المالك) --}}
                                <td>{{ $course->department->department_name ?? 'N/A' }}</td>
                                
                                {{-- الساعات --}}
                                <td>{{ $course->credit_hours }}</td>
                                
                                {{-- إجراءات --}}
                                <td>
                                    {{-- (هنعمل الراوت ده بعدين) --}}
                                    <a href="{{route("courses.edit", $course->id)}}" class="btn btn-warning btn-sm">تعديل</a>
                                    
                                    <form action="{{ route('courses.destroy', $course->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                <div class="alert alert-info mb-0">لا يوجد كورسات لعرضها.</div>
                            </td>
                        </tr>
                        @endforelse

                        {{-- صف "لا توجد نتائج" للبحث --}}
                        <tr id="noResultsRow" style="display: none;">
                            <td colspan="5" class="text-center">
                                <div class="alert alert-warning mb-0">لا توجد نتائج مطابقة للبحث.</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            {{-- لينكات الـ Pagination --}}
            <div class="d-flex justify-content-center mt-3">
                 
            </div>
            
        </div>
    </div>
</div>

{{-- كود الـ JavaScript بتاع البحث (زي بتاع الأقسام بالظبط) --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        const searchInput = document.getElementById('searchInput');
        const tableBody = document.getElementById('dataTableBody');
        const dataRows = tableBody.getElementsByClassName('data-row');
        const noResultsRow = document.getElementById('noResultsRow');

        searchInput.addEventListener('keyup', function() {
            
            const filter = searchInput.value.toUpperCase();
            let matchesFound = 0;

            for (let i = 0; i < dataRows.length; i++) {
                const row = dataRows[i];
                // (هندور في أول خليتين: الكود والاسم)
                const codeCell = row.getElementsByTagName('td')[0];
                const nameCell = row.getElementsByTagName('td')[1];
                
                if (codeCell && nameCell) {
                    const codeText = codeCell.textContent || codeCell.innerText;
                    const nameText = nameCell.textContent || nameCell.innerText;
                    
                    if (codeText.toUpperCase().indexOf(filter) > -1 || nameText.toUpperCase().indexOf(filter) > -1) {
                        row.style.display = '';
                        matchesFound++;
                    } else {
                        row.style.display = 'none';
                    }
                }
            }

            if (matchesFound > 0) {
                noResultsRow.style.display = 'none';
            } else {
                noResultsRow.style.display = '';
            }
        });
    });
</script>
@endsection