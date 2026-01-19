@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0">Đơn hàng</h3>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:80px">STT</th>
                            <th>Người đặt</th>
                            <th>Ngày đặt</th>
                            <th>Thanh toán</th>
                            <th>Phương thức</th>
                            <th>Trạng thái</th>
                            <th>Ngày giao</th>
                            <th>Ngày hủy</th>
                            <th style="width:160px">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $key => $order)
                        <tr>
                            <td>{{ $orders->firstItem() + $key }}</td>

                            <td>
                                <div class="fw-semibold">{{ $order->name }}</div>
                                <small class="text-muted">{{ $order->phone }}</small>
                            </td>

                            <td>{{ $order->created_at->format('d/m/Y') }}</td>

                            <td>
                                <span class="badge {{ $order->payment_status == 'paid' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $order->payment_status == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-info">
                                    {{ $order->payment_method }}
                                </span>
                            </td>

                            <td>
                                <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                                    @csrf
                                    <select name="status"
                                            onchange="this.form.submit()"
                                            class="form-select form-select-sm">
                                        <option value="pending" {{ $order->status=='pending'?'selected':'' }}>Chờ xử lý</option>
                                        <option value="processing" {{ $order->status=='processing'?'selected':'' }}>Đang xử lý</option>
                                        <option value="completed" {{ $order->status=='completed'?'selected':'' }}>Hoàn thành</option>
                                        <option value="cancelled" {{ $order->status=='cancelled'?'selected':'' }}>Hủy</option>
                                    </select>
                                </form>
                            </td>

                            <td>
                                <form action="{{ route('admin.orders.updateDate', $order) }}" method="POST">
                                    @csrf
                                    <input type="date"
                                        name="delivered_date"
                                        value="{{ $order->delivered_date }}"
                                        class="form-control form-control-sm"
                                        onchange="this.form.submit()">
                                </form>
                            </td>

                            <td>
                                <form action="{{ route('admin.orders.updateDate', $order) }}" method="POST">
                                    @csrf
                                    <input type="date"
                                        name="cancelled_date"
                                        value="{{ $order->cancelled_date }}"
                                        class="form-control form-control-sm"
                                        onchange="this.form.submit()">
                                </form>
                            </td>

                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="text-primary me-2"
                                   title="Xem chi tiết">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Không có đơn hàng
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<style>
.card { border-radius: 12px }
.table-hover tbody tr:hover { background-color: #f1f3f5 }
</style>
@endsection
