<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laptop HQ')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    
</head>
<body class="d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand">Laptop HQ</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/home') }}">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/products') }}">Sản phẩm</a></li>
                </ul>
            <div class="position-relative frontend-search">
                <input type="text"id="search-input"class="form-control border-start-0"placeholder="Tìm sản phẩm">
                <div id="searchResult"class="list-group position-absolute w-100 shadow"style="top:100%; z-index:9999; display:none"></div>
            </div>

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

    <main class="container mt-4 flex-grow-1 main-content">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="mt-auto bg-dark text-light py-3">
        <div class="container text-center">
            <p class="mb-1"><strong>Laptop HQ</strong></p>
        </div>
    </footer>

</body>

<script>
    const searchInput = document.getElementById('search-input');
    const resultBox  = document.getElementById('searchResult');

    let timer = null;

    searchInput.addEventListener('input', function () {
        const query = this.value.trim();
        clearTimeout(timer);

        if (query.length < 2) {
            resultBox.style.display = 'none';
            resultBox.innerHTML = '';
            return;
        }

        timer = setTimeout(() => {
            fetch(`{{ route('search.suggest') }}?query=${encodeURIComponent(query)}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                resultBox.innerHTML = '';

                if (!data.length) {
                    resultBox.innerHTML =
                        `<div class="list-group-item text-muted">
                            Không tìm thấy sản phẩm
                        </div>`;
                    resultBox.style.display = 'block';
                    return;
                }

                data.forEach(item => {
                    resultBox.innerHTML += `
                        <a href="/products/${item.slug}"
                        class="list-group-item list-group-item-action d-flex gap-2">
                            <img src="${item.image ? '/storage/' + item.image : '/images/no-image.png'}"
                                width="40" height="40">
                            <div>
                                <div class="fw-semibold">${item.name}</div>
                            </div>
                        </a>
                    `;
                });

                resultBox.style.display = 'block';
            });
        }, 300);
    });

    document.addEventListener('click', e => {
        if (!searchInput.contains(e.target) && !resultBox.contains(e.target)) {
            resultBox.style.display = 'none';
        }
    });
</script>

<style>

    .main-content {
    padding-bottom: 40px;
    }
    .frontend-search {
        max-width: 500px;
        width: 100%;
    }

    #searchResult {
        border-radius: 10px;
        overflow: hidden;
        max-height: 350px;
        overflow-y: auto;
    }

    #searchResult .list-group-item {
        border: none;
        border-bottom: 1px solid #eee;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    #searchResult .list-group-item:last-child {
        border-bottom: none;
    }

    #searchResult img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 6px;
    }

    @media (max-width: 992px) {
        .frontend-search {
            margin: 10px 0;
            max-width: 100%;
        }
    }


</style>
    @stack('scripts')
</html>
