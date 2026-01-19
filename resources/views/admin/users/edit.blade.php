@extends('layouts.admin')
@section('content')

<div class="container-fluid py-4">
    <div class="card p-4">
        <h4 class="mb-3">Chi tiết tài khoản</h4>

        <form method="POST"
              action="{{ route('admin.users.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Tên</label>
                <input type="text"
                       class="form-control"
                       value="{{ $user->name }}"
                       disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="text"
                       class="form-control"
                       value="{{ $user->email }}"
                       disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Quyền</label>
                <select name="utype" class="form-select">
                    <option value="USR" {{ $user->utype === 'USR' ? 'selected' : '' }}>
                        User
                    </option>
                    <option value="ADM" {{ $user->utype === 'ADM' ? 'selected' : '' }}>
                        Admin
                    </option>
                </select>

                @error('utype')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button class="btn btn-primary">
                Lưu thay đổi
            </button>

            <a href="{{ route('admin.users') }}"
               class="btn btn-secondary ms-2">
                Quay lại
            </a>
        </form>
    </div>
</div>

@endsection
