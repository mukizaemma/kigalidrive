<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $setting = Setting::first();

        if ($this->userIsAdmin($user)) {
            return view('admin.profile', compact('user', 'setting'));
        }

        return view('frontend.profile', compact('user', 'setting'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('my.profile')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password:web'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'The current password is incorrect.',
        ]);

        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        return redirect()->route('my.profile')->with('success', 'Password changed successfully.');
    }

    private function userIsAdmin($user): bool
    {
        return in_array((int) ($user->role ?? 0), [1, 2], true);
    }
}
