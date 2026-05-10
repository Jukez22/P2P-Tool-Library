<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credential=$request->validate([
            'email'=> 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credential)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard'); // 
        }

        return back()->withErrors([
            'email' => 'The credentials is not correct.',
        ])->onlyInput('email');

    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:50',
            'phone'    => 'required|string|max:20',
            'email'    => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:lender,borrower,libraian,technician',
            'address'  => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name'               => $data['name'],
            'phone'              => $data['phone'],
            'email'              => $data['email'],
            'password'           => $data['password'], // Automatically hashed by the User model cast
            'role'               => $data['role'],
            'address'            => $data['address'] ?? null,
            'membership_tier_id' => 1, // Default to first tier
            'trust_score'        => 0, // Initial score
        ]);

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Registration successful!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');  
    }
}
