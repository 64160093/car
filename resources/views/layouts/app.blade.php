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

    <!-- Line Icons -->
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />

    <!-- css , js  -->
    @vite(['resources/css/style.css', 'resources/js/script.js'])
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        <!-- Navbar Section -->
        <nav class="navbar navbar-expand-md shadow-sm">
            <div class="container-fluid">
                <!-- เนื้อหาของ Navbar -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
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
                                    <!-- เก็บ "แก้ไขข้อมูลส่วนตัว" ไว้ใน Navbar -->
                                    <a class="dropdown-item"
                                        href="{{ route('profile.edit') }}">{{ __('แก้ไขข้อมูลส่วนตัว') }}</a>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('ออกจากระบบ') }}</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div class="wrapper">
            <!-- Sidebar Section -->
            <aside id="sidebar">
                <div class="d-flex">
                    <button class="toggle-btn" type="button">
                        <i class="lni lni-grid-alt"></i>
                    </button>
                    <div class="sidebar-logo">
                        <a href="{{ route('welcome') }}">ขออนุญาตใช้ยานรถ</a>
                    </div>
                </div>
                <!-- Sidebar Navigation -->
                <ul class="sidebar-nav">
                    <!-- แสดงเมนูของผู้ใช้ -->
                    @auth
                        @if (auth()->user()->is_admin == 1)
                            <li class="sidebar-item"><a href="{{ route('admin.home') }}" class="sidebar-link"><i
                                        class="lni lni-home"></i><span>{{ __('หน้าหลัก') }}</span></a></li>
                            <li class="sidebar-item"><a href="{{ route('admin.users') }}" class="sidebar-link"><i
                                        class="lni lni-users"></i><span>{{ __('จัดการข้อมูลผู้ใช้') }}</span></a></li>
                            <li class="sidebar-item"><a href="{{ route('store.vehicle') }}" class="sidebar-link"><i
                                        class="lni lni-car"></i><span>{{ __('แสดงข้อมูลรถ') }}</span></a></li>
                            <li class="sidebar-item"><a href="{{ route('documents.index') }}" class="sidebar-link"><i
                                        class="lni lni-files"></i><span>{{ __('รายการคำขออนุญาตทั้งหมด') }}</span></a></li>

                        @else
                            <li class="sidebar-item"><a href="{{ route('home') }}" class="sidebar-link"><i
                                        class="lni lni-home"></i><span>{{ __('หน้าหลัก') }}</span></a></li>
                            <li class="sidebar-item"><a href="{{ route('documents.index') }}" class="sidebar-link"><i
                                        class="lni lni-files"></i><span>{{ __('รายการคำขออนุญาต') }}</span></a></li>
                            <li class="sidebar-item"><a href="{{ route('reviewform') }}" class="sidebar-link"><i
                                        class="lni lni-checkmark"></i><span>{{ __('ตรวจสอบอนุมัติคำขอ') }}</span></a></li>



                        @endif
                    @endauth


                </ul>
            </aside>

            <!-- Main Content -->
            <div class="main p-3">
                <main class="py-4">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+yCg0tSgPZnFbx1bBLycLnlr7/+bJ8CsxrjW+4ylpVjmhi9z/HRX1ADdbaz8Wt"
        crossorigin="anonymous"></script>

    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var dropdowns = document.querySelectorAll('.dropdown-toggle');
            var toggleButton = document.querySelector('.toggle-btn');
            var sidebar = document.getElementById('sidebar');
            var main = document.querySelector('.main');

            dropdowns.forEach(function (dropdown) {
                dropdown.addEventListener('click', function () {
                    this.classList.toggle('active');
                });
            });
        });

    </script>
</body>

</html>