<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerimaanBarang extends Model
{
    protected $table = 'penerimaan_barangs';

    protected $fillable = [
        'po_id',
        'tanggal_terima',
        'kondisi_bahan',
        'kuantitas_rijek',
        'status_rijek',
    ];

    protected $casts = [
        'tanggal_terima' => 'date',
        'kuantitas_rijek' => 'double',
        'status_rijek' => 'boolean',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    // Methods from Class Diagram
    public function terimaBahan()
    {
        $po = $this->purchaseOrder;
        if ($po) {
            $po->status = 'selesai';
            $po->save();

            // Perform stock update for each item in PO
            $this->updateStok();
        }
    }

    public function cekKualitas(): string
    {
        if (strtolower($this->kondisi_bahan) === 'baik') {
            return 'Lolos QC';
        }
        return 'Gagal QC / Butuh Retur';
    }

    public function prosesRijek()
    {
        if ($this->kuantitas_rijek > 0) {
            $this->status_rijek = true;
            $this->save();
        }
    }

    public function updateStok()
    {
        $po = $this->purchaseOrder;
        if (!$po) return;

        foreach ($po->details as $detail) {
            $bahan = $detail->bahanBaku;
            if ($bahan) {
                // Kuantitas yang diterima bersih = kuantitas pesan - kuantitas rijek
                $qtyDiterima = max(0, $detail->kuantitas_pesan - $this->kuantitas_rijek);
                $detail->kuantitas_diterima = $qtyDiterima;
                $detail->save();

                // Update stock in BahanBaku table (both stok and qty)
                $bahan->stok += $qtyDiterima;
                $bahan->qty += $qtyDiterima;
                $bahan->save();

                // Log into StokGudang lot for FIFO tracking
                StokGudang::create([
                    'bahan_baku_id' => $bahan->id,
                    'tanggal_masuk' => $this->tanggal_terima ? $this->tanggal_terima->toDateString() : now()->toDateString(),
                    'kuantitas' => $qtyDiterima,
                    'lokasi_gudang' => 'Gudang Utama',
                    'metode_fifo' => 'FIFO',
                ]);
            }
        }
    }

    /**
     * Re-sync semua stok gudang (FIFO lots) berdasarkan data penerimaan yang sudah ada.
     */
    public static function resyncAllStock()
    {
        StokGudang::truncate();

        $penerimaans = self::with('purchaseOrder.details.bahanBaku')->get();

        foreach ($penerimaans as $penerimaan) {
            $po = $penerimaan->purchaseOrder;
            if (!$po) continue;

            foreach ($po->details as $detail) {
                $bahan = $detail->bahanBaku;
                if (!$bahan) continue;

                $qtyDiterima = max(0, $detail->kuantitas_pesan - $penerimaan->kuantitas_rijek);

                StokGudang::create([
                    'bahan_baku_id' => $bahan->id,
                    'tanggal_masuk' => $penerimaan->tanggal_terima ? $penerimaan->tanggal_terima->toDateString() : now()->toDateString(),
                    'kuantitas' => $qtyDiterima,
                    'lokasi_gudang' => 'Gudang Utama',
                    'metode_fifo' => 'FIFO',
                ]);
            }
        }

        // Sync bahan_baku.stok with FIFO totals
        $bahanBakus = BahanBaku::all();
        foreach ($bahanBakus as $bahan) {
            $totalFifo = StokGudang::where('bahan_baku_id', $bahan->id)->sum('kuantitas');
            $bahan->stok = $totalFifo;
            $bahan->qty = $totalFifo;
            $bahan->save();
        }
    }
}
