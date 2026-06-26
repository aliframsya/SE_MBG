<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GramasiMenu extends Model
{
    protected $table = 'gramasi_menus';

    protected $fillable = [
        'menu_id',
        'bahan_baku_id',
        'gramasi_bersih',
        'gramasi_kotor',
    ];

    protected $casts = [
        'gramasi_bersih' => 'double',
        'gramasi_kotor' => 'double',
    ];

    public function menu()
    {
        return $this->belongsTo(KaryawanMenu::class, 'menu_id');
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }

    // Methods from Class Diagram
    public function getTotalKebutuhan(int $pm): float
    {
        // Multiply clean/gross grammage by the number of portions (total PM)
        // Usually, total requirement uses gross grammage (gramasiKotor)
        return (float) ($this->gramasi_kotor * $pm);
    }

    public function getGramasiWithBuffer(): float
    {
        return (float) $this->gramasi_kotor;
    }
}
