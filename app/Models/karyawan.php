<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Karyawan extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'kode',
        'nik',
        'nama',
        'jabatan',
        'kitchen_kode',
        'no_hp',
        'alamat',
        'tanggal_masuk',
        'status',
        'foto',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_masuk' => 'date',
            'password' => 'hashed',
        ];
    }

    public function kitchen()
    {
        return $this->belongsTo(Kitchen::class, 'kitchen_kode', 'kode');
    }
}