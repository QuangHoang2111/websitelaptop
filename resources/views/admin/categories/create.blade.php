@extends('layouts.admin')
@section('content')

<div class="card p-4">
    <h4 class="mb-3">Thêm danh mục</h4>

    <form id="categoryform">
        @csrf

        <div class="mb-3">
            <label class="form-label">
                Tên danh mục <span class="text-danger">*</span>
            </label>
            <input type="text"name="name"class="form-control" placeholder="Nhập tên danh mục">
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Thêm danh mục
        </button>

        <div id="alertarea" class="mt-3"></div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(function(){

    // restore name nếu reload
    if(localStorage.getItem('category_name')){
        $('input[name="name"]').val(localStorage.getItem('category_name'));
    }

    $('input[name="name"]').on('input', function(){
        localStorage.setItem('category_name', $(this).val());
    });

    $('#categoryform').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            url: "{{ route('admin.categories.store') }}",
            type: "POST",
            data: $(this).serialize(),
            success(res){
                $('#alertarea').html(
                    `<div class="alert alert-success">${res.message}</div>`
                );
                localStorage.removeItem('category_name');
                $('#categoryform')[0].reset();
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
