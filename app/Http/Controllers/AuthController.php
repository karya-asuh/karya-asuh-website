<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Show the registration form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Register user
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:100|unique:users',
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:donor,panti,admin',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect('/login')->with('success', 'Registration successful! Please login.');
    }

    // Login user
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        $user = User::where('username', $credentials['username'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return redirect()->back()->withErrors(['login_error' => 'Invalid username or password.']);
        }
        
        Auth::login($user, true);
        session(['user_id' => Auth::id()]);
        // dd(Auth::user());

        Log::info('User logged in:', ['user' => $user]);

        Log::info('Session Data:', session()->all());

        
        if (Auth::check()) {
            Log::info('Checked');
            return redirect('/')->with('success', 'Login successful!');
        } else {
            return redirect('/login')->withErrors(['login_error' => 'Authentication failed.']);
        }        
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();  // Invalidates the session
        $request->session()->regenerateToken();  // Regenerates the CSRF token to prevent CSRF attacks
        return redirect('/login')->with('success', 'Logged out successfully!');
    }

    public function showUserDetail(){
        $panti = DB::select("
            SELECT users.*, panti_details.*
            FROM users
            LEFT JOIN panti_details ON users.user_id = panti_details.panti_id
            WHERE users.user_id = :id
        ", ['id' => Auth::id()]);

        return response()->json($panti);
    }
}