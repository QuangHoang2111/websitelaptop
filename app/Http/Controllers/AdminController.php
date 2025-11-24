<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function brands()
    {
        $brands = brand::orderBy('id','DESC') ->paginate(10);
        return view('admin.brands',compact('brands'));
    }

    public function createbrand(){
        return view('admin.createbrand');
    }

    public function storebrand(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:brands,name',
            'slug' => 'nullable|string',
            'image' => 'nullable|image'
        ]);

        $brand = new Brand();
        $brand-> name = $request->name;
        $brand-> slug = $request->slug ?: Str::slug($request->name);

        if ($request->hasFile('image')){
            $brand->image = $request->file('image')->store('brands','public');
        }

        $brand -> save();

        return response()->json([
        'status' => 'success',
        'message' => 'Thêm thương hiệu thành công'
        ]);
    }

    public function editbrand($id){
        $brand = Brand::findOrFail($id);
        return view('admin.editbrand',compact('brand'));
    }

    public function updatebrand(Request $request, $id)
    {   
        $brand = Brand::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:brands,name,' . $brand -> id,
            'slug' => 'nullable|string|unique:brands,slug,'. $brand -> id, 
            'image' => 'nullable|image'
        ]);

        $brand-> name = $request->name;
        $brand-> slug = $request->slug ?: Str::slug($request->name);

        if ($request->hasFile('image')){

            if ($brand->image && Storage::disk ('public') -> exists ($brand ->image )){
                Storage::disk ('public')->delete($brand->image);
            }
            $brand->image = $request->file('image')->store('brands','public');
        }

        $brand -> save();

          return response()->json([
        'status' => 'success',
        'message' => 'Cập nhật thương hiệu thành công'
        ]);
    }

    public function deletebrand($id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->image && Storage::disk ('public') -> exists ($brand ->image )){
                Storage::disk ('public')->delete($brand->image);
        }

        $brand -> delete();

        return redirect() -> route('admin.brands')->with('success','Xóa thương hiệu thành công');
    }

}
