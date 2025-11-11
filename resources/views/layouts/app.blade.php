<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laptop HQ')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction:column ;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        main{
            flex: 1 0 auto;
            margin-bottom: 0;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #007bff !important;
        }

        .footer {
            background: #222;
            color: #ccc;
            padding: 20px 0;
            text-align: center;
        }

        .footer a {
            color: #0d6efd;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Laptop HQ</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/home') }}">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/products') }}">Sản phẩm</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/about') }}">Giới thiệu</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/contact') }}">Liên hệ</a></li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Đăng nhập</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Đăng ký</a></li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    @if (Auth::user() -> utype === 'ADM')
                                        <a class="dropdown-item" href="{{ url('/admin')}}"> Thông tin </a>
                                    @else
                                        <a class="dropdown-item" href="{{ url('/user')}}"> Thông tin </a>
                                    @endif
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Đăng xuất
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest

                    <li class="nav-item">
                        <a href="{{ url('/cart') }}" class="nav-link">
                            Giỏ hàng
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        @yield('content')
    </main>

    <footer class="footer mt-auto">
        <div class="container">
            <p> <strong>Laptop HQ</strong></p>
            <p>
                <a href="{{ url('/about') }}">Về chúng tôi</a> |
                <a href="{{ url('/contact') }}">Liên hệ</a> |
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
