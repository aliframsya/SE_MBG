<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distribusi extends Model
{
    protected $table = 'distribusis';
    
    protected $fillable = [
        'tanggal',
        'nama_sekolah',
        'nama_driver',
        'jumlah_porsi_dikirim',
        'jumlah_sisa_kembali',
        'keterangan_dibuang'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah_porsi_dikirim' => 'integer',
        'jumlah_sisa_kembali' => 'integer'
    ];
}
