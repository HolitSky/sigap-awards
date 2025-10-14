<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function index()
    {
        $title = 'Profile';
        $user = Auth::user();
        $user->role_display = $this->getRoleDisplay($user->role);
        return view('dashboard.pages.profile.index', compact('user', 'title'));
    }

    /**
     * Get display name for role
     */
    protected function getRoleDisplay($role)
    {
        return match ($role) {
            'panitia' => 'Juri',
            'peserta' => 'Peserta',
            'admin' => 'Admin',
            'superadmin' => 'Superadmin',
            default => ucfirst($role),
        };
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Store new image
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $imagePath;
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully!');
    }

    /**
     * Delete the user's profile image.
     */
    public function deleteImage()
    {
        $user = Auth::user();

        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
            $user->profile_image = null;
            $user->save();
        }

        return redirect()->route('profile.index')->with('success', 'Profile image deleted successfully!');
    }
}
