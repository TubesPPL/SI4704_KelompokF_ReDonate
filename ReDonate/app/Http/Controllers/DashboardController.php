<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || !$user->is_active) {
            Auth::logout();
            return redirect()->route('login');
        }

        $user->load(['logs' => function ($query) {
            $query->latest()->limit(5);
        }]);

        return view('dashboard.dashboard', compact('user'));
    }
}