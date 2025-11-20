@extends('layouts.naa')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إدارة المستخدمين (الطلاب، الدكاترة، الأدمن)</h5>
            
            {{-- (اللينك ده لسه هنعمله) --}}
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                إضافة مستخدم جديد +
            </a>
        </div>
        <div class="card-body">

            
            {{-- شريط البحث (بـ JS) --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" 
                               id="searchInput" 
                               class="form-control" 
                               placeholder="ابحث بالاسم أو البريد الإلكتروني...">
                        <button class="btn btn-outline-secondary" type="button">بحث</button>
                    </div>
                </div>
            </div>

            
            {{-- جدول المستخدمين --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">الاسم</th>
                            <th scope="col">البريد الإلكتروني</th>
                            <th scope="col">الدور (Role)</th>
                            <th scope="col">القسم</th>
                            <th scope="col">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="dataTableBody">
                        @forelse ($users as $user)
                            <tr class="data-row">
                                {{-- الاسم --}}
                                <td>{{ $user->name }}</td>
                                
                                {{-- الإيميل --}}
                                <td>{{ $user->email }}</td>

                                {{-- الدور (Role) --}}
                                <td>
                                    @if($user->role == 'admin')
                                        <span class="badge bg-danger">أدمن</span>
                                    @elseif($user->role == 'instructor')
                                        <span class="badge bg-success">دكتور</span>
                                    @else
                                        <span class="badge bg-info">طالب</span>
                                    @endif
                                </td>
                                
                                {{-- القسم (الخاص بالطالب) --}}
                                <td>
                                    {{-- (بنعرض القسم بس لو هو طالب وعنده بروفايل) --}}
                                    {{ $user->profile->department->department_name ?? 'N/A' }}
                                </td>
                                
                                {{-- إجراءات --}}
                                <td>
                                    {{-- (اللينكات دي لسه هنعملها) --}}
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">تعديل</a>
                                    
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                <div class="alert alert-info mb-0">لا يوجد مستخدمين لعرضهم.</div>
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
                 {{ $users->links() }}
            </div>
            
        </div>
    </div>
</div>

{{-- كود الـ JavaScript بتاع البحث (بيدور في الاسم والإيميل) --}}
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
                // (هندور في أول خليتين: الاسم والإيميل)
                const nameCell = row.getElementsByTagName('td')[0];
                const emailCell = row.getElementsByTagName('td')[1];
                
                if (nameCell && emailCell) {
                    const nameText = nameCell.textContent || nameCell.innerText;
                    const emailText = emailCell.textContent || emailCell.innerText;
                    
                    if (nameText.toUpperCase().indexOf(filter) > -1 || emailText.toUpperCase().indexOf(filter) > -1) {
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