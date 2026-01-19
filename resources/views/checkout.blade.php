@extends('layouts.app')
@section('title','Thanh toán')

@section('content')
<form action="{{ route('checkout.place') }}" method="POST">
@csrf

<div class="row">
    <div class="col-md-7">
        <h4 class="fw-bold mb-3">Thông tin giao hàng</h4>

        <input class="form-control mb-2" name="name"
               value="{{ $address->name ?? auth()->user()->name }}"
               placeholder="Họ tên" required>

        <input class="form-control mb-2" name="phone"
               value="{{ $address->phone ?? '' }}"
               placeholder="Số điện thoại" required>

        <input class="form-control mb-2" name="address"
               value="{{ $address->address ?? '' }}"
               placeholder="Địa chỉ" required>

        <input class="form-control mb-2" name="city"
               value="{{ $address->city ?? '' }}"
               placeholder="Thành phố" required>

        <input class="form-control mb-2" name="ward"
               value="{{ $address->ward ?? '' }}"
               placeholder="Phường / xã" required>
    </div>

    <div class="col-md-5">
        <h4 class="fw-bold mb-3">Thanh toán</h4>

        <div class="border rounded p-3">
            <div class="form-check mb-2">
                <input class="form-check-input"
                       type="radio"
                       name="payment_method"
                       value="COD"
                       checked>
                <label class="form-check-label">
                    Thanh toán khi nhận hàng (COD)
                </label>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input"
                       type="radio"
                       name="payment_method"
                       value="VNPAY">
                <label class="form-check-label">
                    Thanh toán qua VNPAY
                </label>
            </div>

            <hr>

            <button type="submit" class="btn btn-danger w-100 mt-3">
                ĐẶT HÀNG
            </button>
        </div>
    </div>
</div>
</form>
@endsection
