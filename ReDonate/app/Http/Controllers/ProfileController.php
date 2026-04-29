<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'nullable',
            'address' => 'nullable'
        ]);

        // upload foto
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->extension();

            $file->move(public_path('profile'), $filename);

            $user->photo = $filename;
        }

        // update data
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui');
    }

    public function deactivate()
    {
        $user = Auth::user();

        $user->update([
            'is_active' => false
        ]);

        Auth::logout();

        return redirect('/login');
    }

    public function destroy()
    {
        $user = Auth::user(); // ✅ FIX (bukan firstOrFail)

        Auth::logout();

        $user->delete();

        return redirect('/register');
    }
}