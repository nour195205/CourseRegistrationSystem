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

    {{-- =================== Features Section (المميزات) =================== --}}
    <div class="row align-items-md-stretch mt-5">
        
        {{-- ميزة 1: للطلاب --}}
        <div class="col-md-4 mb-4">
            <div class="h-100 p-4 text-white bg-dark rounded-3 shadow-sm">
                <h3 class="mb-3">للطلاب</h3>
                <ul class="list-unstyled fs-5">
                    <li class="mb-2">✅ حساب المعدل (GPA) تلقائيًا.</li>
                    <li class="mb-2">✅ تسجيل وحذف المواد بضغطة زر.</li>
                    <li class="mb-2">✅ معرفة الساعات المتبقية والمسموحة.</li>
                </ul>
            </div>
        </div>

        {{-- ميزة 2: للدكاترة --}}
        <div class="col-md-4 mb-4">
            <div class="h-100 p-4 bg-white border rounded-3 shadow-sm">
                <h3 class="mb-3 text-dark">لأعضاء هيئة التدريس</h3>
                <ul class="list-unstyled fs-5 text-muted">
                    <li class="mb-2">🔹 عرض قوائم الطلاب المسجلين.</li>
                    <li class="mb-2">🔹 رصد الدرجات بسهولة.</li>
                    <li class="mb-2">🔹 متابعة حالة الكورسات.</li>
                </ul>
            </div>
        </div>

        {{-- ميزة 3: الإدارة --}}
        <div class="col-md-4 mb-4">
            <div class="h-100 p-4 text-white bg-secondary rounded-3 shadow-sm">
                <h3 class="mb-3">للإدارة</h3>
                <ul class="list-unstyled fs-5">
                    <li class="mb-2">⚙️ إدارة المستخدمين والصلاحيات.</li>
                    <li class="mb-2">⚙️ التحكم في الأقسام والمواد.</li>
                    <li class="mb-2">⚙️ ضبط المتطلبات السابقة للكورسات.</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- =================== Footer بسيط للصفحة =================== --}}
    <footer class="pt-3 mt-4 text-muted border-top text-center">
        &copy; {{ date('Y') }} جميع الحقوق محفوظة لجامعة [اسم الجامعة]
    </footer>
</div>
@endsection