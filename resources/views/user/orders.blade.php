@extends('layouts.app')
@section('title','Đơn hàng của tôi')

@section('content')
<div class="container my-5">
    <div class="row g-4">

        <div class="col-md-3">
            @include('user.account-nav')
        </div>

        <div class="col-md-9">
            <h4 class="fw-bold mb-3">Đơn hàng của tôi</h4>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:60px">STT</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Thanh toán</th>
                                    <th>Trạng thái</th>
                                    <th style="width:160px">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $key => $order)
                                <tr>
                                    <td>{{ $orders->firstItem() + $key }}</td>

                                    <td>
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="text-danger fw-semibold">
                                        {{ number_format($order->total) }} ₫
                                    </td>

                                    <td>
                                        <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-info text-dark">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>

                                    <td>
                                        <a href="{{ route('user.orders.show', $order) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            Xem
                                        </a>

                                      @if(!($order->status === 'completed' || $order->payment_status === 'paid'))
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#cancelOrderModal"
                                                data-order-id="{{ $order->id }}"
                                                data-action="{{ route('user.orders.cancel', $order) }}"
                                            >
                                                Hủy
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        Bạn chưa có đơn hàng nào
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="cancelOrderModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body">
                <p class="mb-0">
                    Xác nhận <strong> hủy đơn hàng </strong> này?
                </p>
            </div>

            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Quay lại
                </button>

                <form id="cancelOrderForm" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        Xác nhận hủy
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('cancelOrderModal');
    const form = document.getElementById('cancelOrderForm');

    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const action = button.getAttribute('data-action');
        form.setAttribute('action', action);
    });
});
</script>

@endsection
