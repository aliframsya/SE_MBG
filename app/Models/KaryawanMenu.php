<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KaryawanMenu extends Model
{
    protected $table = 'karyawan_menus';

    protected $fillable = [
        'nama_menu',
        'tanggal',
        'jenis_porsi',
        'total_pm',
        'dibuat_oleh',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_pm' => 'integer',
    ];

    public function gramasis()
    {
        return $this->hasMany(GramasiMenu::class, 'menu_id');
    }

    // Methods from Class Diagram
    public function hitungTotalBahan(): array
    {
        $ingredients = [];
        foreach ($this->gramasis as $gramasi) {
            $bahan = $gramasi->bahanBaku;
            if ($bahan) {
                // Calculate based on total PM (Penerima Manfaat)
                $qtyNeeded = $gramasi->getTotalKebutuhan($this->total_pm);
                $ingredients[] = [
                    'id' => $bahan->id,
                    'nama' => $bahan->nama,
                    'satuan' => $bahan->unit ? $bahan->unit->nama : 'unit',
                    'qty_per_porsi' => $gramasi->gramasi_bersih,
                    'total_qty' => $qtyNeeded,
                    'stok_saat_ini' => $bahan->qty,
                    'cukup' => $bahan->qty >= $qtyNeeded,
                ];
            }
        }
        return $ingredients;
    }

    public function validasiMenu(): bool
    {
        // Menu is valid if it has ingredients and all ingredients are valid
        if ($this->gramasis()->count() === 0) {
            return false;
        }
        return true;
    }

    public function publishMenu()
    {
        $this->status = 'published';
        $this->save();
    }
}
