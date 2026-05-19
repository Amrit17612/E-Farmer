<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember    = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been blocked by the administrator.']);
            }
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:8|confirmed',
            'phone'     => 'nullable|string|max:15',
            'location'  => 'nullable|string|max:255',
            'farm_size' => 'nullable|numeric|min:0',
            'role'      => 'required|in:' . User::ROLE_FARMER . ',' . User::ROLE_BUYER,
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'phone'     => $request->phone,
            'location'  => $request->location,
            'farm_size' => $request->role === User::ROLE_FARMER ? $request->farm_size : null,
            'role'      => $request->role,
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Welcome to eFarmar, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }

    public function redirectToGoogle()
    {
        // If real keys are not set, simulate the Google flow for local testing
        if (env('GOOGLE_CLIENT_ID') === 'your_google_client_id' || empty(env('GOOGLE_CLIENT_ID'))) {
            return redirect('/auth/google/callback?simulated=1');
        }
        
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            if ($request->has('simulated')) {
                // Create a simulated Google User object for testing
                $googleUser = new \stdClass();
                $googleUser->id = '10484738291038475';
                $googleUser->name = 'Google Test User';
                $googleUser->email = 'test.google@example.com';
                $googleUser->avatar = 'https://ui-avatars.com/api/?name=Google+Test+User&background=4285F4&color=fff';
                $googleUser->token = 'simulated_token_123';
                $googleUser->refreshToken = 'simulated_refresh_token_123';
            } else {
                $googleUser = Socialite::driver('google')->user();
            }

            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name'                 => $googleUser->name,
                    'google_id'            => $googleUser->id,
                    'avatar'               => $googleUser->avatar,
                    'google_token'         => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'password'             => Hash::make(str()->random(16)),
                ]
            );

            // Assign a default role if the user was just created and doesn't have one
            if (!$user->role) {
                $user->role = User::ROLE_FARMER;
                $user->save();
            }

            // Check if user is blocked
            if (!$user->is_active) {
                return redirect()->route('login')->withErrors(['email' => 'Your account has been blocked by the administrator.']);
            }

            Auth::login($user);
            return redirect()->route('dashboard')->with('success', 'Successfully logged in with Google as ' . $user->name . '!');
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Google login failed: ' . $e->getMessage()]);
        }
    }
}
