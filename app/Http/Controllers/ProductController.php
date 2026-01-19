<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderByDesc('id')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create', [
            'attributes' => Attribute::all(),
            'categories' => Category::all(),
            'brands'     => Brand::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|max:255',
            'slug'         => 'required|unique:products,slug',
            'sku'          => 'required|unique:products,sku',
            'costprice'    => 'required|numeric|min:0',
            'regularprice' => 'required|numeric|min:0',
            'saleprice'    => 'nullable|numeric|min:0',
            'stocks'       => 'required|integer|min:0',
            'categoryid'   => 'nullable|exists:categories,id',
            'brandid'      => 'nullable|exists:brands,id',
            'image'        => 'nullable|image|max:2048',
            'gallery.*'    => 'nullable|image|max:2048',
        ],[
            'slug.unique' => "Slug đã tồn tại",
            'sku.unique' => "SKU đã tồn tại"
        ]);

        $product = Product::create([
            'name'         => $request->name,
            'slug'         => $request->slug ?: Str::slug($request->name),
            'sku'          => $request->sku,
            'costprice'    => $request->costprice,
            'regularprice' => $request->regularprice,
            'saleprice'    => $request->saleprice ?? 0,
            'stocks'       => $request->stocks,
            'categoryid'   => $request->categoryid,
            'brandid'      => $request->brandid,
            'isfeatured'   => $request->isfeatured ?? 0,
            'shortdescription' => $request->shortdescription,
            'description'      => $request->description,
        ]);

        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('products', 'public');
            $product->save();
        }

        if ($request->hasFile('gallery')) {
            $gallery = [];
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('products/gallery', 'public');
            }
            $product->images = json_encode($gallery);
            $product->save();
        }

        foreach ($request->input('attributes', []) as $attrId => $value) {
            if ($value !== null && $value !== '') {
                AttributeValue::create([
                    'productid' => $product->id,
                    'attrid'    => $attrId,
                    'value'     => $value,
                ]);
            }
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Thêm sản phẩm thành công'
        ]);
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', [
            'product'    => $product,
            'attributes' => Attribute::all(),
            'categories' => Category::all(),
            'brands'     => Brand::all(),
            'attrValues' => AttributeValue::where('productid', $product->id)->pluck('value', 'attrid')->toArray(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'         => 'required|max:255',
            'slug'         => 'required|unique:products,slug,' . $product->id,
            'sku'          => 'required|unique:products,sku,' . $product->id,
            'costprice'    => 'required|numeric|min:0',
            'regularprice' => 'required|numeric|min:0',
            'saleprice'    => 'nullable|numeric|min:0',
            'stocks'       => 'required|integer|min:0',
            'categoryid'   => 'nullable|exists:categories,id',
            'brandid'      => 'nullable|exists:brands,id',
            'image'        => 'nullable|image|max:2048',
        ],[
            'slug.unique' => "Slug đã tồn tại",
            'sku.unique' => "SKU đã tồn tại"
        ]);

        $product->update([
            'name'         => $request->name,
            'slug'         => $request->slug ?: Str::slug($request->name),
            'sku'          => $request->sku,
            'costprice'    => $request->costprice,
            'regularprice' => $request->regularprice,
            'saleprice'    => $request->saleprice ?? 0,
            'stocks'       => $request->stocks,
            'categoryid'   => $request->categoryid,
            'brandid'      => $request->brandid,
            'isfeatured'   => $request->isfeatured ?? 0,
            'shortdescription' => $request->shortdescription,
            'description'      => $request->description,
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
            $product->save();
        }


            if ($request->hasFile('gallery')) {

                if ($product->images) {
                    $oldImages = json_decode($product->images, true);
                    if (is_array($oldImages)) {
                        foreach ($oldImages as $img) {
                            Storage::disk('public')->delete($img);
                        }
                    }
                }

                $gallery = [];
                foreach ($request->file('gallery') as $file) {
                    $gallery[] = $file->store('products/gallery', 'public');
                }

                $product->images = json_encode($gallery);
                $product->save();
            }   


        foreach ($request->input('attributes', []) as $attrId => $value) {
            AttributeValue::updateOrCreate(
                ['productid' => $product->id, 'attrid' => $attrId],
                ['value' => $value]
            );
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Cập nhật sản phẩm thành công'
        ]);
    }
    
    public function destroy(Product $product)
    {
    if ($product->image) {
        Storage::disk('public')->delete($product->image);
    }

    if ($product->images) {
        $images = json_decode($product->images, true);
        if (is_array($images)) {
            foreach ($images as $img) {
                Storage::disk('public')->delete($img);
            }
        }
    }

    AttributeValue::where('productid', $product->id)->delete();
    $product->delete();

    return redirect()
        ->route('admin.products.index')
        ->with('success', 'Xóa sản phẩm thành công');
    }


}
