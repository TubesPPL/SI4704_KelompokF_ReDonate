<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * PBI #2: Tampilkan detail profil
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user || !$user->is_active) {
            return redirect('/login')->with('error', 'Akun tidak aktif');
        }

        return view('profile.index', compact('user'));
    }

    /**
     * PBI #3: Update profil dengan validasi lengkap
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->is_active) {
            return back()->with('error', 'Akun tidak ditemukan atau tidak aktif');
        }

        // ✅ FIX 1: Regex phone diperbaiki
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20|regex:/^([0-9\s\-\+\$\$]*)$/u', // ✅ Fixed
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'password' => 'nullable|min:8|confirmed',
        ], [
            'email.unique' => 'Email sudah digunakan.',
            'phone.regex' => 'Format nomor telepon tidak valid.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Update data dalam 1 query (performance)
        $updateData = $request->only(['name', 'email', 'phone', 'address']);

        // ✅ FIX 2: Handle photo dengan konsisten photo_url
        if ($request->hasFile('photo')) {
            // Hapus foto lama
            if ($user->photo_url && Storage::disk('public')->exists($user->photo_url)) {
                Storage::disk('public')->delete($user->photo_url);
            }

            $path = $request->file('photo')->store('profile', 'public');
            $updateData['photo_url'] = $path; // ✅ Simpan path relatif
        }

        // Password change rate limiting
        if ($request->filled('password')) {
            $rateKey = 'password-change:' . $user->id;
            if (RateLimiter::tooManyAttempts($rateKey, 3)) {
                return back()->with('error', 'Terlalu sering ganti password');
            }
            $updateData['password'] = Hash::make($request->password);
            RateLimiter::hit($rateKey, 900); // 15 menit cooldown
        }

        // Log data lama
        $oldData = $user->only(['name', 'email', 'phone', 'address', 'photo_url']);

        // ✅ FIX 3: Single update query
        $user->update($updateData);

        // Log aktivitas
        UserLog::create([
            'user_id' => $user->id,
            'action' => 'update_profile',
            'old_data' => json_encode($oldData),
            'new_data' => json_encode($user->fresh()->only(['name', 'email', 'phone', 'address', 'photo_url'])),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Profil berhasil diupdate');
    }

    /**
     * PBI #4: Deaktivasi akun dengan konfirmasi
     */
    public function deactivate(Request $request)
    {
        $request->validate([
            'confirm_password' => 'required|min:6'
        ]);

        $user = Auth::user();

        if (!$user || !$user->is_active) {
            return back()->with('error', 'Akun tidak aktif');
        }

        // ✅ Password verification
        if (!Hash::check($request->confirm_password, $user->password)) {
            return back()->with('error', 'Password salah');
        }

        $user->update(['is_active' => false]);

        UserLog::create([
            'user_id' => $user->id,
            'action' => 'deactivate_account',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/')->with('info', 'Akun dinonaktifkan. Kontak admin untuk aktivasi.');
    }

    /**
     * PBI #4: Hapus permanen dengan double konfirmasi
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'confirm_password' => 'required|min:6',
            'confirm_delete' => 'accepted'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->confirm_password, $user->password)) {
            return back()->with('error', 'Password salah');
        }

        // ✅ FIX 4: Log sebelum delete (prevent cascade loss)
        UserLog::create([
            'user_id' => $user->id,
            'action' => 'delete_account',
            'old_data' => json_encode($user->toArray()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $user->delete();
        Auth::logout();

        return redirect('/')->with('success', 'Akun dihapus permanen');
    }
}