@extends('layouts.admin')
@section('content')

<div class="card p-4">
    <h4 class="mb-3">Chỉnh sửa sản phẩm</h4>

    <form id="productForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" required value="{{ $product->name }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Slug <span class="text-danger">*</span> </label>
            <input type="text" name="slug" class="form-control" value="{{ $product->slug }}">
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Danh mục <span class="text-danger">*</span> </label>
                <select name="categoryid" class="form-select">
                    <option value="">-- Chưa phân loại --</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ $product->categoryid==$c->id?'selected':'' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Thương hiệu <span class="text-danger">*</span> </label>
                <select name="brandid" class="form-select">
                    <option value="">-- Chưa phân loại --</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}" {{ $product->brandid==$b->id?'selected':'' }}>
                            {{ $b->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Giá nhập <span class="text-danger">*</span> </label>
                <input type="text" id="costprice_display" class="form-control"value="{{ number_format($product->costprice) }}">
                <input type="hidden" name="costprice" id="costprice"value="{{ $product->costprice }}">        
            </div>

            <div class="col-md-4 mb-3">
                <input type="text" id="regularprice_display" class="form-control"value="{{ number_format($product->regularprice) }}">
                <input type="hidden" name="regularprice" id="regularprice"value="{{ $product->regularprice }}">
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Loại giảm giá</label>
                    <select id="discount_type" class="form-select">
                        <option value="">-- Không giảm --</option>
                        <option value="percent">Giảm theo %</option>
                        <option value="fixed">Giảm theo tiền</option>
                    </select>
                </div>  

                <div class="col-md-4 mb-3">
                    <label class="form-label">Giá trị giảm</label>
                    <input type="number" id="discount_value" class="form-control" min="0">
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <input type="text" id="saleprice_display" class="form-control" readonly>
                <input type="hidden" name="saleprice" id="saleprice">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">sku <span class="text-danger">*</span> </label>
                <input type="text" name="sku" class="form-control" required value="{{ $product->sku }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Số lượng <span class="text-danger">*</span> </label>
                <input type="number" name="stocks" class="form-control" required value="{{ $product->stocks }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh chính</label>
            <div id="mainDropzone" style="border:2px dashed #777;padding:20px;text-align:center;cursor:pointer;">
                Thêm ảnh chính
            </div>
            <input type="file" name="image" id="mainImageInput" class="d-none" accept="image/*">
            <img id="mainPreview"
                 src="{{ $product->image ? asset('storage/'.$product->image) : '' }}"
                 style="max-width:200px;margin-top:10px;{{ $product->image?'':'display:none' }};border-radius:8px;">
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh phụ</label>
            <div id="galleryDropzone" style="border:2px dashed #777;padding:20px;text-align:center;cursor:pointer;">
                Thêm ảnh phụ
            </div>
            <input type="file" name="gallery[]" id="galleryInput" class="d-none" multiple accept="image/*">
            <div id="galleryPreview" class="d-flex flex-wrap mt-2">
                @foreach(json_decode($product->images ?? '[]', true) as $img)
                    <div class="preview-wrapper">
                        <img src="{{ asset('storage/'.$img) }}">
                    </div>
                @endforeach
            </div>
        </div>

        <h5>Thông số kỹ thuật</h5>

        @foreach($attributes as $attr)
            <div class="mb-2">
                <label class="form-label">
                    {{ $attr->name }}
                    @if($attr->unit) ({{ $attr->unit }}) @endif
                </label>

                <input type="text"name="attributes[{{ $attr->id }}]"class="form-control"
                    value="{{ $product->attributeValues
                    ->firstWhere('attrid', $attr->id)
                    ->value ?? '' }}">
            </div>
        @endforeach
        
        <div class="mb-3">
            <label class="form-label">Mô tả ngắn</label>
            <textarea name="shortdescription"
                    class="form-control"
                    rows="3">{{ $product->shortdescription }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả chi tiết</label>
            <textarea name="description"
                    class="form-control"
                    rows="6">{{ $product->description }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Sản phẩm nổi bật</label>
            <select name="isfeatured" class="form-select">
                <option value="0">Không</option>
                <option value="1">Có</option>
            </select>
        </div>
        
        <button class="btn btn-primary w-100">Cập nhật sản phẩm</button>
        <div id="alertArea" class="mt-3"></div>
    </form>
</div>

<style>
.preview-wrapper{position:relative;margin:6px}
.preview-wrapper img{width:120px;height:120px;object-fit:cover;border-radius:8px}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

$('input[name="name"]').on('input', function () {
    $('input[name="slug"]').val(
        $(this).val().toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'')
    );
});

$('#saleprice').val('');

$('#mainDropzone').click(()=>$('#mainImageInput').click());
$('#mainImageInput').on('change',function(){
    let f=this.files[0];
    if(f) $('#mainPreview').attr('src',URL.createObjectURL(f)).show();
});

let galleryFiles=new DataTransfer();
$('#galleryDropzone').click(()=>$('#galleryInput').click());
$('#galleryInput').on('change',function(e){
    [...e.target.files].forEach(f=>galleryFiles.items.add(f));
    renderGallery();
});

function renderGallery(){
    $('#galleryPreview').html('');
    $('#galleryInput')[0].files = galleryFiles.files;

    [...galleryFiles.files].forEach((file, index) => {
        let url = URL.createObjectURL(file);

        $('#galleryPreview').append(`
            <div class="preview-wrapper">
                <img src="${url}">
                <button type="button"
                        class="remove-btn"
                        onclick="removeGalleryImage(${index})">
                    ×
                </button>
            </div>
        `);
    });
}

function removeGalleryImage(index){
    let newDT = new DataTransfer();

    [...galleryFiles.files].forEach((file, i) => {
        if(i !== index){
            newDT.items.add(file);
        }
    });

    galleryFiles = newDT;
    renderGallery();
}


function calculateSalePrice(){
    let regular = Number($('#regularprice').val()) || 0;
    let type = $('#discount_type').val();
    let value = Number($('#discount_value').val()) || 0;

    if(!type || value<=0){
        $('#saleprice').val('');
        return;
    }

    let sale = regular;
    if(type==='percent') sale = regular - (regular*value/100);
    if(type==='fixed') sale = regular - value;
    if(sale<0) sale=0;

    $('#saleprice').val(Math.round(sale));
}

$('#discount_type,#discount_value,#regularprice')
    .on('input change keyup',calculateSalePrice);

$('#productForm').submit(function(e){
    e.preventDefault();
    let fd=new FormData(this);
    fd.delete('gallery[]');
    [...galleryFiles.files].forEach(f=>fd.append('gallery[]',f));

    $.ajax({
    url: "{{ route('admin.products.update',$product->id) }}",
    method: "POST",
    data: fd,
    processData: false,
    contentType: false,
    success: function (r) {
        $('#alertArea').html(
            '<div class="alert alert-success">' + r.message + '</div>'
        );
    },
    error: function (xhr) {
        let msg = 'Có lỗi xảy ra';

        if (xhr.responseJSON && xhr.responseJSON.errors) {
            msg = Object.values(xhr.responseJSON.errors)
                .map(err => err[0])
                .join('<br>');
        }

        $('#alertArea').html(
            '<div class="alert alert-danger">' + msg + '</div>'
        );

        setTimeout(() => {
            $('#alertArea').fadeOut(300, function () {
                $(this).html('').show();
            });
        }, 4000);
    }
});


function formatNumber(val) {
    return val.replace(/\D/g, '')
              .replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

function unformatNumber(val) {
    return val.replace(/,/g, '');
}

$('#costprice_display').on('input', function () {
    let raw = unformatNumber(this.value);
    this.value = formatNumber(raw);
    $('#costprice').val(raw);
});
$('#regularprice_display').on('input', function () {
    let raw = unformatNumber(this.value);
    this.value = formatNumber(raw);
    $('#regularprice').val(raw).trigger('change');
});

</script>

<style>
.preview-wrapper{position:relative;margin:6px}
.preview-wrapper img{width:120px;height:120px;object-fit:cover;border-radius:8px}

.remove-btn{
    position:absolute;
    top:-6px;
    right:-6px;
    width:22px;
    height:22px;
    border:none;
    border-radius:50%;
    background:#dc3545;
    color:#fff;
    font-weight:bold;
    cursor:pointer;
    line-height:22px;
    text-align:center;
}
</style>

@endsection
