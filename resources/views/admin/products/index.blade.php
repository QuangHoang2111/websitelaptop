@extends('layouts.admin')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class = "fw-bold m-0">Sản phẩm</h3>
        <a href="{{ route('admin.products.create')}}" class = "btn btn-primary px-3">
            <i class = "bi bi-plus-lg me-1"></i> Thêm mới
        </a>
    </div>
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-bold" style="width: 120px;">STT</th>
                            <th class="fw-bold">Sản phẩm</th>
                            <th class="fw-bold">Giá nhập</th>
                            <th class="fw-bold">Giá niêm yết</th>
                            <th class="fw-bold">Giá khuyến mãi</th>
                            <th class="fw-bold">SKU</th>
                            <th class="fw-bold">Nổi bật</th>
                            <th class="fw-bold">Số lượng</th>
                            <th class="fw-bold" style="width:300px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id= "product-table-body">
                        @forelse ($products as $key => $product)
                        <tr>
                            <td>{{$products->firstItem() + $key}}</td>
                            <td class ="d-flex align-items-center">
                                <img src="{{ asset('storage/' . $product->image)}}"
                                        alt="{{ $product->name}}"
                                        width="40" height ="40"
                                        class="me-2 rounded">
                                {{$product->name}}
                            </td>
                            
                            <td>{{ number_format($product->costprice, 0, ',', '.') }} đ</td>
                            <td>{{ number_format($product->regularprice, 0, ',', '.') }} đ</td>
                            <td>
                                {{ $product->saleprice > 0
                                    ? number_format($product->saleprice, 0, ',', '.') . ' đ'
                                    : '—'
                                }}
                            </td>

                            <td>{{$product->sku}}</td>
                            <td>{{$product->isfeatured ? 'Có' : 'Không'}}</td>
                            <td>
                                @if ($product->stocks > 0)
                                    <span class="badge bg-success">
                                        {{ $product->stocks }} sp
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        Hết hàng
                                    </span>
                                @endif
                            </td>
                            <td>
                               <a href="{{ route('admin.products.edit', $product->id) }}"
                                    class="btn btn-sm btn-outline-primary me-2">Sửa
                                </a>

                            <button
                                type="button" class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal" data-bs-target="#deleteProductModal"
                                data-action="{{ route('admin.products.destroy', $product->id) }}"
                            >Xóa </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Không có sản phẩm
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-3">
                {{ $products->links('pagination::bootstrap-5')}}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteProductModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">

            <div class="modal-header">
                <h5 class="modal-title fw-bold text-danger">
                    Xác nhận xóa sản phẩm
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="mb-0">
                    Xác nhận <strong>xóa sản phẩm này</strong>?
                    <br>
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Hủy
                </button>

                <form id="deleteProductForm" method="POST">
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
    const modal = document.getElementById('deleteProductModal');
    const form  = document.getElementById('deleteProductForm');

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

.table th,
.table td {
    white-space: nowrap;
    vertical-align: middle nhớ
}

</style>


@endsection