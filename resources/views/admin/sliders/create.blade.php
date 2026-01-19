@extends('layouts.admin')
@section('content')

<div class="card p-4">
    <h4 class="mb-3">Thêm slider</h4>

    <form id="sliderForm" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Tiêu đề</label>
            <input type="text" name="title" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Link (tùy chọn)</label>
            <input type="text" name="link" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Vị trí</label>
            <input type="number" name="position" class="form-control" value="0">
        </div>

        <div class="mb-3">
            <label class="form-label">
                Ảnh slider <span class="text-danger">*</span>
            </label>

            <div id="sliderDropzone"
                 style="border:2px dashed #777; padding:20px; text-align:center; cursor:pointer">
                Kéo thả / click để thêm ảnh slider
            </div>

            <input type="file"name="image"id="sliderImageInput" class="d-none" accept="image/*"required>

            <img id="sliderPreview"style="max-width:300px; margin-top:10px; display:none"class="rounded">
        </div>

        <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select name="isactive" class="form-select">
                <option value="1">Hiện</option>
                <option value="0">Ẩn</option>
            </select>
        </div>

        <button class="btn btn-primary w-100">
            Lưu slider
        </button>

        <div id="alertarea" class="mt-3"></div>
    </form>
</div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const dropzone = document.getElementById('sliderDropzone');
    const input    = document.getElementById('sliderImageInput');
    const preview  = document.getElementById('sliderPreview');

    dropzone.addEventListener('click', () => input.click());

    input.addEventListener('change', () => {
        const file = input.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    });

    dropzone.addEventListener('dragover', e => {
        e.preventDefault();
        dropzone.style.borderColor = 'blue';
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.style.borderColor = '#777';
    });

    dropzone.addEventListener('drop', e => {
        e.preventDefault();
        input.files = e.dataTransfer.files;

        const file = input.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }

        dropzone.style.borderColor = '#777';
    });

    $('#sliderForm').on('submit', function(e){
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('admin.sliders.store') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(res){
                if(res.status === 'success'){
                    $('#alertarea').html(
                        '<div class="alert alert-success">'+res.message+'</div>'
                    );
                    $('#sliderForm')[0].reset();
                    $('#sliderPreview').hide();
                }
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
