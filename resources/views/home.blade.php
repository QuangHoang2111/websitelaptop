@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
<div class="container mt-4">

    <!-- Banner -->
    <div id="mainBanner" class="carousel slide mb-5" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://cdn.tgdd.vn/2023/07/banner/laptop-800-200-800x200.png" class="d-block w-100 rounded" alt="Laptop banner 1">
            </div>
            <div class="carousel-item">
                <img src="https://cdn.tgdd.vn/2023/07/banner/laptopgaming-800-200-800x200.png" class="d-block w-100 rounded" alt="Laptop banner 2">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#mainBanner" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainBanner" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <!-- Danh sách sản phẩm -->
    <h3 class="mb-4">Sản phẩm nổi bật</h3>
    <div class="row">
        @foreach ([
            ['name' => 'Asus TUF Gaming F15', 'price' => '22.990.000₫', 'img' => 'https://cdn.tgdd.vn/Products/Images/44/310652/asus-tuf-gaming-f15-fx507zu4-lp049w-thumb-600x600.jpg'],
            ['name' => 'MacBook Air M2', 'price' => '30.990.000₫', 'img' => 'https://cdn.tgdd.vn/Products/Images/44/303863/apple-macbook-air-m2-2022-600x600.jpg'],
            ['name' => 'Dell Inspiron 15', 'price' => '17.990.000₫', 'img' => 'https://cdn.tgdd.vn/Products/Images/44/302890/dell-inspiron-15-3520-600x600.jpg'],
        ] as $product)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <img src="{{ $product['img'] }}" class="card-img-top" alt="{{ $product['name'] }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $product['name'] }}</h5>
                    <p class="card-text text-primary fw-bold">{{ $product['price'] }}</p>
                    <a href="#" class="btn btn-outline-primary btn-sm">Xem chi tiết</a>
                    <a href="#" class="btn btn-primary btn-sm">Thêm vào giỏ</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
