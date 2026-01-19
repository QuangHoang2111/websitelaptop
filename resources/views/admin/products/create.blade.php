@extends('layouts.admin')
@section('content')

<div class="card p-4">
    <h4 class="mb-3">Thêm sản phẩm</h4>

    <form id="productForm" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Tên sản phẩm <span class="text-danger">*</span> </label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Slug <span class="text-danger">*</span> </label>
            <input type="text" name="slug" class="form-control" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Danh mục <span class="text-danger">*</span> </label>
                <select name="categoryid" class="form-select" required>
                    <option value="">-- Chọn --</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Thương hiệu <span class="text-danger">*</span> </label>
                <select name="brandid" class="form-select" required>
                    <option value="">-- Chọn --</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Giá nhập <span class= "text-danger">*</span></label>
                <input type="text" id="costprice_display" class="form-control">
                <input type="hidden" name="costprice" id="costprice">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Giá niêm yết <span class= "text-danger">*</span></label>
                <input type="text" id="regularprice_display" class="form-control">
                <input type="hidden" name="regularprice" id="regularprice">
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
                <label class="form-label">Giá khuyến mãi <span class= "text-danger">*</span></label>
                <input type="text" id="saleprice_display" class="form-control" readonly>
                <input type="hidden" name="saleprice" id="saleprice">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">sku <span class="text-danger">*</span> </label>
                <input type="text" name="sku" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Số lượng <span class="text-danger">*</span> </label>
                <input type="number" name="stocks" class="form-control" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Ảnh chính</label>
            <div id="mainDropzone"style="border:2px dashed #777; padding:20px; text-align:center; cursor:pointer;">Thêm ảnh chính</div>
            <input type="file" name="image" id="mainImageInput" class="d-none" accept="image/*">
            <img id="mainPreview" style="max-width:200px; margin-top:10px; display:none; border-radius:8px;">
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh phụ</label>
            <div id="galleryDropzone"style="border:2px dashed #777; padding:20px; text-align:center; cursor:pointer;">Thêm ảnh phụ</div>
            <input type="file"
                name="gallery[]"
                id="galleryInput"
                class="d-none"
                multiple
                accept="image/*">
            <div id="galleryPreview" class="d-flex flex-wrap mt-2"></div>
        </div>
        <h5>Thông số kỹ thuật</h5>
        @foreach($attributes as $attr)
            <div class="mb-2">
                <label class="form-label">
                    {{ $attr->name }} @if($attr->unit) ({{ $attr->unit }}) @endif
                </label>
                <input type="text" name="attributes[{{ $attr->id }}]" class="form-control">
            </div>
        @endforeach

        <div class="mb-3">
            <label class="form-label">Mô tả ngắn</label>
            <textarea name="shortdescription" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả chi tiết</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Sản phẩm nổi bật</label>
            <select name="isfeatured" class="form-select">
                <option value="0">Không</option>
                <option value="1">Có</option>
            </select>
        </div>

        <button class="btn btn-primary w-100">Thêm sản phẩm</button>
        <div id="alertArea" class="mt-3"></div>
    </form>
</div>

<style>

    .preview-wrapper{
    position:relative;
    margin:6px;
}
.preview-wrapper img{
    width:120px;
    height:120px;
    object-fit:cover;
    border-radius:8px;
}
.remove-btn{
    position:absolute;
    top:-6px;
    right:-6px;
    z-index:10;
    background:#dc3545;
    color:#fff;
    border:none;
    width:22px;
    height:22px;
    border-radius:50%;
    cursor:pointer;
}

</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

$('input[name="name"]').on('input', function () {
    $('input[name="slug"]').val(
        $(this).val()
            .toLowerCase()
            .replace(/ /g, '-')
            .replace(/[^\w-]+/g, '')
    );
});

$('#mainDropzone').click(() => $('#mainImageInput').click());

$('#mainDropzone').on('dragover', function(e){
    e.preventDefault();
    $(this).css('border-color','blue');
});

$('#mainDropzone').on('dragleave', function(){
    $(this).css('border-color','#777');
});

$('#mainDropzone').on('drop', function(e){
    e.preventDefault();
    $(this).css('border-color','#777');

    let file = e.originalEvent.dataTransfer.files[0];
    if(file){
        $('#mainImageInput')[0].files = e.originalEvent.dataTransfer.files;
        $('#mainPreview')
            .attr('src', URL.createObjectURL(file))
            .removeClass('d-none')
            .show();
    }
});

$('#mainImageInput').on('change', function(){
    let f = this.files[0];
    if(f){
        $('#mainPreview')
            .attr('src', URL.createObjectURL(f))
            .removeClass('d-none')
            .show();
    }
});

let galleryFiles = new DataTransfer();

$('#galleryDropzone').click(() => $('#galleryInput').click());

$('#galleryDropzone').on('dragover', function(e){
    e.preventDefault();
    $(this).css('border-color','blue');
});

$('#galleryDropzone').on('dragleave', function(){
    $(this).css('border-color','#777');
});

$('#galleryDropzone').on('drop', function(e){
    e.preventDefault();
    $(this).css('border-color','#777');

    let files = e.originalEvent.dataTransfer.files;
    [...files].forEach(f => galleryFiles.items.add(f));
    renderGallery();
});

$('#galleryInput').on('change', function(e){
    [...e.target.files].forEach(f => galleryFiles.items.add(f));
    renderGallery();
});

function renderGallery(){
    $('#galleryPreview').html('');
    $('#galleryInput')[0].files = galleryFiles.files;

    [...galleryFiles.files].forEach((f,i)=>{
        $('#galleryPreview').append(`
            <div class="preview-wrapper">
                <img src="${URL.createObjectURL(f)}">
                <button type="button" class="remove-btn" data-i="${i}">×</button>
            </div>
        `);
    });
}

$(document).on('click','.remove-btn',function(){
    let index = $(this).data('i');
    let dt = new DataTransfer();

    [...galleryFiles.files].forEach((f,i)=>{
        if(i !== index) dt.items.add(f);
    });

    galleryFiles = dt;
    renderGallery();
});

function calculateSalePrice() {
    let regular = parseFloat($('#regularprice').val()) || 0;
    let type = $('#discount_type').val();
    let value = parseFloat($('#discount_value').val()) || 0;

    let sale = regular;

    if (type === 'percent') sale = regular - (regular * value / 100);
    if (type === 'fixed') sale = regular - value;
    if (sale < 0) sale = 0;

    $('#saleprice').val(Math.round(sale));
    $('#saleprice_display').val(
        Math.round(sale).toLocaleString('en-US')
    );

}

$('#discount_type, #discount_value, #regularprice')
    .on('input change', calculateSalePrice);

$('#productForm').on('input change','input:not([type=file]), textarea, select',function(){
    let name=$(this).attr('name');
    if(name) localStorage.setItem('product_'+name,$(this).val());
});

$(document).ready(()=>{
    $('#productForm')
        .find('input:not([type=file]), textarea, select')
        .each(function(){
            let n=$(this).attr('name');
            if(n && localStorage.getItem('product_'+n))
                $(this).val(localStorage.getItem('product_'+n));
        });
});

$('#productForm').submit(function(e){
    e.preventDefault();

    let fd=new FormData(this);
    fd.delete('gallery[]');
    [...galleryFiles.files].forEach(f=>fd.append('gallery[]',f));

    $.ajax({
        url:"{{ route('admin.products.store') }}",
        method:"POST",
        data:fd,
        processData:false,
        contentType:false,
        success:r=>{
            $('#alertArea').html('<div class="alert alert-success">'+r.message+'</div>');
            Object.keys(localStorage).forEach(k=>k.startsWith('product_')&&localStorage.removeItem(k));
            this.reset();
            $('#mainPreview').hide();
            $('#galleryPreview').html('');
            galleryFiles=new DataTransfer();
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
});

function formatNumber(val) {
    return val.replace(/\D/g,'')
              .replace(/\B(?=(\d{3})+(?!\d))/g,',');
}
function unformatNumber(val) {
    return val.replace(/,/g,'');
}

$('#costprice_display').on('input',function(){
    let raw=unformatNumber(this.value);
    this.value=formatNumber(raw);
    $('#costprice').val(raw);
});

$('#regularprice_display').on('input',function(){
    let raw=unformatNumber(this.value);
    this.value=formatNumber(raw);
    $('#regularprice').val(raw).trigger('change');
});

</script>
@endsection
