<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\BahanBaku;
use App\Models\Supplier;

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

    // Stok hampir habis
    $lowStocks = BahanBaku::whereColumn(
    'stok',
    '<=',
    'stok_minimal'
    )
    ->orderBy('stok')
    ->limit(5)
    ->get();

foreach ($lowStocks as $item) {
    $notifications[] = [
        'type' => 'danger',
        'message' => "Stok {$item->nama} tinggal {$item->stok}"
    ];
}
$totalLowStock = BahanBaku::whereColumn(
    'stok',
    '<=',
    'stok_minimal'
)->count();

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