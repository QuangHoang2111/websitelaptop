@extends('layouts.admin')
@section('content')

<div class="card p-4">
    <h4 class="mb-3">Cập nhật danh mục</h4>

    <form id="categoryform">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">
                Tên danh mục <span class="text-danger">*</span>
            </label>
            <input type="text"name="name"class="form-control"value="{{ old('name', $category->name) }}"required>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Cập nhật danh mục
        </button>

        <div id="alertarea" class="mt-3"></div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(function(){

    $('#categoryform').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            url: "{{ route('admin.categories.update', $category->id) }}",
            type: "POST",
            data: $(this).serialize(),
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

<style>
#alertarea ul {
    list-style: none;
    padding-left: 0;
    margin-bottom: 0;
}
</style>
@endsection
