<div class="row g-4">
@forelse($products as $product)
    <div class="col-md-4">
        <a href="{{ route('products.detail', $product->slug) }}"
           class="text-decoration-none text-dark">

            <div class="card h-100 shadow-sm">
                <img src="{{ asset('storage/'.$product->image) }}"
                     class="card-img-top"
                     style="height:180px;object-fit:cover">

                <div class="card-body">
                    <h6 class="mb-1 text-truncate">
                        {{ $product->name }}
                    </h6>

                    @if($product->saleprice > 0)
                        <div>
                            <strong class="text-danger fs-6">
                                {{ number_format($product->final_price) }} ₫
                            </strong>
                            <span class="text-muted text-decoration-line-through ms-1">
                                {{ number_format($product->regularprice) }} ₫
                            </span>
                        </div>
                    @else
                        <strong class="text-danger fs-6">
                            {{ number_format($product->regularprice) }} ₫
                        </strong>
                    @endif
                </div>
            </div>

        </a>
    </div>
@empty
    <p class="text-muted">Không có sản phẩm phù hợp</p>
@endforelse
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $products->links('pagination::bootstrap-5') }}
</div>
 