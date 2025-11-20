<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600&display=swap" rel="stylesheet">

        <style>
            /* 3. تطبيق الخط وتظبيط الخلفية */
            body {
                font-family: 'Cairo', sans-serif;
                background-color: #f8f9fa; /* لون خلفية رمادي فاتح */
            }
            main {
                min-height: 80vh; /* عشان الفوتر مينطش فوق لو الصفحة فاضية */
            }
        </style>
    </head>
    <body>
        
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
            {{ config('app.name', 'نظام التسجيل') }}
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @auth
                    {{-- 1. الرئيسية (للكل) --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                           href="{{ route('dashboard') }}">
                            الرئيسية
                        </a>
                    </li>

                    {{-- ============ لينكات الأدمن ============ --}}
                    @if(auth()->user()->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
                               href="{{ route('admin.users.index') }}">
                               المستخدمين
                            </a>
                        </li>
                        <li class="nav-item">
                            {{-- هنا استخدمنا departments.dashboard زي ما إنت كاتب في الراوتس --}}
                            <a class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}" 
                               href="{{ route('departments.dashboard') }}">
                               الأقسام
                            </a>
                        </li>
                        <li class="nav-item">
                            {{-- هنا استخدمنا courses.dashboard زي ما إنت كاتب في الراوتس --}}
                            <a class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}" 
                               href="{{ route('courses.dashboard') }}">
                               الكورسات
                            </a>
                        </li>
                    @endif
                    
                    {{-- ============ لينكات الدكتور ============ --}}
                    @if(auth()->user()->role == 'instructor')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('instructor.*') ? 'active' : '' }}" 
                               href="{{ route('instructor.courses') }}">
                               كورساتي (رصد الدرجات)
                            </a>
                        </li>
                    @endif

                    {{-- ============ لينكات الطالب ============ --}}
                    @if(auth()->user()->role == 'student')
                         <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('registration.*') ? 'active' : '' }}" 
                               href="{{ route('registration.index') }}">
                               تسجيل المواد
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">تسجيل الدخول</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">تسجيل جديد</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    الملف الشخصي
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">تسجيل الخروج</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
        
        <main class="container py-4">
            {{-- أي صفحة هتستخدم الـ layout ده هتحط المحتوى بتاعها هنا --}}
            @yield('content')
        </main>


        <footer class="bg-dark text-white text-center py-3 mt-4">
            <div class="container">
                <p class="mb-0">&copy; كل الحقوق محفوظة {{ date('Y') }} - {{ config('app.name') }}</p>
            </div>
        </footer>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>