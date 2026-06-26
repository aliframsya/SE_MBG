<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Absensi extends Model
{
    protected $table = 'absensis';

    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'waktu_masuk',
        'waktu_keluar',
        'status_hadir',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_masuk' => 'datetime',
        'waktu_keluar' => 'datetime',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    // Methods from Class Diagram
    public function rekamFingerprint()
    {
        if (!$this->waktu_masuk) {
            $this->waktu_masuk = now();
            $this->status_hadir = 'hadir';
        } else {
            $this->waktu_keluar = now();
        }
        $this->save();
    }

    public function hitungJamKerja(): float
    {
        if (!$this->waktu_masuk || !$this->waktu_keluar) {
            return 0.0;
        }
        return (float) round($this->waktu_masuk->diffInMinutes($this->waktu_keluar) / 60, 2);
    }

    public function getStatusHadir(): string
    {
        return $this->status_hadir;
    }
}
