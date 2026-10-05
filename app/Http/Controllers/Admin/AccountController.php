<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    /**
     * Show the account settings form.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        return view('admin.account.index', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:120'],
            'email'        => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'        => ['nullable', 'string', 'max:40'],
            'avatar_file'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        /* ---------- Avatar upload ---------- */
        if ($request->hasFile('avatar_file')) {
            if ($user->avatar && Storage::disk('public_uploads')->exists($user->avatar)) {
                Storage::disk('public_uploads')->delete($user->avatar);
            }
            $validated['avatar'] = $this->storeAvatar($request->file('avatar_file'));
        }
        unset($validated['avatar_file']);

        /* ---------- Remove avatar ---------- */
        if ($request->boolean('remove_avatar') && $user->avatar) {
            if (Storage::disk('public_uploads')->exists($user->avatar)) {
                Storage::disk('public_uploads')->delete($user->avatar);
            }
            $validated['avatar'] = null;
        }

        $user->update($validated);

        return redirect()
            ->route('admin.account.index')
            ->with('status', 'Your profile has been updated.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.confirmed' => 'The new password confirmation does not match.',
            'password.min'       => 'Your new password must be at least 8 characters.',
        ]);

        /* ---------- Verify the current password ---------- */
        if (! Hash::check($request->input('current_password'), $user->password)) {
            return back()
                ->withErrors(['current_password' => 'The current password you entered is incorrect.'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return redirect()
            ->route('admin.account.index')
            ->with('status', 'Your password has been changed successfully.');
    }

    /* ============================================================
       HELPERS
       ============================================================ */

    private function storeAvatar($file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $base      = \Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $filename  = $base . '-' . time() . '-' . bin2hex(random_bytes(3)) . '.' . $extension;
        $path      = 'images/avatars/' . $filename;

        $disk = Storage::disk('public_uploads');
        if (! $disk->exists('images/avatars')) {
            $disk->makeDirectory('images/avatars');
        }
        $disk->put($path, file_get_contents($file));

        return $path;
    }
}