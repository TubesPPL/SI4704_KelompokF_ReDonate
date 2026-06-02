<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount(['items', 'claims'])->latest();

        if ($request->filled('filter')) {
            match ($request->filter) {
                'admin'      => $query->where('role', 'admin'),
                'verified'   => $query->where('is_verified', true),
                'user'       => $query->where('role', 'user'),
                default      => null,
            };
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function toggleRole(User $user)
    {
        $user->role = ($user->role === 'admin') ? 'user' : 'admin';
        $user->save();

        return back()->with('success', "Role {$user->name} diubah menjadi {$user->role}.");
    }

    public function toggleVerified(User $user)
    {
        $user->is_verified = !$user->is_verified;
        $user->save();

        $status = $user->is_verified ? 'terverifikasi' : 'tidak terverifikasi';
        return back()->with('success', "{$user->name} sekarang {$status}.");
    }
}
