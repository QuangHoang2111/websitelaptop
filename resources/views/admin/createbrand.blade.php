@extends('layouts.admin')
@section('content')

<div class ="card p-4">
    <h4 class="mb-3">Thêm thương hiệu</h4>

    <form id="brandform" enctype="multipart/form-data">
        @csrf 

        <div class="mb-3">
            <label class="form-label">Tên thương hiệu</label>
            <input type="text" name="name" class="form-control" placeholder="Nhập tên thương hiệu">
        </div>

        <div class="mb-3">
            <label class="form-label">Slug</label>
            <input type="text" name="slug" class="form-control" placeholder="Slug: apple,asus,...">
        </div>

        <div class="mb-3">
            <label class="form-label">Logo thương hiệu</label>
            
            <div id="dropzone"
                 style="border: 2px dashed #777; padding: 20px; text-align: center; cursor: pointer;">
                 Thêm ảnh thương hiệu
            </div>

            <input type="file" name="image" id="imageinput" class="d-none" accept="image/*">

            <img id="preview" src="" style="max-width: 200px; margin-top: 10px; display:none;">
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Thêm thương hiệu
        </button>

        <div id="alertarea" class="mt-3"></div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){

    //load giá trị
    if(localStorage.getItem('brand_name')){
        $('input[name="name"]').val(localStorage.getItem('brand_name'));
    }
    if(localStorage.getItem('brand_slug')){
        $('input[name="slug"]').val(localStorage.getItem('brand_slug'));
    }

    //lưu input vào localstorage

    $('input[name="name"]').on('input',function(){
        localStorage.setItem('brand_name',$(this).val());
    });
    $('input[name="slug"]').on('input', function(){
        localStorage.setItem('brand_slug',$(this).val());
    })

    // Dropzone click và preview

    $('#dropzone').click(function(){ $('#imageinput').click(); });

    $('#imageinput').on('change', function(){
        let file = this.files[0];
        if(file){
            let url = URL.createObjectURL(file);
            $('#preview').attr('src', url).show();
        }
    });

    $('#dropzone').on('dragover', function(e){
        e.preventDefault();
        $(this).css('border-color','blue');
    });

    $('#dropzone').on('dragleave', function(){
        $(this).css('border-color','grey');
    });

    $('#dropzone').on('drop', function(e){
        e.preventDefault();
        let files = e.originalEvent.dataTransfer.files;
        $('#imageinput')[0].files = files;

        let url = URL.createObjectURL(files[0]);
        $('#preview').attr('src', url).show();
        $(this).css('border-color','grey');
    });

    //submit

    $('#brandform').on('submit', function(e){
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('brand.store') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },
            success: function(res){
                let alert = $('#alertarea');

                if(res.status === 'success'){
                    alert.html('<div class="alert alert-success">'+res.message+'</div>');
                    localStorage.removeItem('brand_name');
                    localStorage.removeItem('brand_slug');
                    $('#brandform')[0].reset();
                    $('#preview').hide();
                } else if(res.errors){
                    let errorHtml = '<div class="alert alert-danger"><ul>';
                    $.each(res.errors, function(field, messages){
                        $.each(messages, function(i, msg){
                            errorHtml += '<li>'+msg+'</li>';
                        });
                    });
                    errorHtml += '</ul></div>';
                    alert.html(errorHtml);
                } else {
                    alert.html('<div class="alert alert-danger">'+res.message+'</div>');
                }
            },
            error: function(err){
                $('#alertarea').html('<div class="alert alert-danger">Lỗi</div>');
                console.log(err.responseJSON);
            }
        });

    });
});
</script>

@endsection
