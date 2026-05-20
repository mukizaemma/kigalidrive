<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;

class PartnersController extends Controller
{
    public function index()
    {

        $partners = Partner::all();
        $setting = Setting::first();
        return view('admin.partners', [
            'partners' => $partners,
            'setting' => $setting
        ]);
    }


    public function store(Request $request): RedirectResponse
    {
        $fileName = '';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('public/images/partners');
            $fileName = basename($path);
        }

        $slug = Str::slug($request->name);
        $partnerUid = Str::uuid();

        Partner::create([
            'partner_uid' => $partnerUid,
            'name' => $request->name,
            'type' => $request->type,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'description' => $request->description,
            'status' => $request->status ?? 'Active',
            'image' => $fileName,
            'website' => $request->website,
            'slug' => $slug,
        ]);

        return redirect('getPartners')->with('success', 'New Partner has been saved successfully');
    }


    
    public function edit($id)
    {
        $partner = Partner::find($id);
        return view('admin.partnerUpdate', [
            'partner'=>$partner
        ]);
    }


    public function update(Request $request, $id)
    {
        try {
            $partner = Partner::findOrFail($id);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->store('public/images/partners');
                Storage::delete('public/images/partners/' . $partner->image);
                $partner->image = basename($path);
            }

            $partner->name = $request->name;
            $partner->website = $request->website;
            $partner->type = $request->type;
            $partner->email = $request->email;
            $partner->phone = $request->phone;
            $partner->address = $request->address;
            $partner->city = $request->city;
            $partner->description = $request->description;
            $partner->status = $request->status;

            if ($partner->isDirty('name')) {
                $slug = Str::slug($partner->name);
                if (Partner::where('slug', $slug)->where('id', '!=', $partner->id)->exists()) {
                    $slug .= '-' . uniqid();
                }
                $partner->slug = $slug;
            }

            $partner->save();

            return redirect()->route('getPartners')->with('success', 'Partner has been updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }





    public function destroy($id)
    {
        $partner = Partner::find($id); 
        $partner->delete($id);
        return back()
            ->with('success', 'Partner deleted successfully');
    }
}
