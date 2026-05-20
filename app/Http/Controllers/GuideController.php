<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class GuideController extends Controller
{
    /**
     * User guide (frontend) – how to add a property, process, required details.
     * Available to everyone; encourages sign-in for property owners.
     */
    public function index()
    {
        $setting = Setting::first();
        return view('frontend.guide', compact('setting'));
    }

    /**
     * Admin guide – where to start, what to do under each feature.
     * Admin panel layout.
     */
    public function admin()
    {
        $setting = Setting::first();
        return view('admin.guide', compact('setting'));
    }
}
