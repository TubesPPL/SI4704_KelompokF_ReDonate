<?php

namespace App\Http\Middleware;

use App\Models\UserLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        // ❌ JANGAN redirect ke login di sini (biar ditangani middleware auth)
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // ❌ Hindari logout + redirect berulang
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Akun tidak aktif');
        }

        $allowedRoles = [$role, 'both'];

        if (!in_array($user->role, $allowedRoles)) {

            // Rate limit
            $rateKey = 'role-access:' . $user->id . ':' . $request->ip();
            if (RateLimiter::tooManyAttempts($rateKey, 10)) {
                $seconds = RateLimiter::availableIn($rateKey);
                return response("Terlalu banyak percobaan. Tunggu {$seconds}s", 429);
            }

            RateLimiter::hit($rateKey, 60);

            // Log
            UserLog::create([
                'user_id' => $user->id,
                'action' => 'unauthorized_access',
                'old_data' => json_encode([
                    'requested_role' => $role,
                    'user_role' => $user->role
                ]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // 🔥 FIX UTAMA: JANGAN redirect ke route lain
            return response('Akses ditolak (403)', 403);
        }

        return $next($request);
    }
}