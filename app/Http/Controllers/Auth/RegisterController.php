<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Notifications\NewUserRegisteredNotification;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required','confirmed', Password::min(8)->mixedCase()->letters()->numbers()->symbols()],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => strip_tags($validated['name']),
            'email' => strip_tags($validated['email']),
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        $user->assignRole('customer'); // default role

        $admins = User::role('admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewUserRegisteredNotification($user));
        }


        Auth::login($user);
        return redirect()->route('customer.dashboard');
    }
}
