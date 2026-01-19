@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Xác nhật địa chỉ email') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Đã gửi link đặt lại mật khẩu tới địa chỉ email của bạn') }}
                        </div>
                    @endif

                    {{ __('Trước khi tiếp tục, hãy kiếm tra link đặt lại mật khẩu') }}
                    {{ __('Không nhận được email?') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
