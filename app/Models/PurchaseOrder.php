<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'kode_po',
        'tanggal_po',
        'tanggal_pengiriman',
        'supplier_id',
        'status',
        'total_harga',
    ];

    protected $casts = [
        'tanggal_po' => 'date',
        'tanggal_pengiriman' => 'date',
        'total_harga' => 'double',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function details()
    {
        return $this->hasMany(DetailPO::class, 'po_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'po_id');
    }

    public function penerimaan()
    {
        return $this->hasOne(PenerimaanBarang::class, 'po_id');
    }

    // Methods from Class Diagram
    public function buatPO()
    {
        $this->status = 'draft';
        $this->save();
    }

    public function konfirmasiPO()
    {
        $this->status = 'dikirim';
        $this->save();
    }

    public function batalPO()
    {
        $this->status = 'dibatalkan';
        $this->save();
    }

    public function getTotalHarga(): float
    {
        $total = $this->details->sum(function ($detail) {
            return $detail->getSubTotal();
        });
        $this->total_harga = $total;
        $this->save();
        return (float) $total;
    }
}
