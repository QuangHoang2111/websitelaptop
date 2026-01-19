<div class="card shadow-sm">
    <div class="card-header fw-bold">
        Tài khoản
    </div>

    <ul class="list-group list-group-flush">

        <li class="list-group-item">
            <a href="{{ route('user.profile') }}"
               class="nav-link p-0 {{ request()->routeIs('user.profile') ? 'fw-bold text-primary' : '' }}">
                Thông tin tài khoản
            </a>
        </li>

        <li class="list-group-item">
           <a href="{{ route('user.orders.list') }}"class="nav-link p-0">Đơn hàng</a>
        </li>

        <li class="list-group-item">
            <a href="{{ route('user.password.form') }}"class="nav-link p-0 {{ request()->routeIs('user.password.*') ? 'fw-bold text-primary' : '' }}">
                Đổi mật khẩu
            </a>
        </li>


        <li class="list-group-item">
            <a href="{{ route('logout') }}"
               class="nav-link p-0 text-danger"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Đăng xuất
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>

    </ul>
</div>
