@extends('layouts.admin')
@section('content')

<div class="card p-4">
    <h4 class="mb-3">Cập nhật thương hiệu</h4>

    <form id="brandform" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">
                Tên thương hiệu <span class="text-danger">*</span>
            </label>
            <input type="text"name="name"class="form-control"value="{{ old('name', $brand->name) }}"required>
        </div>

        <div class="mb-3">
            <label class="form-label">Logo thương hiệu</label>

            <div id="dropzone"
                 style="border:2px dashed #777; padding:20px; text-align:center; cursor:pointer">
                Thêm ảnh thương hiệu
            </div>

            <input type="file"name="image"id="imageinput"class="d-none"accept="image/*">

            <img id="preview"
                 src="{{ $brand->image ? asset('storage/'.$brand->image) : '' }}"
                 style="max-width:200px; margin-top:10px; {{ $brand->image ? '' : 'display:none' }}">
        </div>

        <button class="btn btn-primary w-100">
            Cập nhật thương hiệu
        </button>

        <div id="alertarea" class="mt-3"></div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(function(){

    $('#dropzone').click(() => $('#imageinput').click());

    $('#imageinput').on('change', function(){
        let file = this.files[0];
        if(file){
            $('#preview').attr('src', URL.createObjectURL(file)).show();
        }
    });

    $('#brandform').on('submit', function(e){
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('admin.brands.update', $brand->id) }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-HTTP-Method-Override': 'PUT'
            },
            success(res){
                $('#alertarea').html(
                    `<div class="alert alert-success">${res.message}</div>`
                );
            },
            error: function (xhr) {
                let msg = 'Có lỗi xảy ra';

                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors)
                        .map(err => err[0])
                        .join('<br>');
                }

                $('#alertarea').html(
                    '<div class="alert alert-danger">' + msg + '</div>'
                );

                setTimeout(() => {
                    $('#alertarea').fadeOut(300, function () {
                        $(this).html('').show();
                    });
                }, 4000);
            }
        });
    });
});
</script>


@endsection
