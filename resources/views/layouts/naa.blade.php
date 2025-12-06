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
            background-color: #f8f9fa;
            /* لون خلفية رمادي فاتح */
        }

        main {
            min-height: 80vh;
            /* عشان الفوتر مينطش فوق لو الصفحة فاضية */
        }

    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">

            <a class="navbar-brand" href="{{ url('/') }}">My Platform</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @auth
                    @if (Auth::user()->role === 'student')
                    {{-- Dashboard --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>

                    {{-- Student Registration --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('registration.index') }}">Register Courses</a>
                    </li>

                    @endif


                    {{-- Profile --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.edit') }}">Profile</a>
                    </li>
                    @if (Auth::user()->role === 'admin')
                    {{-- Courses --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            Courses
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('courses.dashboard') }}">Courses Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('courses.create') }}">Create Course</a></li>
                        </ul>
                    </li>

                    {{-- Departments --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            Departments
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('departments.dashboard') }}">Departments Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('departments.create') }}">Create Department</a></li>
                        </ul>
                    </li>

                    {{-- Admin --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            Admin
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">All Users</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.users.create') }}">Create User</a></li>
                        </ul>
                    </li>

                    @endif
                    
                    @if (Auth::user()->role === 'instructor')
                    {{-- Instructor --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            Instructor
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('instructor.courses') }}">My Courses</a></li>
                        </ul>
                    </li>
                    @endif
                    

                    @endauth
                </ul>

                {{-- Right side (Auth, Logout...) --}}
                <ul class="navbar-nav ms-auto">

                    @auth
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-outline-light btn-sm" type="submit">
                                Logout
                            </button>
                        </form>
                    </li>
                    @endauth

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
