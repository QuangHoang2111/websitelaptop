@extends('layouts.admin')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class = "fw-bold m-0">Thương hiệu</h3>
        <a href="{{ route('brand.create')}}" class = "btn btn-primary px-3">
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
                            <th class="fw-bold">Thương hiệu</th>
                            <th class="fw-bold">Slug</th>
                            <th class="fw-bold" style="width:300px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($brands as $key => $brand)
                        <tr>
                            <td>{{$brands->firstItem() + $key}}</td>
                            <td class ="d-flex align-items-center">
                                <img src="{{ asset('storage/' . $brand->image)}}"
                                        alt="{{ $brand->name}}"
                                        width="40" height ="40"
                                        class="me-2 rounded">
                                {{ $brand->name}}
                            </td>
                            <td>{{$brand->slug}}</td>
                            <td>
                                <a href="{{ route('brand.edit', $brand->id) }}" class="text-primary me-2" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>  
                                <form action="{{ route('brand.delete', $brand->id) }}" method ="POST" class="d-inline" onsubmit="return confirm('Xóa thương hiệu?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>          
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Không có thương hiệu
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-3">
                {{ $brands->links('pagination::bootstrap-5')}}
            </div>
        </div>
    </div>
</div>
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