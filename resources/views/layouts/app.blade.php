<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- Custom CSS -->
    <style>
        .navbar {
            background-color: #0d6efd !important;
            /* เปลี่ยนสีพื้นหลังเป็นสีน้ำเงิน */

        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ffc107 !important;
            /* เปลี่ยนสีตัวหนังสือเป็นสีเหลือง */
        }

        .nav-link {
            color: #ffffff !important;
            /* เปลี่ยนสีตัวหนังสือเป็นสีขาว */
            font-weight: bold;
            /* ทำตัวหนังสือหนา */
        }

        .nav-link:hover {
            background-color: #ffc107 !important;
            /* เปลี่ยนสีพื้นหลังเมื่อเอาเมาส์ชี้เป็นสีเหลือง */
            color: #0d6efd !important;
            /* เปลี่ยนสีตัวหนังสือเป็นสีน้ำเงินเมื่อเอาเมาส์ชี้ */
            border-radius: 5px;
        }

        .dropdown-menu {
            border-radius: 10px;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md shadow-sm">
            <div class="container-fluid"> <!-- ใช้ container-fluid -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link btn btn-outline-primary me-2"
                                        href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @if (auth()->user()->is_admin == 1)
                                        <a class="dropdown-item" href="{{ route('admin.home') }}">
                                            {{ __('หน้าหลัก') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.users') }}">
                                            {{ __('จัดการข้อมูลผู้ใช้') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ route('store.vehicle') }}">
                                            {{ __('แสดงข้อมูลรถ') }}
                                        </a>
                                    @else
                                        <a class="dropdown-item" href="{{ route('home') }}">
                                            {{ __('หน้าหลัก') }}
                                        </a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        {{ __('แก้ไขข้อมูลส่วนตัว') }}
                                    </a>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                     document.getElementById('logout-form').submit();">
                                        {{ __('ออกจากระบบ') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+yCg0tSgPZnFbx1bBLycLnlr7/+bJ8CsxrjW+4ylpVjmhi9z/HRX1ADdbaz8Wt"
        crossorigin="anonymous"></script>

    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var dropdowns = document.querySelectorAll('.dropdown-toggle');
            dropdowns.forEach(function (dropdown) {
                dropdown.addEventListener('click', function () {
                    this.classList.toggle('active');
                });
            });
        });
    </script>
</body>

</html>