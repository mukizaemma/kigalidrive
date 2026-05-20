<?php

namespace App\Http\Controllers;

use Ramsey\Uuid\Uuid;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DestinationsController extends Controller
{

    public function index()
    {
        $destinations = Category::with('hotels')->get();
        return view('admin.destinations.destinations',[
            'destinations'=>$destinations,
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    
        $validate = Validator::make($request->all(), $rules);
        
        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        } 
    
        $fileName = '';
        if($request->hasFile('image')){
            $file = $request->file('image');
            $path = $file->store('public/images/destinations');
            $fileName = basename($path);
        }
    
        $slug = Str::of($request->input('name'))->slug();
    
        $destination = new Category();
        $destination->name = $request->input('name');
        $destination->description = $request->input('description');
        $destination->slug = $slug;
        $destination->image = $fileName;
        $saved = $destination->save();
        
        if($saved){
            return redirect()->route('getDestinations')->with('success', 'Destination created successfully.');
        }
        else{
            return redirect()->back()->with('error', 'Something Went wrong.');
        }
    }
    
    public function edit(string $id)
    {
        $destination = Category::find($id);
        return view('admin.destinations.destinationUpdate', [
            'destination'=>$destination,
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    
        $validate = Validator::make($request->all(), $rules);
    
        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }
    
        $destination = Category::findOrFail($id);
    
        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'slug' => Str::of($request->input('name'))->slug(),
        ];
    
        if ($request->hasFile('image')) {
            if ($destination->image) {
                Storage::delete('public/images/destinations/' . $destination->image);
            }
    
            $file = $request->file('image');
            $path = $file->store('public/images/destinations');
            $data['image'] = basename($path);
        }
    
        // Update the category and check if the save was successful
        $updated = $destination->update($data);
    
        if ($updated) {
            return redirect()->route('getDestinations')->with('success', 'Destination updated successfully.');
        }
    
        return redirect()->route('getDestinations')->with('error', 'Failed to update the category. Please try again.');
    }
    
    
    
    public function destroy($id)
    {
        $post = Category::findOrFail($id);
    
        if ($post->image) {
            $imagePath = public_path('storage/images/destinations/' . $post->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    
        $post->delete();
    
        return redirect('getDestinations')->with('success', 'Data has been deleted');
    }
    

}
