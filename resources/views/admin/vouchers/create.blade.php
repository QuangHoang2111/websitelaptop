@extends('layouts.admin')
@section('content')

<div class="card p-4">
    <h4 class="mb-3">Thêm voucher</h4>

    <form id="voucherForm">
        @csrf

        <div class="mb-3">
            <label class="form-label">Mã voucher <span class="text-danger">*</span> </label>
            <input type="text" name="code" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Loại giảm giá <span class="text-danger">*</span> </label>
            <select name="type" class="form-select" required>
                <option value="">-- Chọn --</option>
                <option value="percent">Giảm theo %</option>
                <option value="fixed">Giảm tiền cố định</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Giá trị <span class="text-danger">*</span> </label>
            <input type="number" name="cartvalue" class="form-control" required>
            <small class="text-muted">
                % nếu là giảm %, VNĐ nếu là giảm tiền
            </small>
        </div>

        <div class="mb-3">
            <label class="form-label">Hạn sử dụng <span class="text-danger">*</span> </label>
            <input type="date"
                   name="expdate"
                   class="form-control"
                   min="{{ now()->addDay()->toDateString() }}"
                   required>
        </div>

        <button class="btn btn-primary w-100">Thêm voucher</button>
        <div id="alertArea" class="mt-3"></div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#voucherForm').submit(function(e){
    e.preventDefault();

    $.ajax({
        url: "{{ route('admin.vouchers.store') }}",
        method: "POST",
        data: $(this).serialize(),
        success: res => {
            $('#alertArea').html(
                `<div class="alert alert-success">${res.message}</div>`
            );
            this.reset();
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
</script>

@endsection
