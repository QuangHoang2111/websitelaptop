<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::orderByDesc('id')->paginate(10);
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'code'      => 'required|unique:vouchers,code',
                'type'      => 'required|in:percent,fixed',
                'cartvalue' => 'required|numeric|min:0',
                'expdate'   => 'required|date|after:today',
            ],
            [
                'code.required' => 'Vui lòng nhập mã voucher',
                'code.unique'   => 'Trùng mã voucher',
                'type.required' => 'Vui lòng chọn loại voucher',
                'cartvalue.required' => 'Vui lòng nhập giá trị',
                'expdate.after' => 'Hạn sử dụng phải lớn hơn ngày hiện tại',
            ]
        );

        Voucher::create($validated);

        return response()->json([
            'message' => 'Thêm voucher thành công'
        ]);
    }

    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $validated = $request->validate(
            [
                'code'      => 'required|unique:vouchers,code,' . $voucher->id,
                'type'      => 'required|in:percent,fixed',
                'cartvalue' => 'required|numeric|min:0',
                'expdate'   => 'required|date|after:today',
            ],
            [
                'code.required' => 'Vui lòng nhập mã voucher',
                'code.unique'   => 'Trùng mã voucher',
                'type.required' => 'Vui lòng chọn loại voucher',
                'cartvalue.required' => 'Vui lòng nhập giá trị',
                'expdate.after' => 'Hạn sử dụng phải lớn hơn ngày hiện tại',
            ]
        );

        $voucher->update($validated);

        return response()->json([
            'message' => 'Cập nhật voucher thành công'
        ]);
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();

        return redirect()
            ->route('admin.vouchers.index')
            ->with('success', 'Xóa voucher thành công');
    }
}
