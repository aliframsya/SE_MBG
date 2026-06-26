<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayarans';

    protected $fillable = [
        'po_id',
        'tanggal_bayar',
        'jumlah_bayar',
        'metode_bayar',
        'status_bayar',
        'bukti_transfer',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'jumlah_bayar' => 'double',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    // Methods from Class Diagram
    public function prosesPembayaran()
    {
        $this->status_bayar = 'pending';
        $this->save();
    }

    public function konfirmasiBayar()
    {
        $this->status_bayar = 'lunas';
        $this->save();

        // Register to budget realization if a budget exists for this day
        $budget = Budget::where('tanggal', $this->tanggal_bayar)->first();
        if ($budget) {
            $budget->addRealisasi($this->jumlah_bayar);
        } else {
            // Create a general budget row if not exists
            Budget::create([
                'tanggal' => $this->tanggal_bayar ?: now()->toDateString(),
                'jenis_budget' => 'Operasional PO',
                'total_budget' => $this->jumlah_bayar * 1.5, // estimate
                'total_realisasi' => $this->jumlah_bayar,
                'sisa' => ($this->jumlah_bayar * 1.5) - $this->jumlah_bayar
            ]);
        }
    }
}
