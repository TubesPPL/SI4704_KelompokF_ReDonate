<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Report;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:item,user',
            'id' => 'required|integer',
        ]);

        $subject = null;
        $title = '';
        
        if ($validated['type'] === 'item') {
            $subject = Item::findOrFail($validated['id']);
            $title = $subject->title;
            if ($subject->user_id === Auth::id()) {
                return redirect()->back()->with('error', 'Anda tidak dapat melaporkan barang milik sendiri.');
            }
            $reportableType = Item::class;
        } else {
            $subject = User::findOrFail($validated['id']);
            $title = $subject->name;
            if ($subject->id === Auth::id()) {
                return redirect()->back()->with('error', 'Anda tidak dapat melaporkan diri sendiri.');
            }
            $reportableType = User::class;
        }

        // Mencegah pengguna melakukan spam laporan terhadap konten yang sama
        // dengan membatasi satu laporan per objek dalam rentang 24 jam.
        $recentDuplicate = Report::where('user_id', Auth::id())
            ->where('reportable_type', $reportableType)
            ->where('reportable_id', $subject->id)
            ->where('created_at', '>=', now()->subDay())
            ->exists();

        if ($recentDuplicate) {
            return redirect()->back()->with('warning', 'Anda sudah melaporkan konten ini dalam 24 jam terakhir. Tim kami sedang meninjaunya.');
        }

        return view('reports.create', [
            'type' => $validated['type'],
            'subject' => $subject,
            'title' => $title
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:item,user',
            'id' => 'required|integer',
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $reportableType = $validated['type'] === 'item' ? Item::class : User::class;
        
        // 1. Rate limiting laporan:
        // Setiap pengguna hanya diperbolehkan mengirim maksimal 5 laporan dalam periode 24 jam 
        // untuk mengurangi penyalahgunaan fitur.
        $recentReportsCount = Report::where('user_id', Auth::id())
            ->where('created_at', '>=', now()->subDay())
            ->count();

        if ($recentReportsCount >= 5) {
            return redirect()->back()->with('error', 'Anda telah mencapai batas maksimum pelaporan harian (5 laporan). Coba lagi besok.');
        }

        // 2. Cek Duplikasi
        $recentDuplicate = Report::where('user_id', Auth::id())
            ->where('reportable_type', $reportableType)
            ->where('reportable_id', $validated['id'])
            ->where('created_at', '>=', now()->subDay())
            ->exists();

        if ($recentDuplicate) {
            return redirect()->back()->with('warning', 'Anda sudah melaporkan konten ini dalam 24 jam terakhir. Tim kami sedang meninjaunya.');
        }

        // Simpan laporan
        $report = Report::create([
            'user_id' => Auth::id(),
            'reportable_type' => $reportableType,
            'reportable_id' => $validated['id'],
            'reason' => $validated['reason'],
            'description' => $validated['reason'] === 'Lainnya' ? $validated['description'] : null,
        ]);

        // Notifikasi dikirim ke seluruh admin agar laporan dapat segera ditinjau dan ditindaklanjuti.
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            NotificationService::send(
                $admin->id,
                NotificationService::NEW_REPORT,
                'Laporan Baru Diterima',
                'Terdapat laporan baru dari ' . Auth::user()->name . ' terkait ' . ($validated['type'] === 'item' ? 'Barang' : 'Pengguna') . '.',
                ['action_url' => route('admin.reports.show', $report->id)]
            );
        }

        return redirect()->route('dashboard')->with('success', 'Laporan berhasil dikirim. Terima kasih telah membantu menjaga komunitas ReDonate tetap aman.');
    }
}
