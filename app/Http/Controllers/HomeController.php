<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Models\Product;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Attribute;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::where('isactive', 1)->orderBy('position')->get();

        $featuredProducts = Product::where('isfeatured', 1)
            ->where('stocks', '>', 0) -> latest()->get();

        $attrValues = AttributeValue::with('attribute')
            ->whereIn('productid', $featuredProducts->pluck('id'))->get()->groupBy('productid');

        return view('home', compact(
            'sliders',
            'featuredProducts',
            'attrValues'
        ));
    }

    public function productDetail($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $gallery = json_decode($product->images ?? '[]', true);

        $attrValues = AttributeValue::with('attribute')->where('productid', $product->id)->get();

        $relatedProducts = Product::where('categoryid', $product->categoryid)->where('id', '!=', $product->id)->take(4)->get();

       return view('products.detail', compact(
        'product',
        'gallery',
        'attrValues',
        'relatedProducts'
        ));

    }

    public function products(Request $request)
    {
        $query = Product::query()->with('brand', 'category');

        if ($request->filled('categories')) {
            $query->whereIn('categoryid', $request->categories);
        }

        if ($request->filled('brands')) {
            $query->whereIn('brandid', $request->brands);
        }

        if ($request->filled('price_min')) {
            $query->whereRaw(
                'COALESCE(NULLIF(saleprice, 0), regularprice) >= ?',
                [$request->price_min]
            );
        }

        if ($request->filled('price_max')) {
            $query->whereRaw(
                'COALESCE(NULLIF(saleprice, 0), regularprice) <= ?',
                [$request->price_max]
            );
        }


        if ($request->filled('attrs')) {
            foreach ($request->attrs as $attrId => $values) {
                $query->whereHas('attributeValues', function ($q) use ($attrId, $values) {
                    $q->where('attrid', $attrId)->whereIn('value', $values);
                });
            }
        }

        $categories = Category::all();

        $products = $query->paginate(12)->withQueryString();

        $brands = Brand::all();

        $attributes = Attribute::whereHas('values', function ($q) {
            $q->whereNotNull('value')->where('value', '!=', '');
        })
        ->with(['values' => function ($q) {
            $q->whereNotNull('value')->where('value', '!=', '')->distinct();
        }])
        ->get();

        if ($request->ajax()) {
            return view('products._list', compact('products'))->render();
        }

        return view('products.index', compact(
            'products',
            'categories',
            'brands',
            'attributes'
        ));
    }

    public function searchSuggest(Request $request)
    {
        $query = $request->input('query');
        $results = Product::where('name','LIKE',"%{$query}%")->get();
        return response()->json($results);
    }
}
