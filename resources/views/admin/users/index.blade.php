@extends('layouts.admin')
@section('content')

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0">Tài khoản</h3>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-bold" style="width:80px">STT</th>
                            <th class="fw-bold">Tên</th>
                            <th class="fw-bold">Email</th>
                            <th class="fw-bold">Quyền</th>
                            <th class="fw-bold">Ngày tạo</th>
                            <th class="fw-bold" style="width:120px">Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($users as $key => $user)
                            <tr>
                                <td>{{ $users->firstItem() + $key }}</td>

                                <td class="fw-semibold">
                                    {{ $user->name }}
                                </td>

                                <td>{{ $user->email }}</td>

                                <td>
                                    @if($user->utype === 'ADM')
                                        <span class="badge bg-danger">Admin</span>
                                    @else
                                        <span class="badge bg-secondary">User</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>

                                <td>
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                    class="btn btn-sm btn-outline-primary">Sửa</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"class="text-center text-muted py-4">Không có tài khoản</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

@endsection
