<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\BahanBaku;
use App\Models\StokGudang;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = [];

        // Pengajuan menunggu approval
        $pendingSubmissions = Submission::where('status', 'diajukan')->get();

        foreach ($pendingSubmissions as $submission) {
            $notifications[] = [
                'type' => 'warning',
                'message' => "Pengajuan #{$submission->id} menunggu approval"
            ];
        }

        // Pengajuan ditolak
        $rejectedSubmissions = Submission::where('status', 'ditolak')->get();

        foreach ($rejectedSubmissions as $submission) {
            $notifications[] = [
                'type' => 'danger',
                'message' => "Pengajuan #{$submission->id} ditolak"
            ];
        }

        // Stok kritis — cek per lot FIFO yang kuantitas > 0 dan <= 50
        $lowStockLots = StokGudang::with('bahanBaku.kitchen')
            ->where('kuantitas', '>', 0)
            ->where('kuantitas', '<=', 50)
            ->orderBy('kuantitas', 'asc')
            ->get();

        foreach ($lowStockLots as $lot) {
            $kitchenName = $lot->bahanBaku->kitchen->nama ?? 'Pusat';
            $notifications[] = [
                'type' => 'danger',
                'message' => "⚠️ Stok Kritis: {$lot->bahanBaku->nama} ({$kitchenName}) tersisa {$lot->kuantitas} unit di lot FIFO"
            ];
        }

        $totalLowStock = $lowStockLots->count();

        $summary = [
            'pending' => $pendingSubmissions->count(),
            'rejected' => $rejectedSubmissions->count(),
            'low_stock' => $totalLowStock,
        ];

        return view(
            'notifications.index',
            compact(
                'notifications',
                'totalLowStock',
                'summary'
            )
        );
    }
}