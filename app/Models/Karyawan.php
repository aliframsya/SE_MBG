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
        'divisi',
        'gaji_per_periode',
        'last_medical_checkup',
        'nomor_str',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_masuk' => 'date',
            'last_medical_checkup' => 'date',
            'password' => 'hashed',
            'gaji_per_periode' => 'double',
        ];
    }

    public function kitchen()
    {
        return $this->belongsTo(Kitchen::class, 'kitchen_kode', 'kode');
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'karyawan_id');
    }

    public function penggajians()
    {
        return $this->hasMany(Penggajian::class, 'karyawan_id');
    }

    /**
     * Hitung Gaji Karyawan.
     */
    public function hitungGaji(): float
    {
        // Simple logic: Gaji calculated proportional to attendance in the current period
        $hadirDays = $this->absensis()->where('status_hadir', 'hadir')->count();
        if ($hadirDays === 0) {
            return (float) $this->gaji_per_periode; // default fallback
        }
        // Proportional to 20 working days
        $multiplier = min(1.0, $hadirDays / 20);
        return (float) ($this->gaji_per_periode * $multiplier);
    }

    /**
     * Cek Medical Checkup.
     */
    public function cekMedicalCheckup(): bool
    {
        if (!$this->last_medical_checkup) {
            return false;
        }
        // Valid if within the last 90 days (3 months)
        return $this->last_medical_checkup->diffInDays(now()) <= 90;
    }

    /**
     * Get Rekap Absensi.
     */
    public function getRekapAbsensi()
    {
        return $this->absensis()->orderBy('tanggal', 'desc')->get();
    }

    /*
    |--------------------------------------------------------------------------
    | AhliGizi UML Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Tentukan Buffer based on PM portions (e.g. 10% default).
     */
    public function tentukanBuffer(int $pm): float
    {
        return (float) ($pm * 0.10);
    }

    /**
     * Hitung Kebutuhan Harian.
     */
    public function hitungKebutuhanHarian($tanggal, $pm_porsi_kecil, $pm_porsi_besar, $bufferPersen, $budgetHarian)
    {
        return KebutuhanHarian::create([
            'tanggal' => $tanggal,
            'total_pm' => $pm_porsi_kecil + $pm_porsi_besar,
            'pm_porsi_kecil' => $pm_porsi_kecil,
            'pm_porsi_besar' => $pm_porsi_besar,
            'buffer_persen' => $bufferPersen,
            'budget_harian' => $budgetHarian,
        ]);
    }

    /**
     * Buat Menu.
     */
    public function buatMenu($nama, $tanggal, $jenisPorsi, $totalPM)
    {
        return KaryawanMenu::create([
            'nama_menu' => $nama,
            'tanggal' => $tanggal,
            'jenis_porsi' => $jenisPorsi,
            'total_pm' => $totalPM,
            'dibuat_oleh' => $this->nama,
            'status' => 'draft',
        ]);
    }

    /**
     * Set Gramasi.
     */
    public function setGramasi($menuId, $bahanBakuId, $bersih, $kotor)
    {
        return GramasiMenu::create([
            'menu_id' => $menuId,
            'bahan_baku_id' => $bahanBakuId,
            'gramasi_bersih' => $bersih,
            'gramasi_kotor' => $kotor,
        ]);
    }
}