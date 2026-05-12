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
            $user = Auth::user();
            $requestedRole = $request->input('role');

            $isValidRole = false;
            if ($requestedRole === 'member' && in_array($user->role, ['borrower', 'lender'])) {
                $isValidRole = true;
            } elseif ($requestedRole === $user->role) {
                $isValidRole = true;
            }

            if (!$isValidRole) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                $displayRole = ucfirst($requestedRole);
                return back()->withErrors(['email' => "Access Denied: Your account does not have the '$displayRole' role."])->onlyInput('email');
            }

            $request->session()->regenerate();
            return $this->redirectUserByRole($user);
        }

        return back()->withErrors(['email' => 'The credentials are not correct.'])->onlyInput('email');
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
            'password'           => \Illuminate\Support\Facades\Hash::make($data['password']), 
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
            return redirect()->intended(route('maintenance.dashboard'));
        }
        
        return redirect()->intended('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');  
    }
}
