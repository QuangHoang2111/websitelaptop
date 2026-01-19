@extends('layouts.admin')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class = "fw-bold m-0">Danh mục</h3>
        <a href="{{ route('admin.categories.create')}}" class = "btn btn-primary px-3">
            <i class = "bi bi-plus-lg me-1"></i> Thêm mới
        </a>
    </div>
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-bold" style="width: 200px;">STT</th>
                            <th class="fw-bold">Danh mục</th>
                            <th class="fw-bold" style="width:300px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $key => $category)
                        <tr>
                            <td>{{$categories->firstItem() + $key}}</td>
                            <td>{{$category->name}}</td>
                            <td>
                               <a href="{{ route('admin.categories.edit', $category->id) }}"
                                    class="btn btn-sm btn-outline-primary me-2">Sửa
                                </a>
                            <button
                                type="button" class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal" data-bs-target="#deleteCategoryModal"
                                data-action="{{ route('admin.categories.destroy', $category->id) }}"
                            >Xóa</button>

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Không có danh mục
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-3">
                {{ $categories->links('pagination::bootstrap-5')}}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">

            <div class="modal-header">
                <h5 class="modal-title fw-bold text-danger">
                    Xác nhận xóa danh mục
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="mb-0">
                    Xác nhận <strong>xóa danh mục</strong>?
                    <br>
                </p>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Hủy
                </button>

                <form id="deleteCategoryForm" method="POST">
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
    const modal = document.getElementById('deleteCategoryModal');
    const form  = document.getElementById('deleteCategoryForm');

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