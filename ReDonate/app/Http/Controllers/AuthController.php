<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'phone' => 'nullable|string|max:20|regex:/^([0-9\s\-\+\$]*)$/u',
            'address' => 'nullable|string|max:500',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/',
            'role' => ['required', Rule::in(['donatur', 'penerima', 'both'])],
            'terms' => 'required|accepted',
        ], [
            'password.regex' => 'Password harus: 8+ char, huruf kecil, BESAR, angka',
            'role.in' => 'Role tidak valid.',
            'phone.regex' => 'Format nomor telepon tidak valid.',
            'terms.accepted' => 'Anda harus setuju dengan syarat & ketentuan.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => true,
        ]);

        UserLog::create([
            'user_id' => $user->id,
            'action' => 'register',
            'new_data' => json_encode([
                'name' => $user->name, 
                'email' => $user->email, 
                'role' => $user->role
            ]),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan login.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $key = 'login:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->with('error', "Terlalu banyak percobaan. Coba lagi {$seconds}s.");
        }

        $user = User::where('email', $request->email)
            ->where('is_active', true)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            RateLimiter::hit($key, 60);
            return back()->with('error', 'Email atau password salah');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate(true);

        UserLog::create([
            'user_id' => $user->id,
            'action' => 'login_success',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return $this->redirectToDashboard($user->role);
    }

    private function redirectToDashboard(string $role): \Illuminate\Http\RedirectResponse
    {
        return match($role) {
            'donatur' => redirect()->route('dashboard.donatur'),
            'penerima' => redirect()->route('dashboard.penerima'),
            'both' => redirect()->route('dashboard'),
            default => redirect()->route('profile.index')
        };
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        
        if ($user) {
            UserLog::create([
                'user_id' => $user->id,
                'action' => 'logout',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}