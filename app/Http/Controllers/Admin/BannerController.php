<?php
namespace App\Http\Controllers\Admin;

use App\Banner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderByDesc('id')->get();
        return view('admin.banner.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banner.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $img = time().'.'.$request->image->extension();
        $request->image->move(public_path('banners'), $img);

        Banner::create([
            'name'  => $request->name,
            'image' => 'banners/'.$img,
            'type'  => $request->type ?? 1,
            'status'=> $request->status ?? 1,
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banner.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imgPath = $banner->image;
        if ($request->hasFile('image')) {
            $img = time().'.'.$request->image->extension();
            $request->image->move(public_path('banners'), $img);
            $imgPath = 'banners/'.$img;
        }

        $banner->update([
            'name'  => $request->name,
            'image' => $imgPath,
            'type'  => $request->type ?? 1,
            'status'=> $request->status ?? 1,
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        if (file_exists(public_path($banner->image))) {
            unlink(public_path($banner->image));
        }
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }
}

