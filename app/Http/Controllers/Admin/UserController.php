<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        $roles = Role::all();
        return view('admin.users.index', compact('users','roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', Password::min(8)->mixedCase()->letters()->numbers()->symbols()],
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
            'role'     => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'     => strip_tags($validated['name']),
            'email'    => strip_tags($validated['email']),
            'password' => Hash::make($validated['password']),
            'phone'    => $validated['phone'] ?? null,
            'address'  => $validated['address'] ?? null,
        ]);

        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')->with('success','User berhasil ditambahkan');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required','string','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
            'role'     => 'required|exists:roles,name',
            'old_password' => 'nullable|required_with:new_password',
            'new_password' => ['nullable','confirmed', Password::min(8)->mixedCase()->letters()->numbers()->symbols()],
        ]);

        $data = [
            'name'    => strip_tags($validated['name']),
            'email'   => strip_tags($validated['email']),
            'phone'   => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ];

        // Update password jika ada
        if ($request->filled('new_password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'Password lama tidak sesuai.']);
            }
            $data['password'] = Hash::make($request->new_password);
        }

        $user->update($data);
        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')->with('success','User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success','User berhasil dihapus');
    }
}
