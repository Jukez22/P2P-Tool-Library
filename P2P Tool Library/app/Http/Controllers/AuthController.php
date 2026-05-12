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
    $credential = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    if (Auth::attempt($credential)) {
        $request->session()->regenerate();
        return $this->redirectUserByRole(Auth::user());
    }
    return back()->withErrors(['email' => 'The credentials is not correct.'])->onlyInput('email');
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
            'role'     => 'required|in:lender,borrower,librarian,technician',
            'address'  => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name'               => $data['name'],
            'phone'              => $data['phone'],
            'email'              => $data['email'],
            'password'           => $data['password'], 
            'role'               => $data['role'],
            'address'            => $data['address'] ?? null,
            'membership_tier_id' => 1, 
            'trust_score'        => 3, 
        ]);

        Auth::login($user);

        return $this->redirectUserByRole($user);
    }

    protected function redirectUserByRole($user)
    {
        $role = $user->role;
        
        if ($role == 'borrower' || $role == 'lender') {
            return redirect()->intended(route('member.dashboard'));
        }
        
        if ($role == 'librarian') {
            return redirect()->intended(route('librarian.dashboard'));
        }
        
        if ($role == 'technician') {
            // Adjusting to maintenance queue as the default for technicians
            return redirect()->intended(route('maintenance.queue.index'));
        }
        
        return redirect()->intended('welcome');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');  
    }
}
