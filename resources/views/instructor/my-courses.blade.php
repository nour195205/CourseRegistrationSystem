@extends('layouts.naa')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">الكورسات الخاصة بي (للدكتور)</h5>
        </div>
        <div class="card-body">

            {{-- لو الدكتور معندوش كورسات --}}
            @if ($courses->isEmpty())
            <div class="alert alert-info text-center">
                لا توجد أي كورسات مسندة إليك حاليًا.
            </div>
            @else
            {{-- لو عنده كورسات، اعرضها في جدول --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">كود الكورس</th>
                            <th scope="col">اسم الكورس</th>
                            <th scope="col">عدد الطلاب المسجلين</th>
                            <th scope="col">إجراء (رصد الدرجات)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($courses as $course)
                        <tr>
                            {{-- كود الكورس --}}
                            <td>{{ $course->course_code }}</td>

                            {{-- اسم الكورس --}}
                            <td>{{ $course->course_name }}</td>

                            {{-- عدد الطلاب (جبناه من withCount) --}}
                            <td>
                                <span class="badge bg-primary">{{ $course->registrations_count }}</span>
                                طالب
                            </td>

                            {{-- اللينك لصفحة رصد الدرجات (لسه هنعملها) --}}
                            <td>
                                {{-- (ده اللينك اللي لسه هنعمله) --}}
                                <a href="{{ route('instructor.grade.show', $course) }}" class="btn btn-warning btn-sm">
                                    عرض الطلاب (لرصد الدرجات)
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
