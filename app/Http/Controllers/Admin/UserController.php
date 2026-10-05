<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = trim($request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        switch ($request->input('filter', 'all')) {
            case 'active':
                $query->where('is_active', true);
                break;
            case 'inactive':
                $query->where('is_active', false);
                break;
            case 'admin':
                $query->where('role', 'admin');
                break;
            case 'editor':
                $query->where('role', 'editor');
                break;
        }

        $users = $query->orderBy('role')->orderBy('name')->paginate(20)->withQueryString();

        $stats = [
            'total'   => User::count(),
            'active'  => User::where('is_active', true)->count(),
            'admins'  => User::where('role', 'admin')->count(),
            'editors' => User::where('role', 'editor')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats', 'search'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:120'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'        => ['nullable', 'string', 'max:40'],
            'role'         => ['required', 'in:admin,editor'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
            'avatar_file'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active'    => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('avatar_file')) {
            $validated['avatar'] = $this->storeAvatar($request->file('avatar_file'));
        }
        unset($validated['avatar_file']);

        $validated['password']  = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;

        User::create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('status', "User \"{$validated['name']}\" has been created.");
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:120'],
            'email'        => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'        => ['nullable', 'string', 'max:40'],
            'role'         => ['required', 'in:admin,editor'],
            'password'     => ['nullable', 'string', 'min:8', 'confirmed'],
            'avatar_file'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active'    => ['nullable', 'boolean'],
        ]);

        /* ---------- Safety guards ---------- */

        // Cannot demote the last admin
        if ($user->role === 'admin' && $validated['role'] !== 'admin') {
            if (User::where('role', 'admin')->count() <= 1) {
                return back()->withErrors(['role' => 'You cannot demote the last remaining admin.'])->withInput();
            }
        }

        // Cannot deactivate yourself
        if ($user->id === Auth::id() && ! $request->boolean('is_active')) {
            return back()->withErrors(['is_active' => 'You cannot deactivate your own account.'])->withInput();
        }

        // Cannot deactivate the last active admin
        if ($user->role === 'admin' && $user->is_active && ! $request->boolean('is_active')) {
            if (User::where('role', 'admin')->where('is_active', true)->count() <= 1) {
                return back()->withErrors(['is_active' => 'You cannot deactivate the last active admin.'])->withInput();
            }
        }

        /* ---------- Avatar ---------- */
        if ($request->hasFile('avatar_file')) {
            if ($user->avatar && Storage::disk('public_uploads')->exists($user->avatar)) {
                Storage::disk('public_uploads')->delete($user->avatar);
            }
            $validated['avatar'] = $this->storeAvatar($request->file('avatar_file'));
        }
        unset($validated['avatar_file']);

        if ($request->boolean('remove_avatar') && $user->avatar) {
            if (Storage::disk('public_uploads')->exists($user->avatar)) {
                Storage::disk('public_uploads')->delete($user->avatar);
            }
            $validated['avatar'] = null;
        }

        /* ---------- Password ---------- */
        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : false;

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('status', "User \"{$user->name}\" has been updated.");
    }

    public function toggle(User $user)
    {
        // Cannot toggle yourself
        if ($user->id === Auth::id()) {
            return back()->withErrors(['toggle' => 'You cannot change your own active status.']);
        }

        // Cannot deactivate the last active admin
        if ($user->role === 'admin' && $user->is_active) {
            if (User::where('role', 'admin')->where('is_active', true)->count() <= 1) {
                return back()->withErrors(['toggle' => 'You cannot deactivate the last active admin.']);
            }
        }

        $user->update(['is_active' => ! $user->is_active]);

        $state = $user->is_active ? 'activated' : 'deactivated';

        return back()->with('status', "\"{$user->name}\" has been {$state}.");
    }

    public function destroy(User $user)
    {
        // Cannot delete yourself
        if ($user->id === Auth::id()) {
            return back()->withErrors(['delete' => 'You cannot delete your own account.']);
        }

        // Cannot delete the last admin
        if ($user->role === 'admin') {
            if (User::where('role', 'admin')->count() <= 1) {
                return back()->withErrors(['delete' => 'You cannot delete the last remaining admin.']);
            }
        }

        $name = $user->name;

        if ($user->avatar && Storage::disk('public_uploads')->exists($user->avatar)) {
            Storage::disk('public_uploads')->delete($user->avatar);
        }

        $user->delete();

        return back()->with('status', "User \"{$name}\" has been deleted.");
    }

    private function storeAvatar($file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $base      = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
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