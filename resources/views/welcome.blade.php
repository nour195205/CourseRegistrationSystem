<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <title>@yield('title')</title>

        <!-- 1. Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        
        <!-- 2. Font (اختياري، ممكن نستخدم خطوط جوجل زي Cairo) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600&display=swap" rel="stylesheet">

        {{-- 3. شيلنا السطر بتاع @vite عشان ميجيبش Tailwind --}}
        
        <style>
            /* 4. تطبيق الخط العربي على الجسم */
            body {
                font-family: 'Cairo', sans-serif;
                background-color: #f8f9fa; /* لون خلفية زي بتاع Tailwind */
            }
            /* تعديل بسيط عشان الـ navigation الأصلي يشتغل */
            .navbar {
                border-bottom: 1px solid #e5e7eb;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="min-h-screen">
            
            {{-- 
              ملحوظة: ملف الـ navigation ده معمول بـ Tailwind
              شكله هيتغير تمامًا وهيحتاج يتعدل بكلاسات Bootstrap
            --}} [cite: uploaded:nour195205/courseregistrationsystem/CourseRegistrationSystem-16fea9b6c7ce54dd59a78243836dd133fb8d09f6/resources/views/layouts/navigation.blade.php]
            @include('layouts.navigation') 

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow-sm"> {{-- استخدمنا كلاسات قريبة من Bootstrap --}}
                    <div class="container py-3"> {{-- استخدمنا container --}}
                        @yield('header')
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="container mt-4"> {{-- استخدمنا container و margin --}}
                @yield('content')
            </main>
        </div>

        <!-- 5. Bootstrap JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>