<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Event;
use App\Models\StokGudang;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 2. Tambahkan custom Gate ini
        Gate::define('report.sales-summary.legacy', function ($user) {
            // Pastikan user secara default memang punya peran/permission untuk melihat laporan
            if (!$user->hasPermissionTo('report.sales-summary.view')) {
                return false;
            }
            // Cek apakah dapur-dapur milik user pernah melakukan transaksi (submission) sebelum 1 April 2026
            return $user->kitchens()->whereHas('submissions', function ($query) {
                $query->whereDate('tanggal', '<', '2026-04-01');
            })->exists();
        });

        // Badge dinamis di ikon lonceng Notifikasi (navbar)
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
            $lowStockCount = StokGudang::where('kuantitas', '>', 0)
                ->where('kuantitas', '<=', 50)
                ->count();

            if ($lowStockCount > 0) {
                // Hapus item Notifikasi lama (tanpa badge)
                $event->menu->remove('notifikasi');

                // Tambah ulang dengan badge
                $event->menu->add([
                    'key'  => 'notifikasi',
                    'text' => 'Notifikasi',
                    'icon' => 'fas fa-bell',
                    'url'  => 'notifications',
                    'topnav_right' => true,
                    'label' => $lowStockCount,
                    'label_color' => 'danger',
                ]);
            }
        });
    }
}
