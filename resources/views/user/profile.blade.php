@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row g-4">

        <div class="col-md-3">
            @include('user.account-nav')
        </div>

        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h5 class="mb-3">Địa chỉ của bạn</h5>

                    <form method="POST" action="{{ route('user.address.save') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Tên</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $address->name ?? 'Nhà Riêng') }}" required></div>

                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $address->phone ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $address->address ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phường</label>
                            <input type="text" name="ward" class="form-control" value="{{ old('ward', $address->ward ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Thành phố</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', $address->city ?? '') }}">
                        </div>

                        <button class="btn btn-primary">
                            {{ $address ? 'Lưu thay đổi' : 'Lưu địa chỉ' }}
                        </button>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
