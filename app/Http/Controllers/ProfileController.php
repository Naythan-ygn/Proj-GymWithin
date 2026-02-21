<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function editSettings()
    {
        return view('profile.settings', [
            'user' => Auth::user(),
        ]);
    }

    public function updateSettings(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        // Using update directly on the model instance
        $user->update($validated);

        return back()->with('status', 'profile-updated')
            ->with('message', 'Profile details updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated')
            ->with('message', 'Password changed successfully.');
    }

    public function destroy(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        Auth::logout();

        $user->delete(); // Recognized by IDE thanks to @var hint

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'Account deactivated.');
    }
}
