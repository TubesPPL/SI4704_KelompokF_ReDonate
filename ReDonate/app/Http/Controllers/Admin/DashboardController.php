<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Item;
use App\Models\Claim;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'          => User::count(),
            'total_active_items'   => Item::where('status', 'active')->count(),
            'completed_this_month' => Claim::where('status', 'completed')
                                           ->whereMonth('updated_at', now()->month)
                                           ->whereYear('updated_at', now()->year)
                                           ->count(),
            'active_events'        => Event::where('status', 'active')->count(),
        ];

        
        $driver = DB::connection()->getDriverName();
        if ($driver === 'sqlite') {
            $monthSelect = "strftime('%Y-%m', updated_at) as month";
        } else {
            $monthSelect = "DATE_FORMAT(updated_at, '%Y-%m') as month";
        }

        
        $monthlyDonations = Claim::where('status', 'completed')
            ->where('updated_at', '>=', now()->subMonths(11)->startOfMonth())
            ->select(DB::raw($monthSelect), DB::raw('count(*) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        
        $labels = [];
        $data   = [];
        for ($i = 11; $i >= 0; $i--) {
            $key      = now()->subMonths($i)->format('Y-m');
            $labels[] = now()->subMonths($i)->translatedFormat('M Y');
            $data[]   = $monthlyDonations[$key] ?? 0;
        }

        $recentItems  = Item::with('user', 'category')->latest()->take(6)->get();
        $recentClaims = Claim::with(['item', 'user'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'labels', 'data', 'recentItems', 'recentClaims'));
    }
}