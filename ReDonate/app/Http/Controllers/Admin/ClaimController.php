<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    public function index(Request $request)
    {
        $query = Claim::with(['item', 'user', 'item.user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $claims = $query->paginate(20)->withQueryString();

        return view('admin.claims.index', compact('claims'));
    }
}
