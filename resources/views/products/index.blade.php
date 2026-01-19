@extends('layouts.app')

@section('title','Sản phẩm')

@section('content')
<div class="row">

    <div class="col-md-3">
        <form id="filterForm" class="card p-3">

            <div class="accordion" id="filterAccordion">

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button type="button"
                                class="accordion-button"
                                data-bs-toggle="collapse"
                                data-bs-target="#catFilter">
                            Danh mục
                        </button>
                    </h2>
                    <div id="catFilter" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            @foreach($categories as $c)
                                <label class="d-block">
                                    <input type="checkbox"
                                           name="categories[]"
                                           value="{{ $c->id }}">
                                    {{ $c->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button type="button"
                                class="accordion-button collapsed"
                                data-bs-toggle="collapse"
                                data-bs-target="#brandFilter">
                            Thương hiệu
                        </button>
                    </h2>
                    <div id="brandFilter" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @foreach($brands as $b)
                                <label class="d-block">
                                    <input type="checkbox"
                                           name="brands[]"
                                           value="{{ $b->id }}">
                                    {{ $b->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                @foreach($attributes as $attr)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button type="button"
                                class="accordion-button collapsed"
                                data-bs-toggle="collapse"
                                data-bs-target="#attr{{ $attr->id }}">
                            {{ $attr->name }}
                        </button>
                    </h2>
                    <div id="attr{{ $attr->id }}"
                         class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @foreach($attr->values as $v)
                                <label class="d-block">
                                    <input type="checkbox"
                                           name="attrs[{{ $attr->id }}][]"
                                           value="{{ $v->value }}">
                                    {{ $v->value }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button type="button"
                                class="accordion-button collapsed"
                                data-bs-toggle="collapse"
                                data-bs-target="#priceFilter">
                            Giá
                        </button>
                    </h2>
                    <div id="priceFilter" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <input type="number"
                                   name="price_min"
                                   class="form-control mb-2"
                                   placeholder="Từ">
                            <input type="number"
                                   name="price_max"
                                   class="form-control"
                                   placeholder="Đến">
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <div class="col-md-9">
        <div id="productList">
            @include('products._list', ['products'=>$products])
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#filterForm').on('change', 'input', function () {
    $.ajax({
        url: "{{ route('products.index') }}",
        type: "GET",
        data: $('#filterForm').serialize(),
        success: function (html) {
            $('#productList').html(html);
        }
    });
});

$(document).on('click', '#productList .pagination a', function(e) {
    e.preventDefault();

    let url = $(this).attr('href');

    $.ajax({
        url: url,
        type: "GET",
        data: $('#filterForm').serialize(),
        success: function (html) {
            $('#productList').html(html);
            window.history.pushState({}, '', url);
        }
    });
});
</script>
@endpush
