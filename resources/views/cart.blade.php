@extends('layouts.app')
@section('title','Giỏ hàng')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Giỏ hàng</h4>

            @if(count($cart))
            <button class="btn btn-sm btn-outline-danger" id="clearCart">
                Xóa toàn bộ
            </button>
            @endif
        </div>


        @forelse($cart as $id => $item)
        <div class="d-flex align-items-center border rounded p-3 mb-3 cart-item" data-id="{{ $id }}">
            <img src="{{ asset('storage/'.$item['image']) }}" width="80" class="me-3 rounded">

            <div class="flex-grow-1">
                <div class="fw-semibold">{{ $item['name'] }}</div>
                <div class="text-danger">{{ number_format($item['price']) }} ₫</div>
            </div>

            <input type="number" class="form-control w-25 me-2 qty" value="{{ $item['qty'] }}" min="1" max="{{ $item['stock'] }}">

            <button class="btn btn-outline-danger remove">✕</button>
        </div>
        @empty
            <p>Giỏ hàng trống</p>
        @endforelse
    </div>

    <div class="col-md-4">
        <h4 class="fw-bold mb-3">Thanh toán</h4>

        <input id="voucherCode" class="form-control mb-2" placeholder="Nhập voucher">
        <button id="applyVoucher" class="btn btn-outline-primary w-100 mb-3"> Áp dụng </button>

        <div class="border rounded p-3">
            <div class="d-flex justify-content-between">
                <span>Tạm tính</span>
                <strong id="subtotal">{{ number_format($subtotal) }} ₫</strong>
            </div>

            <div class="d-flex justify-content-between">
                <span>Giảm giá</span>
                <strong id="discount">
                    {{ isset($voucher) ? number_format($voucher['discount']) : 0 }} ₫
                </strong>
            </div>

            <hr>

            <div class="d-flex justify-content-between fw-bold text-danger">
                <span>Tổng</span>
                <span id="total">
                    {{ number_format($subtotal - ($voucher['discount'] ?? 0)) }} ₫
                </span>
            </div>

            <a href="{{ route('checkout') }}" class="btn btn-danger w-100 mt-3" id="checkoutBtn">
                Tiếp theo
            </a>
        </div>
    </div>
</div>

<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:99999">
    <div id="stockToastError" class="toast text-bg-danger border-0">
        <div class="toast-body">
            Số lượng vượt quá tồn kho
        </div>
    </div>
</div>

<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:99999">
    <div id="voucherToastError" class="toast text-bg-danger border-0">
        <div class="toast-body" id="voucherErrorText">
            Voucher không hợp lệ
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const csrf = '{{ csrf_token() }}';

function format(v){
    return new Intl.NumberFormat('vi-VN').format(v) + ' ₫';
}

document.querySelectorAll('.qty').forEach(el => {
    el.onchange = () => {
        const box = el.closest('.cart-item');
        const btn = document.getElementById('checkoutBtn');

        fetch('{{ route("cart.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({
                id: box.dataset.id,
                qty: el.value
            })
        })
        .then(res => {
            if (!res.ok) {
                new bootstrap.Toast(
                    document.getElementById('stockToastError'),
                    { delay: 500 }
                ).show();

                setTimeout(() => {
                    location.reload();
                }, 500);
                return;
            }

            return res.json();
        })
        .then(d => {
            if (!d) return;

            document.getElementById('subtotal').innerText = format(d.subtotal);
            document.getElementById('discount').innerText = '0 ₫';
            document.getElementById('total').innerText = format(d.subtotal);

            btn.classList.remove('disabled');
            btn.setAttribute('href', '{{ route("checkout") }}');
        });
    };
});


document.getElementById('applyVoucher').onclick = () => {
    fetch('{{ route("cart.voucher") }}', {
        method: 'POST',
        headers: {
            'Content-Type':'application/json',
            'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify({
            code: document.getElementById('voucherCode').value.trim()
        })
    })
    .then(async res => {
        if (!res.ok) {
            const data = await res.json();

            document.getElementById('voucherErrorText').innerText =
                data.error || 'Voucher không hợp lệ';

            new bootstrap.Toast(
                document.getElementById('voucherToastError'),
                { delay: 3500 }
            ).show();

            throw new Error('voucher error');
        }
        return res.json();
    })
    .then(d => {
        document.getElementById('subtotal').innerText = format(d.subtotal);
        document.getElementById('discount').innerText = format(d.discount);
        document.getElementById('total').innerText = format(d.total);
    })
    .catch(() => {});
};


const clearBtn = document.getElementById('clearCart');

if (clearBtn) {
    clearBtn.onclick = () => {
        
        fetch('{{ route("cart.clear") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            }
        })
        .then(r => r.json())
        .then(() => {
            location.reload();
        });
    };
}

document.querySelectorAll('.remove').forEach(btn => {
    btn.onclick = () => {
        const box = btn.closest('.cart-item');

        fetch('{{ route("cart.remove") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({
                id: box.dataset.id
            })
        })
        .then(res => res.json())
        .then(() => {
            box.remove();

        if (document.querySelectorAll('.cart-item').length === 0) {
                location.reload();
            }
        });
    };
});
</script>
@endpush
