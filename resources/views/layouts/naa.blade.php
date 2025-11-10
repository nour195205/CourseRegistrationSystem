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
        
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'نظام التسجيل') }}
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNavbar">
                    
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">الرئيسية</a>
                            </li>
                            
                            {{-- لو اليوزر "أدمن" (زي ما عملنا في الداتابيز) --}}
                            @if(auth()->user()->role == 'admin')
                                <li class="nav-item">
                                    <a class="nav-link " href="#">الأقسام</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " href="#">الكورسات</a>
                                </li>
                                {{-- ممكن نضيف لينك الطلاب هنا لو حابب --}}
                                {{-- <li class="nav-item">
                                    <a class="nav-link" href="#">الطلاب</a>
                                </li> --}}
                            @endif
                            
                            {{-- لو اليوزر "طالب" --}}
                            @if(auth()->user()->role == 'student')
                                 <li class="nav-item">
                                    <a class="nav-link " href="#">الكورسات المتاحة</a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        @guest
                            {{-- لو اليوزر زائر (مش مسجل) --}}
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">تسجيل الدخول</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">تسجيل جديد</a>
                            </li>
                        @else
                            {{-- لو اليوزر مسجل دخوله --}}
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">الملف الشخصي</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                                تسجيل الخروج
                                            </a>
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