<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $table = 'budgets';

    protected $fillable = [
        'tanggal',
        'jenis_budget',
        'total_budget',
        'total_realisasi',
        'sisa',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_budget' => 'double',
        'total_realisasi' => 'double',
        'sisa' => 'double',
    ];

    // Methods from Class Diagram
    public function cekSisaBudget(): float
    {
        $this->sisa = $this->total_budget - $this->total_realisasi;
        $this->save();
        return (float) $this->sisa;
    }

    public function addRealisasi(float $nominal)
    {
        $this->total_realisasi += $nominal;
        $this->sisa = $this->total_budget - $this->total_realisasi;
        $this->save();
    }

    public function isMelebihi(): bool
    {
        return $this->total_realisasi > $this->total_budget;
    }
}
