<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Report;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with(['reporter', 'reportable'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $typeClass = $request->type === 'item' ? Item::class : User::class;
            $query->where('reportable_type', $typeClass);
        }

        $reports = $query->paginate(20)->withQueryString();

        return view('admin.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $report->load(['reporter', 'reportable']);
        
        // Cek jika sedang direview oleh admin lain? Bisa tambahkan logika tapi untuk sekarang ubah ke reviewing jika pending
        if ($report->status === 'pending') {
            $report->update(['status' => 'reviewing']);
        }

        // Ambil riwayat laporan untuk subject yang sama
        $historyReports = Report::where('reportable_type', $report->reportable_type)
            ->where('reportable_id', $report->reportable_id)
            ->where('id', '!=', $report->id)
            ->latest()
            ->get();

        return view('admin.reports.show', compact('report', 'historyReports'));
    }

    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:resolved,rejected',
            'action' => 'required|in:no_action,warn_user,suspend_item,ban_user',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        // Simpan keputusan
        $report->status = $validated['status'];
        $report->admin_notes = $validated['admin_notes'];
        $report->save();

        // Ambil user yang dilaporkan atau pemilik barang
        $targetUser = null;
        if ($report->reportable_type === User::class) {
            $targetUser = $report->reportable;
        } elseif ($report->reportable_type === Item::class) {
            $targetUser = $report->reportable->user;
        }

        // Eksekusi Tindakan jika ditargetkan ke user dan status resolved
        if ($report->status === 'resolved' && $targetUser) {
            switch ($validated['action']) {
                case 'warn_user':
                    NotificationService::send(
                        $targetUser->id,
                        NotificationService::MODERATION_WARNING,
                        'Peringatan Moderasi',
                        'Akun Anda menerima peringatan terkait aktivitas atau konten yang dilaporkan melanggar pedoman komunitas kami. Harap patuhi aturan agar akun Anda tetap aktif.'
                    );
                    break;
                
                case 'suspend_item':
                    if ($report->reportable_type === Item::class) {
                        $item = $report->reportable;
                        $item->status = 'cancelled';
                        $item->save();
                        
                        NotificationService::send(
                            $targetUser->id,
                            NotificationService::MODERATION_WARNING,
                            'Barang Diturunkan',
                            'Barang Anda "' . $item->title . '" telah diturunkan oleh admin karena melanggar pedoman komunitas.'
                        );
                    }
                    break;

                case 'ban_user':
                    $targetUser->is_banned = true;
                    $targetUser->is_verified = false;
                    $targetUser->save();

                    // Optional: cancel all their active items
                    $targetUser->items()->where('status', 'active')->update(['status' => 'cancelled']);
                    break;
            }
        }

        // Notifikasi ke pelapor
        NotificationService::send(
            $report->user_id,
            NotificationService::REPORT_RESOLVED,
            'Laporan Ditindaklanjuti',
            'Laporan Anda terkait ' . ($report->reportable_type === Item::class ? 'barang' : 'pengguna') . ' telah ditinjau dan ditindaklanjuti oleh tim moderasi. Terima kasih!',
            ['action_url' => '#']
        );

        // Jika suspend_item atau ban_user, tandai laporan lain terkait subject ini sebagai resolved
        if (in_array($validated['action'], ['suspend_item', 'ban_user'])) {
            Report::where('reportable_type', $report->reportable_type)
                ->where('reportable_id', $report->reportable_id)
                ->whereIn('status', ['pending', 'reviewing'])
                ->update([
                    'status' => 'resolved',
                    'admin_notes' => 'Otomatis di-resolve bersamaan dengan laporan #' . $report->id
                ]);
        }

        return redirect()->route('admin.reports.index')->with('success', 'Keputusan laporan berhasil disimpan.');
    }
}
