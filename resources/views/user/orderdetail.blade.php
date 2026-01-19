@extends('layouts.app')
@section('title','Chi tiết đơn hàng')

@section('content')
<div class="container my-5">
    <div class="row g-4">

        <div class="col-md-3">
            @include('user.account-nav')
        </div>

        <div class="col-md-9">
            <h4 class="fw-bold mb-3">
                Đơn hàng #{{ $order->id }}
            </h4>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Người nhận:</strong> {{ $order->name }} <br>
                            <strong>SĐT:</strong> {{ $order->phone }} <br>
                            <strong>Địa chỉ:</strong>
                            {{ $order->address }},
                            {{ $order->ward }},
                            {{ $order->city }}
                        </div>

                        <div class="col-md-6">
                            <strong>Ngày đặt:</strong>
                            {{ $order->created_at->format('d/m/Y H:i') }} <br>

                            <strong>Thanh toán:</strong>
                            <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                            </span>
                            <br>

                            <strong>Phương thức:</strong>
                            {{ $order->payment_method }} <br>

                            <strong>Trạng thái:</strong>
                            <span class="badge bg-info text-dark">
                                {{ ucfirst($order->status) }}
                            </span>

                            @if($order->cancelled_date)
                                <br>
                                <strong>Ngày hủy:</strong>
                                {{ \Carbon\Carbon::parse($order->cancelled_date)->format('d/m/Y') }}
                            @endif

                            @if($order->delivered_date)
                                <br>
                                <strong>Ngày giao:</strong>
                                {{ \Carbon\Carbon::parse($order->delivered_date)->format('d/m/Y') }}
                            @endif
                        </div>
                    </div>

                    @if(
                        $order->status === 'pending'
                    )
                        <div class="mt-3">
                            <a href="{{ route('vnpay.pay', $order->id) }}"
                            class="btn btn-success">
                                Thanh toán qua VNPAY
                            </a>
                        </div>
                    @endif

                </div>
            </div>

            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Giá</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</div>
                                </td>

                                <td class="text-center">{{ $item->quantity }}</td>

                                <td class="text-end">{{ number_format($item->price) }} ₫</td>

                                <td class="text-end text-danger fw-semibold">{{ number_format($item->price * $item->quantity) }} ₫</td>
                            </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Tạm tính</th>
                                <th class="text-end">{{ number_format($order->subtotal) }} ₫</th>
                            </tr>

                            <tr>
                                <th colspan="3" class="text-end">Giảm giá</th>
                                <th class="text-end">{{ number_format($order->discount) }} ₫</th>
                            </tr>

                            <tr>
                                <th colspan="3" class="text-end fw-bold">Tổng cộng</th>
                                <th class="text-end text-danger fw-bold">{{ number_format($order->total) }} ₫</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('user.orders.list') }}"
                   class="btn btn-outline-secondary">
                        Quay lại đơn hàng
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
