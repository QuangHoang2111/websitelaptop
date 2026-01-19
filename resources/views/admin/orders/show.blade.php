@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <h3 class="fw-bold mb-4">Chi tiết đơn hàng #{{ $order->id }}</h3>

    <div class="row">
        <div class="col-md-5">
            <div class="card p-4 mb-4">
                <h5 class="fw-bold mb-3">Thông tin khách hàng</h5>
                <p><strong>Họ tên:</strong> {{ $order->name }}</p>
                <p><strong>SĐT:</strong> {{ $order->phone }}</p>
                <p><strong>Địa chỉ:</strong> {{ $order->address }}, {{ $order->ward }}, {{ $order->city }}</p>
                <p><strong>Thanh toán:</strong> {{ $order->payment_method }}</p>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">Sản phẩm</h5>

                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Tạm tính</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ number_format($item->price) }} ₫</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price * $item->quantity) }} ₫</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <hr>

                <div class="text-end">
                    <p>Tạm tính: {{ number_format($order->subtotal) }} ₫</p>
                    <p>Giảm giá: {{ number_format($order->discount) }} ₫</p>
                    <h5 class="text-danger fw-bold">
                        Tổng: {{ number_format($order->total) }} ₫
                    </h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
