@extends('layouts.admin')
@section('content')

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0">Voucher</h3>

        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary px-3">
            <i class="bi bi-plus-lg me-1"></i> Thêm mới
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-bold" style="width: 80px;">STT</th>
                            <th class="fw-bold">Mã voucher</th>
                            <th class="fw-bold">Loại</th>
                            <th class="fw-bold">Giá trị</th>
                            <th class="fw-bold">Hạn sử dụng</th>
                            <th class="fw-bold">Trạng thái</th>
                            <th class="fw-bold" style="width:150px;">Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($vouchers as $key => $voucher)
                            @php
                                $expired = \Carbon\Carbon::parse($voucher->expdate)->isPast();
                            @endphp

                            <tr>
                                <td>{{ $vouchers->firstItem() + $key }}</td>

                                <td class="fw-semibold">
                                    {{ $voucher->code }}
                                </td>

                                <td>
                                    {{ $voucher->type === 'percent' ? 'Giảm %' : 'Giảm tiền' }}
                                </td>

                                <td>
                                    {{ $voucher->type === 'percent'
                                        ? $voucher->cartvalue . '%'
                                        : number_format($voucher->cartvalue, 0, ',', '.') . ' đ'
                                    }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($voucher->expdate)->format('d/m/Y') }}
                                </td>

                                <td>
                                    @if($expired)
                                        <span class="badge bg-danger">Hết hạn</span>
                                    @else
                                        <span class="badge bg-success">Còn hiệu lực</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('admin.vouchers.edit', $voucher->id) }}"
                                        class="btn btn-sm btn-outline-primary me-2">Sửa
                                    </a>

                                   <button type="button" class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="modal"data-bs-target="#deleteVoucherModal"
                                        data-action="{{ route('admin.vouchers.destroy', $voucher->id) }}"
                                    > Xóa </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Không có voucher
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $vouchers->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteVoucherModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">

            <div class="modal-header">
                <h5 class="modal-title fw-bold text-danger">
                    Xác nhận xóa voucher
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="mb-0">
                    Xác nhận <strong>xóa voucher này</strong>?
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Hủy
                </button>

                <form id="deleteVoucherForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        Xác nhận xóa
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('deleteVoucherModal');
    const form  = document.getElementById('deleteVoucherForm');

    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const action = button.getAttribute('data-action');
        form.setAttribute('action', action);
    });
});
</script>

<style>
body {
    background-color: #f8fafc !important;
}
.card {
    border-radius: 12px;
}
.table thead th {
    font-weight: 600;
    color: #333;
}
.table-hover tbody tr:hover {
    background-color: #f1f3f5;
}
.btn-primary {
    border-radius: 8px;
}
</style>

@endsection
