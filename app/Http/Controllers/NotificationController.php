<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\StokGudang;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = [];

        // =============================================
        // Stok kritis — cek per lot FIFO
        // =============================================
        $lowStockLots = StokGudang::with('bahanBaku.kitchen')
            ->where('kuantitas', '>', 0)
            ->where('kuantitas', '<=', 50)
            ->orderBy('kuantitas', 'asc')
            ->get();

        foreach ($lowStockLots as $lot) {
            $kitchenName = $lot->bahanBaku->kitchen->nama ?? 'Pusat';
            $notifications[] = [
                'type' => 'danger',
                'category' => 'low_stock',
                'message' => "⚠️ Stok Kritis: {$lot->bahanBaku->nama} ({$kitchenName}) tersisa {$lot->kuantitas} unit di lot FIFO",
                'url' => null,
                'created_at' => $lot->updated_at,
            ];
        }

        $totalLowStock = $lowStockLots->count();

        // Summary counts
        $summary = [
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