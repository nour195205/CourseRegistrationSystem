@extends('layouts.naa')

@section('content')
<div class="container">

    {{-- =================== Hero Section (الجزء الرئيسي) =================== --}}
    <div class="p-5 mb-4 bg-light rounded-3 shadow-sm text-center border">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold text-primary">نظام تسجيل الكورسات الجامعي</h1>
            <p class="col-md-8 fs-4 mx-auto mt-3 text-muted">
                منصتك المتكاملة لإدارة رحلتك الأكاديمية. سجل موادك، تابع معدلك التراكمي (GPA)، وتواصل مع دكاترة المواد بكل سهولة ويسر.
            </p>
            
            <div class="mt-4">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg px-4 mx-2">
                        تسجيل الدخول
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-success btn-lg px-4">
                        الذهاب إلى لوحة التحكم
                    </a>
                @endguest
            </div>
        </div>
    </div>

   

    {{-- =================== Footer بسيط للصفحة =================== --}}
    <footer class="pt-3 mt-4 text-muted border-top text-center">
        &copy; {{ date('Y') }} جميع الحقوق محفوظة لجامعة كفر الشيخ 
    </footer>
</div>
@endsection