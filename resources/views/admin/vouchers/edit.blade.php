@extends('layouts.admin')
@section('content')

<div class="card p-4">
    <h4 class="mb-3">Chỉnh sửa voucher</h4>

    <form id="voucherForm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Mã voucher <span class="text-danger">*</span> </label>
            <input type="text"
                   name="code"
                   class="form-control"
                   value="{{ $voucher->code }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Loại giảm giá <span class="text-danger">*</span> </label>
            <select name="type" class="form-select" required>
                <option value="percent" {{ $voucher->type === 'percent' ? 'selected' : '' }}>
                    Giảm theo %
                </option>
                <option value="fixed" {{ $voucher->type === 'fixed' ? 'selected' : '' }}>
                    Giảm tiền cố định
                </option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Giá trị <span class="text-danger">*</span> </label>
            <input type="number"
                   name="cartvalue"
                   class="form-control"
                   value="{{ $voucher->cartvalue }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Hạn sử dụng <span class="text-danger">*</span> </label>
            <input type="date"
                   name="expdate"
                   class="form-control"
                   value="{{ \Carbon\Carbon::parse($voucher->expdate)->toDateString() }}"
                   required>
        </div>

        <button class="btn btn-primary w-100">Cập nhật voucher</button>
        <div id="alertArea" class="mt-3"></div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#voucherForm').submit(function(e){
    e.preventDefault();

    $.ajax({
        url: "{{ route('admin.vouchers.update', $voucher->id) }}",
        method: "POST",
        data: $(this).serialize(),
        success: res => {
            $('#alertArea').html(
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
</script>

@endsection
