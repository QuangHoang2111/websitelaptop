<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('position')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $path = $request->file('image')->store('sliders', 'public');

        Slider::create([
            'title'    => $request->title,
            'image'    => $path,
            'link'     => $request->link,
            'position' => $request->position ?? 0,
            'isactive' => $request->isactive ?? 1,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Thêm slider thành công',
        ]);
    }


    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['title', 'link', 'position', 'isactive']);

        if ($request->hasFile('image')) {
            if ($slider->image) {
                Storage::disk('public')->delete($slider->image);
            }
            $data['image'] = $request->file('image')->store('sliders', 'public');
        }

        $slider->update($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Cập nhật slider thành công',
            'image'   => $slider->image,
        ]);
    }

    
    public function destroy(Slider $slider)
    {
        Storage::disk('public')->delete($slider->image);
        $slider->delete();

        return redirect()->route('admin.sliders.index');
    }
}
