<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    protected $table = 'penggajians';

    protected $fillable = [
        'karyawan_id',
        'periode',
        'jumlah_hari_kerja',
        'total_gaji',
        'status_bayar',
        'tanggal_bayar',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'total_gaji' => 'double',
        'jumlah_hari_kerja' => 'integer',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    // Methods from Class Diagram
    public function hitungGaji(): float
    {
        if ($this->karyawan) {
            $gaji = $this->karyawan->hitungGaji();
            $this->total_gaji = $gaji;
            $this->save();
            return (float) $gaji;
        }
        return 0.0;
    }

    public function prosesGaji()
    {
        $this->status_bayar = 'dibayar';
        $this->tanggal_bayar = now()->toDateString();
        $this->save();

        // Register to Budget realization
        $budget = Budget::where('tanggal', now()->toDateString())->first();
        if ($budget) {
            $budget->addRealisasi($this->total_gaji);
        } else {
            Budget::create([
                'tanggal' => now()->toDateString(),
                'jenis_budget' => 'Gaji Karyawan',
                'total_budget' => $this->total_gaji * 1.2,
                'total_realisasi' => $this->total_gaji,
                'sisa' => ($this->total_gaji * 1.2) - $this->total_gaji
            ]);
        }
    }
}
