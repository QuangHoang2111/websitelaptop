@extends('layouts.admin')
@section('content')

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0">Thương hiệu</h3>
        <a href="{{ route('admin.brands.create') }}" class="btn btn-primary px-3">
            <i class="bi bi-plus-lg me-1"></i> Thêm mới
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 200px;">STT</th>
                            <th>Thương hiệu</th>
                            <th style="width: 300px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($brands as $key => $brand)
                        <tr>
                            <td>{{ $brands->firstItem() + $key }}</td>

                            <td class="d-flex align-items-center">
                                <img src="{{ asset('storage/' . $brand->image) }}"
                                     alt="{{ $brand->name }}"
                                     width="40"
                                     height="40"
                                     class="me-2 rounded">
                                {{ $brand->name }}
                            </td>

                            <td>
                                <a href="{{ route('admin.brands.edit', $brand->id) }}"
                                    class="btn btn-sm btn-outline-primary me-2">Sửa</a>

                             <button
                                type="button" class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal" data-bs-target="#deleteBrandModal"
                                data-action="{{ route('admin.brands.destroy', $brand->id) }}"
                            >Xóa</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                Không có thương hiệu
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $brands->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteBrandModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">

            <div class="modal-header">
                <h5 class="modal-title fw-bold text-danger">
                    Xác nhận xóa thương hiệu
                </h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="mb-0">
                    Xác nhận <strong>xóa thương hiệu này</strong>?
                    <br>
                </p>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Hủy
                </button>

                <form id="deleteBrandForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn btn-danger">
                        Xác nhận xóa
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('deleteBrandModal');
    const form  = document.getElementById('deleteBrandForm');

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
.table-hover tbody tr:hover {
    background-color: #f1f3f5;
}
.btn-primary {
    border-radius: 8px;
}
</style>
@endsection
