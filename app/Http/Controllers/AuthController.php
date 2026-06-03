<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alternative;
use App\Models\Criterion;
use App\Models\AlternativeCriterion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::validate($credentials)) {
            $user = User::where('email', $credentials['email'])->first();
            
            // Generate tab-specific session key
            $sk = 'tab_' . uniqid();
            session(["multi_auth.{$sk}" => $user->id]);

            return $user->isAdmin()
                ? redirect()->route('admin.dashboard', ['sk' => $sk])
                : redirect()->route('user.dashboard', ['sk' => $sk]);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // regular student role
        ]);

        // Automatically generate a unique random NISN
        $randomNisn = 'KIP-' . rand(10000000, 99999999);
        while (Alternative::where('nisn', $randomNisn)->exists()) {
            $randomNisn = 'KIP-' . rand(10000000, 99999999);
        }

        // Automatically create the Alternative profile linked to this user
        $alternative = Alternative::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'nisn' => $randomNisn,
            'email' => $user->email,
            'phone' => null,
            'status' => 'pending',
        ]);

        // Initialize criteria values with 0
        $criteria = Criterion::all();
        foreach ($criteria as $criterion) {
            AlternativeCriterion::create([
                'alternative_id' => $alternative->id,
                'criterion_id' => $criterion->id,
                'value' => 0.0,
            ]);
        }

        // Generate tab-specific session key for registration redirect
        $sk = 'tab_' . uniqid();
        session(["multi_auth.{$sk}" => $user->id]);

        return redirect()->route('user.dashboard', ['sk' => $sk])->with('success', 'Registrasi berhasil! Silakan lengkapi profil pengajuan Anda.');
    }

    public function logout(Request $request)
    {
        $sk = $request->query('sk') ?? $request->input('sk');
        if ($sk) {
            session()->forget("multi_auth.{$sk}");
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}
