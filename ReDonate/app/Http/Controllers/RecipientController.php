<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipientController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Load claims dengan item dan donatur
        $claims = $user->claims()->with(['item', 'item.user'])->latest()->paginate(10);
        
        // Statistik
        $pendingCount = $user->claims()->where('status', 'pending')->count();
        $approvedCount = $user->claims()->where('status', 'approved')->count();
        $completedCount = $user->claims()->where('status', 'completed')->count();
        $rejectedCount = $user->claims()->where('status', 'rejected')->count();

        return view('recipient.dashboard', compact('claims', 'pendingCount', 'approvedCount', 'completedCount', 'rejectedCount'));
    }
}
