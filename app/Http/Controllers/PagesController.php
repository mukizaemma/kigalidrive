<?php

namespace App\Http\Controllers;

use App\Models\Eventpage;
use App\Models\Eventimage;
use App\Models\Restaurant;
use App\Models\Restoimage;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PagesController extends Controller
{

    public function eventsPage(){
        $data = Eventpage::first();
        $images = collect(); // Default to empty collection
        $totalImages = 0;

        if ($data) {
            $data->load('images');
            $images = $data->images ?? collect(); // In case images is null
            $totalImages = $images->count();
        } else {
            $data = new Eventpage();
            $data->title = 'Our Restaurant';
            $data->description = 'Restaurant details';
            $data->details = 'Restaurant details';
            $data->save();
            $data = Eventpage::first();
        }

        return view('admin.pages.events', [
            'data'=>$data,
            'images'=>$images,
            'totalImages'=>$totalImages,
            ]);
    }



    public function saveEvent(Request $request){
        $data = Eventpage::first();
        $data->title = $request->input('title');
        $data->description = $request->input('description');
        $data->details = $request->input('details');


        if ($request->hasFile('image') && request('image') != '') {
            $dir = 'public/images';

            if (File::exists($dir)) {
                unlink($dir);
            }
            $path = $request->file('image')->store($dir);
            $fileName = str_replace($dir, '', $path);

            $data->image = $fileName;
        }

        $saved = $data->update();

        if($saved){
            return redirect()->back()->with('success', 'Page has been updated successfully');
        }
        else{
            abort(404);
        }
    }

        
    public function addEventImage(Request $request)
        {
            $request->validate([
                'image.*'           => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'eventpage_id' => 'required|exists:eventpages,id', 
            ]);

            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $image) {
                    $dir = 'public/images/events';
                    $path = $image->store($dir);
                    $fileName = str_replace($dir . '/', '', $path);

                    Eventimage::create([
                        'image'             => $fileName,
                        'eventpage_id' => $request->eventpage_id, 
                        'user_id' => $request->user()->id
                    ]);
                }

                return redirect()->back()->with('success', 'Images uploaded successfully!');
            }

            return redirect()->back()->with('error', 'No images were uploaded.');
        }

    public function deleteEventImage($id){
        $image = Eventimage::findOrFail($id);

        $imagePath = 'public/images/events/' . $image->filename;

        if (Storage::exists($imagePath)) {
            Storage::delete($imagePath);
        }

        $image->delete();

        return redirect()->back()->with('warning', 'Image has been deleted');
    }

    public function resto(){
        $data = Restaurant::with('images')->first();
        $images = collect(); // Default to empty collection
        $totalImages = 0;

        if ($data) {
            $data->load('images');
            $images = $data->images ?? collect(); // In case images is null
            $totalImages = $images->count();
        } else {
            $data = new Restaurant();
            $data->title = 'Our Restaurant';
            $data->description = 'Restaurant details';
            $data->save();
            $data = Restaurant::first();
        }

        return view('admin.pages.restaurant', [
            'data'=>$data,
            'images'=>$images,
            'totalImages'=>$totalImages,
            ]);
    }



    public function saveResto(Request $request){
        $data = Restaurant::first();
        $data->title = $request->input('title');
        $data->description = $request->input('description');


        if ($request->hasFile('image') && request('image') != '') {
            $dir = 'public/images';

            if (File::exists($dir)) {
                unlink($dir);
            }
            $path = $request->file('image')->store($dir);
            $fileName = str_replace($dir, '', $path);

            $data->image = $fileName;
        }

        $saved = $data->update();

        if($saved){
            return redirect()->back()->with('success', 'Page has been updated successfully');
        }
        else{
            abort(404);
        }
    }

        
    public function addRestoImage(Request $request)
        {
            $request->validate([
                'image.*'           => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'restaurant_id' => 'required|exists:restaurants,id', 
            ]);

            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $image) {
                    $dir = 'public/images/restaurant';
                    $path = $image->store($dir);
                    $fileName = str_replace($dir . '/', '', $path);

                    Restoimage::create([
                        'image'             => $fileName,
                        'restaurant_id' => $request->restaurant_id, 
                        'user_id' => $request->user()->id
                    ]);
                }

                return redirect()->back()->with('success', 'Resto Image uploaded successfully!');
            }

            return redirect()->back()->with('error', 'No images were uploaded.');
        }

    public function deleteRestoImage($id){
        $image = Restoimage::findOrFail($id);

        $imagePath = 'public/images/restaurant/' . $image->filename;

        if (Storage::exists($imagePath)) {
            Storage::delete($imagePath);
        }

        $image->delete();

        return redirect()->back()->with('warning', 'Image has been deleted');
    }

}
