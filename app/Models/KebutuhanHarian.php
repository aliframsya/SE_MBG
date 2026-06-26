<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KebutuhanHarian extends Model
{
    protected $table = 'kebutuhan_harians';

    protected $fillable = [
        'tanggal',
        'total_pm',
        'buffer_persen',
        'budget_harian',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_pm' => 'integer',
        'buffer_persen' => 'double',
        'budget_harian' => 'double',
    ];

    // Methods from Class Diagram
    public function hitungTotalKebutuhan(): array
    {
        // Finds published menus for this day and aggregates ingredients
        $menus = KaryawanMenu::where('tanggal', $this->tanggal)
            ->where('status', 'published')
            ->get();

        $totals = [];
        $totalCost = 0;

        foreach ($menus as $menu) {
            $ingredients = $menu->hitungTotalBahan();
            foreach ($ingredients as $item) {
                $id = $item['id'];
                // Apply daily nutritionist buffer percentage (e.g. 10% -> 0.10)
                $qtyWithBuffer = $item['total_qty'] * (1 + ($this->buffer_persen / 100));
                
                $bahan = BahanBaku::find($id);
                $cost = $qtyWithBuffer * ($bahan ? $bahan->harga : 0);
                $totalCost += $cost;

                if (isset($totals[$id])) {
                    $totals[$id]['total_qty'] += $qtyWithBuffer;
                    $totals[$id]['cost'] += $cost;
                } else {
                    $totals[$id] = [
                        'id' => $id,
                        'nama' => $item['nama'],
                        'satuan' => $item['satuan'],
                        'total_qty' => $qtyWithBuffer,
                        'cost' => $cost
                    ];
                }
            }
        }

        return [
            'ingredients' => array_values($totals),
            'total_cost' => $totalCost
        ];
    }

    public function validasiBudget(): bool
    {
        $res = $this->hitungTotalKebutuhan();
        return $res['total_cost'] <= $this->budget_harian;
    }

    public function adjustJikaMelebihi()
    {
        $res = $this->hitungTotalKebutuhan();
        if ($res['total_cost'] > $this->budget_harian && $this->buffer_persen > 0) {
            // Keep reducing buffer percent until it fits
            while ($this->buffer_persen > 0 && $res['total_cost'] > $this->budget_harian) {
                $this->buffer_persen = max(0, $this->buffer_persen - 1.0);
                $res = $this->hitungTotalKebutuhan();
            }
            $this->save();
        }
    }
}
