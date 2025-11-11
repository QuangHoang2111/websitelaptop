<ul class="account-nav">
    <li> <a href="#" class = "nav-link">Thông tin tài khoản</a> </li>
    <li> <a href="#" class = "nav-link">Đơn hàng</a> </li>
    <li> <a href="#" class = "nav-link">Địa chỉ</a> </li>
    <li> <a href="#" class = "nav-link">Đơn hàng</a> </li>
    <li class ="nav-item">
        <a href="{{route('logout')}}" class = "nav-link logout-link"
            onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">Đăng xuất
        </a>
        <form id="sidebar-logout-form" action="{{ route('logout')}}"method="POST" class="d-none"> @csrf
        </form>
    </li>
</ul>

<style>
    .account-nav{
        list-style: none;
        padding-left: 0;
        margin: 0;
    }
    .account-nav li{
        margin-bottom: 1rem;
    }
    .account-nav .nav-link{
        font-size: 20px;
        font-weight: 700;
        display: block;
    }
    .account-nav .nav-link:hover{
        color:aqua
    }


    .account-nav .logout-link:hover{
        color:red
    }
</style>