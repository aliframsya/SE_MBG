<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'province',
        'kepala_sekolah',
        'no_telepon',
        'jumlah_siswa',
        'kitchen_id',
    ];

    public function kitchen()
    {
        return $this->belongsTo(Kitchen::class, 'kitchen_id');
    }
}
