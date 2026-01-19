<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderByDesc('id')->paginate(10);
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name'  => 'required|unique:brands,name',
                'image' => 'nullable|image|max:2048',
            ],
            [
                'name.required' => 'Vui lòng nhập tên thương hiệu',
                'name.unique'   => 'Thương hiệu đã tồn tại',
            ]
        );

        $brand = Brand::create([
            'name'  => $request->name,
            'image' => $request->hasFile('image')
                ? $request->file('image')->store('brands', 'public')
                : null,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Thêm thương hiệu thành công',
            'data'    => $brand
        ]);
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate(
            [
                'name'  => 'required|unique:brands,name,' . $brand->id,
                'image' => 'nullable|image|max:2048',
            ],
            [
                'name.unique' => 'Thương hiệu đã tồn tại',
            ]
        );

        $brand->name = $request->name;

        if ($request->hasFile('image')) {
            if ($brand->image) {
                Storage::disk('public')->delete($brand->image);
            }
            $brand->image = $request->file('image')->store('brands', 'public');
        }

        $brand->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Cập nhật thương hiệu thành công'
        ]);
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        $brand->delete();

        if ($brand->image && Storage::disk('public')->exists($brand->image)) {
            Storage::disk('public')->delete($brand->image);
        }

        return redirect()->route('admin.brands.index')->with('success', 'Xóa thương hiệu thành công');
    }

}
