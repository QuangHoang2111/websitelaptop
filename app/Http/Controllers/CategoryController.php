<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderByDesc('id')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|unique:categories,name',
            ],
            [
                'name.required' => 'Vui lòng nhập tên danh mục',
                'name.unique'   => 'Danh mục đã tồn tại',
            ]
        );

        Category::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Thêm danh mục thành công',
        ]);
    }

    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->get();

        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(
            [
                'name' => 'required|unique:categories,name,' . $category->id,
            ],
            [
                'name.unique' => 'Danh mục đã tồn tại',
            ]
        );

        $category->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Cập nhật danh mục thành công',
        ]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Xóa danh mục thành công');
    }
}
