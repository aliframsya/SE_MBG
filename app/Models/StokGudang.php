<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokGudang extends Model
{
    protected $table = 'stok_gudangs';

    protected $fillable = [
        'bahan_baku_id',
        'tanggal_masuk',
        'kuantitas',
        'lokasi_gudang',
        'metode_fifo',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'kuantitas' => 'double',
    ];

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }

    // Methods from Class Diagram
    public static function keluarkanFIFO(int $bahanBakuId, float $jumlah)
    {
        // Get active lots for this material sorted by date (FIFO)
        $lots = self::where('bahan_baku_id', $bahanBakuId)
            ->where('kuantitas', '>', 0)
            ->orderBy('tanggal_masuk', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $remainingToDeduct = $jumlah;

        foreach ($lots as $lot) {
            if ($remainingToDeduct <= 0) break;

            if ($lot->kuantitas >= $remainingToDeduct) {
                $lot->kuantitas -= $remainingToDeduct;
                $lot->save();
                $remainingToDeduct = 0;
            } else {
                $remainingToDeduct -= $lot->kuantitas;
                $lot->kuantitas = 0;
                $lot->save();
            }
        }

        // Deduct from total stock in BahanBaku as well
        $bahan = BahanBaku::find($bahanBakuId);
        if ($bahan) {
            $bahan->stok = max(0, $bahan->stok - $jumlah);
            $bahan->qty = max(0, $bahan->qty - $jumlah);
            $bahan->save();
        }
    }

    public static function cekStokTersedia(int $bahanBakuId): float
    {
        return (float) self::where('bahan_baku_id', $bahanBakuId)->sum('kuantitas');
    }

    public static function rekonsiliasiFIFO(int $bahanBakuId)
    {
        $bahan = BahanBaku::find($bahanBakuId);
        if (!$bahan) return;

        $actualStock = $bahan->stok;
        $fifoStock = self::cekStokTersedia($bahanBakuId);

        if ($fifoStock != $actualStock) {
            // Adjust the latest lot or add a reconciliation lot
            if ($actualStock > $fifoStock) {
                // Add lot
                self::create([
                    'bahan_baku_id' => $bahanBakuId,
                    'tanggal_masuk' => now()->toDateString(),
                    'kuantitas' => $actualStock - $fifoStock,
                    'lokasi_gudang' => 'Realisasi Penyesuaian',
                    'metode_fifo' => 'FIFO',
                ]);
            } else {
                // Deduct from newest lots down
                $lots = self::where('bahan_baku_id', $bahanBakuId)
                    ->where('kuantitas', '>', 0)
                    ->orderBy('tanggal_masuk', 'desc')
                    ->orderBy('id', 'desc')
                    ->get();

                $toDeduct = $fifoStock - $actualStock;
                foreach ($lots as $lot) {
                    if ($toDeduct <= 0) break;
                    if ($lot->kuantitas >= $toDeduct) {
                        $lot->kuantitas -= $toDeduct;
                        $lot->save();
                        $toDeduct = 0;
                    } else {
                        $toDeduct -= $lot->kuantitas;
                        $lot->kuantitas = 0;
                        $lot->save();
                    }
                }
            }
        }
    }
}
