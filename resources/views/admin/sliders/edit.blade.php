@extends('layouts.admin')
@section('content')

<div class="card p-4">
    <h4 class="mb-3">Sửa slider</h4>

    <form id="sliderEditForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Tiêu đề</label>
            <input type="text"name="title"value="{{ $slider->title }}"class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Link</label>
            <input type="text"name="link"value="{{ $slider->link }}"class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Vị trí</label>
            <input type="number"
                   name="position"
                   value="{{ $slider->position }}"
                   class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh slider</label><br>

            <div id="sliderDropzone"
                 style="border:2px dashed #777; padding:20px; text-align:center; cursor:pointer">
                Kéo thả / click để đổi ảnh
            </div>

            <input type="file"name="image"id="sliderImageInput" class="d-none"accept="image/*">

            <img id="sliderPreview" src="{{ asset('storage/'.$slider->image) }}"style="max-width:300px; margin-top:10px"
             class="rounded">
        </div>

        <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select name="isactive" class="form-select">
                <option value="1" {{ $slider->isactive ? 'selected' : '' }}>Hiện</option>
                <option value="0" {{ !$slider->isactive ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>

        <button class="btn btn-primary w-100">Cập nhật</button>

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
        }

        dropzone.style.borderColor = '#777';
    });

    $('#sliderEditForm').on('submit', function(e){
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('admin.sliders.update', $slider) }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(res){
                if(res.status === 'success'){
                    $('#alertarea').html(
                        '<div class="alert alert-success">'+res.message+'</div>'
                    );

                    if(res.image){
                        $('#sliderPreview').attr(
                            'src',
                            '/storage/' + res.image + '?' + new Date().getTime()
                        );
                    }
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
