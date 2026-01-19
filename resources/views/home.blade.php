@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')

<div class="container my-4">
    <div class="slider-wrapper mx-auto">
        <div id="homeSlider"
             class="carousel slide"
             data-bs-ride="carousel"
             data-bs-interval="3000"
             data-bs-pause="false">

            @if($sliders->count() > 1)
                <div class="carousel-indicators">
                    @foreach($sliders as $i => $slider)
                        <button type="button"
                                data-bs-target="#homeSlider"
                                data-bs-slide-to="{{ $i }}"
                                class="{{ $i === 0 ? 'active' : '' }}">
                        </button>
                    @endforeach
                </div>
            @endif

            <div class="carousel-inner slider-box">
                @forelse($sliders as $index => $slider)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <a href="{{ $slider->link ?? '#' }}">
                            <img src="{{ asset('storage/'.$slider->image) }}"
                                 class="slider-img"
                                 alt="{{ $slider->title }}">
                        </a>

                        @if($slider->title)
                            <div class="carousel-caption d-none d-md-block">
                                <div class="bg-dark bg-opacity-50 px-3 py-2 rounded">
                                    <h5 class="mb-0">{{ $slider->title }}</h5>
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="carousel-item active">
                        <img src="https://via.placeholder.com/800x300" class="slider-img">
                    </div>
                @endforelse
            </div>

            @if($sliders->count() > 1)
                <button class="carousel-control-prev" type="button"
                        data-bs-target="#homeSlider" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button"
                        data-bs-target="#homeSlider" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            @endif

        </div>
    </div>
</div>

<div class="container mt-5">
    <h3 class="fw-bold mb-4">Sản phẩm nổi bật</h3>

    <div class="row g-4">
        @foreach($featuredProducts as $product)

            @php
                $cpu = null;
                $ram = null;
                $gpu = null;

                foreach (($attrValues[$product->id] ?? []) as $attr) {
                    if ($attr->attribute->name === 'CPU') $cpu = $attr->value;
                    if ($attr->attribute->name === 'RAM') $ram = $attr->value;
                    if ($attr->attribute->name === 'GPU') $gpu = $attr->value;
                }
            @endphp

            <div class="col-6 col-md-3">
                <a href="{{ route('products.detail', $product->slug) }}" class="product-link-wrapper">
                    <div class="card h-100 shadow-sm product-card">
                        <img src="{{ asset('storage/'.$product->image) }}"
                             class="card-img-top product-img"
                             alt="{{ $product->name }}">

                        <div class="card-body p-2">

                            <h4 class="product-name fw-semibold mb-1 text-truncate">{{ $product->name }}</h4>

                            <div class="spec-box mb-2">
                                <div class="spec-main">
                                    @if($cpu)
                                        <span class="spec-item"><strong>CPU:</strong> {{ $cpu }}</span>
                                    @endif
                                    @if($ram)
                                        <span class="spec-item"><strong>RAM:</strong> {{ $ram }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="price-box">
                                <div class="price-sale">
                                    {{ number_format(
                                        $product->saleprice > 0
                                            ? $product->saleprice
                                            : $product->regularprice
                                    ) }} ₫
                                </div>

                                @if($product->saleprice > 0)
                                    <div class="price-regular">
                                        {{ number_format($product->regularprice) }} ₫
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>

                </a>
            </div>
        @endforeach
    </div>
</div>

@endsection

<style>
.slider-wrapper { max-width: 850px; }
.slider-box { height: 300px; overflow: hidden; border-radius: 16px; }
.slider-img { width: 100%; height: 300px; object-fit: cover; }

@media (max-width: 768px) {
    .slider-box { height: 200px; }
    .slider-img { height: 200px; }
}

.product-link-wrapper {
    text-decoration: none;
    color: inherit;
    display: block;
    height: 100%;
}

.product-card {
    border-radius: 14px;
    border: 1px solid rgba(0,0,0,0.15);
    transition: box-shadow .2s ease, transform .2s ease;
}

.product-link-wrapper:hover .product-card {
    box-shadow: 0 6px 16px rgba(0,0,0,0.12);
    transform: translateY(-2px);
}

.product-img { height: 180px; object-fit: cover; }

.product-name {
    font-size: 18px;
    line-height: 1.3;
    color: #212529;
    transition: color .2s ease;
}

.product-link-wrapper:hover .product-name {
    color: #0d6efd;
}

.spec-box { display: flex; flex-direction: column; gap: 3px; }

.spec-main {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.spec-item {
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 3px 7px;
    font-size: 14px;
    font-weight: 700;
    color: #212529;
    background: #f8f9fa;
    white-space: nowrap;
}

.price-box { margin-top: 6px; }

.price-sale {
    color: #dc3545;
    font-weight: 700;
    font-size: 20px;
}

.price-regular {
    color: #888;
    font-size: 18px;
    text-decoration: line-through;
}
</style>
