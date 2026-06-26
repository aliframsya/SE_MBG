<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPO extends Model
{
    protected $table = 'detail_pos';

    protected $fillable = [
        'po_id',
        'bahan_baku_id',
        'kuantitas_pesan',
        'harga_satuan',
        'kuantitas_diterima',
    ];

    protected $casts = [
        'kuantitas_pesan' => 'double',
        'harga_satuan' => 'double',
        'kuantitas_diterima' => 'double',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }

    // Methods from Class Diagram
    public function getSubTotal(): float
    {
        return (float) ($this->kuantitas_pesan * $this->harga_satuan);
    }

    public function getSelisih(): float
    {
        return (float) ($this->kuantitas_pesan - $this->kuantitas_diterima);
    }
}
