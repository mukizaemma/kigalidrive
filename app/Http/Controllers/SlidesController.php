<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Setting;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class SlidesController extends Controller
{
    public function index()
    {
        $slides = Slide::query()
            ->when(Schema::hasColumn('slides', 'sort_order'), fn ($q) => $q->orderBy('sort_order')->orderByDesc('id'))
            ->when(! Schema::hasColumn('slides', 'sort_order'), fn ($q) => $q->latest())
            ->get();
        $setting = Setting::first();

        return view('admin.includes.slides', ['slides' => $slides, 'setting' => $setting]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:4096',
            'caption' => 'nullable|string|max:120',
            'heading' => 'required|string|max:255',
            'subheading' => 'nullable|string|max:500',
            'status' => 'nullable|in:Active,Inactive',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = new Slide();
        $data->caption = $request->caption;
        $data->heading = $request->heading;
        $data->subheading = $request->subheading;
        $data->status = $request->input('status', 'Active');
        $data->sort_order = (int) $request->input('sort_order', 0);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images/slides');
            $data->image = '/' . basename($path);
        }

        $data->save();

        return redirect()->route('slides')->with('success', 'Slide added successfully.');
    }

    public function edit($id)
    {
        $data = Slide::findOrFail($id);

        return view('admin.includes.slideUpdate', ['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $data = Slide::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|max:4096',
            'caption' => 'nullable|string|max:120',
            'heading' => 'required|string|max:255',
            'subheading' => 'nullable|string|max:500',
            'status' => 'nullable|in:Active,Inactive',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data->caption = $request->caption;
        $data->heading = $request->heading;
        $data->subheading = $request->subheading;
        $data->status = $request->input('status', 'Active');
        $data->sort_order = (int) $request->input('sort_order', 0);

        if ($request->hasFile('image')) {
            $old = ltrim((string) $data->image, '/');
            if ($old && Storage::exists('public/images/slides/' . $old)) {
                Storage::delete('public/images/slides/' . $old);
            }
            $path = $request->file('image')->store('public/images/slides');
            $data->image = '/' . basename($path);
        }

        $data->save();

        return redirect()->route('slides')->with('success', 'Slide updated successfully.');
    }

    public function destroy($id)
    {
        $slide = Slide::findOrFail($id);
        $file = ltrim((string) $slide->image, '/');
        if ($file && Storage::exists('public/images/slides/' . $file)) {
            Storage::delete('public/images/slides/' . $file);
        }
        $slide->delete();

        return redirect()->route('slides')->with('success', 'Slide deleted.');
    }

    public function getImages()
    {
        $images = Gallery::latest()->get();

        return view('admin.gallery', ['images' => $images]);
    }

    public function saveImage(Request $request)
    {
        $data = new Gallery();
        $data->caption = $request->caption;

        if ($request->hasFile('image')) {
            $dir = 'public/images/gallery';
            $path = $request->file('image')->store($dir);
            $fileName = str_replace($dir, '', $path);
            $data->image = $fileName;
        }

        $stored = $data->save();

        if ($stored) {
            return redirect('getImages')->with('success', 'New Image has been added successfuly');
        }

        return redirect()->back()->with('error', 'Failed to add new Image');
    }

    public function editGallery($id)
    {
        $data = Gallery::findOrFail($id);

        return view('admin.galleryUpdate', ['data' => $data]);
    }

    public function updateGallery(Request $request, $id)
    {
        $data = Gallery::find($id);
        $data->caption = $request->input('caption');

        if (! $data) {
            return back()->with('Error', 'Image Not Found');
        }

        if ($request->hasFile('image') && request('image') != '') {
            $dir = 'public/images/gallery';
            $path = $request->file('image')->store($dir);
            $fileName = str_replace($dir, '', $path);
            $data->image = $fileName;
        }

        $data->update();

        return redirect('getImages')->with('success', 'Image has been updated');
    }

    public function destroyImage($id)
    {
        $image = Gallery::findOrFail($id);
        Storage::delete('public/images/gallery/' . $image);
        $image->delete();

        return redirect()->back()->with('warning', 'Item has been deleted');
    }
}
