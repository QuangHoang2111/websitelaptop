@extends('layouts.app')

@section('title','Đổi mật khẩu')

@section('content')
<div class="row">
    <div class="col-md-3">
        @include('user.account-nav')
    </div>

    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-header fw-bold">
                Đổi mật khẩu
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('user.password.update') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Mật khẩu hiện tại</label>
                        <input type="password"name="current_password"class="form-control"required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password"name="password"class="form-control"required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Xác nhận mật khẩu mới</label>
                        <input type="password"name="password_confirmation"class="form-control"required>
                    </div>

                    <button class="btn btn-primary">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
