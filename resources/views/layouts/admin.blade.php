<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="main-content">
    <div class="topbar d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center flex-grow-1">
            <button id="openSidebarBtn" class="btn btn-sm btn-outline-secondary me-2 d-none">
                <i class="bi bi-list"></i>
            </button>
        <div class="input-group flex-grow-1 position-relative">
            <input type="text"
                id="search-input"
                class="form-control border-start-0"
                placeholder="Tìm sản phẩm">

            <span class="input-group-text bg-white border-end-0">
                <i class="bi bi-search"></i>
            </span>

            <div id="adminSearchResult"
                class="list-group position-absolute w-100 shadow"
                style="top:100%; z-index:9999; display:none">
            </div>
        </div>

        </div>
        <div>
            <span class="me-3 text-muted">{{ Auth::user()->name ?? 'Admin' }}</span>
            <i class="bi bi-bell me-3"></i>
            <i class="bi bi-person-circle"></i>
        </div>
    </div>

    <div class="content mt-4">
        @yield('content')
    </div>
</div>


    <div class="sidebar">
        <div class = "p-3 d-flex justify-content-between align-items-center border-bottom pt-0">
            <h4 class = "m-0">Admin</h4>    
            <button id = "toogleBtn" class = "btn btn-sm btn-outline-light text-dark"> <i class="bi-chevron-left"></i> </button>
        </div>
        <nav class="nav flex-column p-3">
            <li>
            <a href="{{ route('admin.dashboard') }}" class="nav-link">Thống kê</a>
            </li>
            <li class ="nav-item">
                <a href="{{route('admin.products.index')}}" class = "nav-link">Sản phẩm</a>
            </li>
            <li class ="nav-item">
                <a href="{{route('admin.brands.index')}}" class = "nav-link">Thương hiệu</a>
            </li>
            <li class ="nav-item">
                <a href="{{route('admin.categories.index')}}" class = "nav-link">Danh mục</a>
            </li>
            <li>
                <a href="{{ route('admin.orders.index') }}" class="nav-link">Đơn hàng</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.sliders.index') }}" class="nav-link">Slider</a>
            </li>   
                <a href="{{ route('admin.vouchers.index') }}" class="nav-link">Mã giảm giá</a>
                <a href="{{ route('admin.users') }}" class="nav-link">Tài khoản</a>
            <li class ="nav-item">
                <a href="{{route('logout')}}" class = "nav-link"
                    onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">Đăng xuất
                </a>
                <form id="sidebar-logout-form" action="{{ route('logout')}}"method="POST" class="d-none"> @csrf
            </li>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script>
    const searchInput = document.getElementById('search-input');
    const resultBox  = document.getElementById('adminSearchResult');

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
            fetch(`{{ route('admin.search') }}?query=${encodeURIComponent(query)}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                resultBox.innerHTML = '';

                if (!Array.isArray(data) || !data.length) {
                    resultBox.innerHTML =
                        `<div class="list-group-item text-muted">Không tìm thấy sản phẩm</div>`;
                    resultBox.style.display = 'block';
                    return;
                }

                data.forEach(item => {
                    const a = document.createElement('a');
                    a.href = `/admin/products/${item.id}/edit`;
                    a.className = 'list-group-item list-group-item-action d-flex align-items-center gap-2';

                    a.innerHTML = `
                        <img src="${item.image 
                            ? '/storage/' + item.image 
                            : '/images/no-image.png'}"
                            width="40" height="40"
                            class="rounded object-fit-cover">

                        <div class="flex-grow-1">
                            <div class="fw-semibold">${item.name}</div>
                        </div>
                    `;

                    resultBox.appendChild(a);
                });

                resultBox.style.display = 'block';
            });
        }, 300); 
    });
    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !resultBox.contains(e.target)) {
            resultBox.style.display = 'none';
        }
    });
    const toggleBtn = document.getElementById('toogleBtn');
    const openSidebarBtn = document.getElementById('openSidebarBtn');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    const toggleIcon = toggleBtn.querySelector('i');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.add('expanded');
        toggleBtn.classList.add('d-none');
        openSidebarBtn.classList.remove('d-none');
    });
    openSidebarBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.remove('expanded');
        toggleBtn.classList.remove('d-none');
        openSidebarBtn.classList.add('d-none');
    })
</script>

   <style>
    body {
        background-color: #f8f9fa;
    }

    .sidebar {
        width: 240px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        background: white;
        border-right: 1px solid #ddd;
        padding: 1.5rem 1rem;
        transition: all 0.3s ease;
        overflow-x: hidden;
    }

    .sidebar.collapsed {
        width: 0px;
        padding: 0;
        border: none;
    }


    .sidebar.collapsed h4,
    .sidebar.collapsed .nav-link span{
        display:none
    }


    .sidebar .nav-link {
        color: #333;
        margin-bottom: 10px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .sidebar .nav-link i {
        font-size: 18px;
        width: 24px;
        text-align: center;
    }

    .sidebar .nav-link span {
        transition: opacity 0.3s ease;
        white-space: nowrap;
        overflow: hidden;
    }

    .sidebar .nav-link:hover {
        background: #e9ecef;
        border-radius: 8px;
    }

    .main-content {
        margin-left: 240px;
        margin-right: 0;
        min-width: 0;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .main-content.expanded {
        margin-left: 0;
    }

    .topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        border-bottom: 1px solid #ddd;
        padding: 0.75rem 1.5rem;
        position: sticky;
        top: 0;
        left: 0;
        right: 0;
        z-index: 10;
    }
    .input-group{
        display: flex;
        width: 100%;
        max-width: 600px;
    }
    .input-group .form-control {
    border-radius: 20px 0 0 20px;
    padding: 0.375rem 0.75rem;
    transition: box-shadow 0.2s;
    border-right: 0;
    width: auto;
    flex-grow: 1;
    }

    .input-group .form-control:focus {
    box-shadow: 0 0 5px rgba(0,0,0,0.15);
    outline: none;
    }

    .input-group-text {
    border-radius: 0 20px 20px 0;
    border-left: 0;
    background-color: #fff;
    }
</style>

</html>
