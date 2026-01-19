@extends('layouts.app')

@section('title', $product->name)

@section('content')

<div class="container my-4">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="border rounded mb-3">
                <img id="mainImage"
                     src="{{ asset('storage/'.$product->image) }}"
                     class="img-fluid w-100"
                     style="height:420px;object-fit:cover">
            </div>

            <div class="d-flex gap-2 flex-wrap">
                @foreach($gallery as $img)
                    <img src="{{ asset('storage/'.$img) }}"
                         class="border rounded thumb-img"
                         onclick="changeImage(this.src)">
                @endforeach
            </div>
        </div>

        <div class="col-md-6">
            <h2 class="fw-bold">{{ $product->name }}</h2>

            <div class="my-3">
                <span class="text-danger fs-3 fw-bold">
                    {{ number_format($product->saleprice > 0 ? $product->saleprice : $product->regularprice) }} ₫
                </span>

                @if($product->saleprice > 0)
                    <div class="text-muted text-decoration-line-through">
                        {{ number_format($product->regularprice) }} ₫
                    </div>
                @endif
            </div>

            <p class="text-muted">{{ $product->shortdescription }}</p>

            <div class="mb-3">
                <strong class="text-success">
                    Còn {{ $product->stocks }} sản phẩm
                </strong>
            </div>

            <div class="d-flex gap-3 mt-4">
                <form action="{{ route('cart.add') }}" method="POST" class="flex-fill">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="qty" value="1">
                    <button type="submit" class="btn btn-danger btn-lg w-100">
                        Mua ngay
                    </button>
                </form>

                <button
                    class="btn btn-outline-primary btn-lg flex-fill"
                    id="openCartModal"
                    data-product-id="{{ $product->id }}">
                    Thêm vào giỏ
                </button>
            </div>
        </div>
    </div>
</div>

<div id="cartOverlay" class="cart-overlay d-none">
    <div class="cart-modal">
        <h5 class="fw-bold mb-3">Thêm vào giỏ hàng</h5>

        <div class="d-flex align-items-center mb-3">
            <img src="{{ asset('storage/'.$product->image) }}" width="80" class="me-3 rounded">
            <div>
                <div class="fw-semibold">{{ $product->name }}</div>
                <div class="text-danger fw-bold">
                    {{ number_format($product->saleprice > 0 ? $product->saleprice : $product->regularprice) }} ₫
                </div>
            </div>
        </div>

        <input type="number"
               id="modalQty"
               class="form-control mb-3"
               value="1"
               min="1"
               max="{{ $product->stocks }}">

        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary flex-fill" id="closeModal">Hủy</button>
            <button class="btn btn-primary flex-fill" id="confirmAddCart">Xác nhận</button>
        </div>
    </div>
</div>

<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:99999">
    <div id="cartToast" class="toast text-bg-success border-0">
        <div class="d-flex">
            <div class="toast-body">
                Đã thêm vào giỏ hàng
            </div>
        </div>
    </div>
</div>

<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:99999">
    <div id="cartToastSuccess" class="toast text-bg-success border-0">
        <div class="toast-body">Đã thêm vào giỏ hàng</div>
    </div>

    <div id="cartToastError" class="toast text-bg-danger border-0">
        <div class="toast-body" id="cartErrorText"></div>
    </div>
</div>

<div class="container my-5">
    <div class="row g-4">
        <div class="col-md-7">
            <h4 class="fw-bold mb-3">Mô tả sản phẩm</h4>

            <div class="border rounded p-3 bg-white">
                {!! nl2br(($product->description)) !!}
            </div>
        </div>
        <div class="col-md-5">
            <h4 class="fw-bold mb-3">Thông số kỹ thuật</h4>

            <div class="border rounded bg-white">
                <table class="table table-striped mb-0">
                    <tbody>
                        @forelse($attrValues as $item)
                            <tr>
                                <th style="width:40%">
                                    {{ $item->attribute->name }}
                                </th>
                                <td>
                                    {{ $item->value }}
                                    @if($item->attribute->unit)
                                        {{ $item->attribute->unit }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">
                                    Chưa có thông số
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

<style>
.thumb-img{width:80px;height:80px;object-fit:cover;cursor:pointer}
.cart-overlay{
    position:fixed;inset:0;background:rgba(0,0,0,.6);
    display:flex;align-items:center;justify-content:center;z-index:9999
}
.cart-modal{
    background:#fff;max-width:400px;width:100%;
    border-radius:12px;padding:20px
}
</style>

@push('scripts')
<script>
function changeImage(src){
    document.getElementById('mainImage').src = src;
}

const overlay   = document.getElementById('cartOverlay');
const openBtn   = document.getElementById('openCartModal');
const closeBtn  = document.getElementById('closeModal');
const confirmBtn= document.getElementById('confirmAddCart');

openBtn.onclick  = () => overlay.classList.remove('d-none');
closeBtn.onclick = () => overlay.classList.add('d-none');

confirmBtn.onclick = () => {
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            product_id: openBtn.dataset.productId,
            qty: document.getElementById('modalQty').value
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            document.getElementById('cartErrorText').innerText = data.error;
            new bootstrap.Toast(
                document.getElementById('cartToastError'),
                { delay: 3000 }
            ).show();
            return;
        }

        overlay.classList.add('d-none');

        new bootstrap.Toast(
            document.getElementById('cartToastSuccess'),
            { delay: 2000 }
        ).show();
    });
};

</script>
@endpush
