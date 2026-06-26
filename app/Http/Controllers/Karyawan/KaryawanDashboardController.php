<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\Budget;
use App\Models\KebutuhanHarian;
use App\Models\KaryawanMenu;
use App\Models\GramasiMenu;
use App\Models\BahanBaku;
use App\Models\Penggajian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class KaryawanDashboardController extends Controller
{
    /**
     * Helper to get the authenticated Karyawan instance from either guard.
     */
    private function getKaryawan()
    {
        if (Auth::guard('karyawan')->check()) {
            return Auth::guard('karyawan')->user();
        }

        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $karyawan = Karyawan::where('user_id', $user->id)->first();
            if (!$karyawan) {
                // Link or create a Karyawan row for this user
                $karyawan = Karyawan::where('nik', '12345')->first() 
                    ?: Karyawan::firstOrNew(['nik' => '12345']);
                $karyawan->user_id = $user->id;
                $karyawan->nama = $user->name;
                $karyawan->kode = $karyawan->kode ?: 'KRY-' . rand(1000, 9999);
                $karyawan->jabatan = $karyawan->jabatan ?: 'Karyawan Biasa';
                $karyawan->gaji_per_periode = $karyawan->gaji_per_periode ?: 5000000;
                $karyawan->status = 'aktif';
                $karyawan->save();
            }
            return $karyawan;
        }

        return null;
    }

    /**
     * Halaman dashboard karyawan.
     */
    public function index(): View
    {
        $karyawan = $this->getKaryawan();

        // 1. Get absensi recap
        $absensis = $karyawan->getRekapAbsensi();

        // 2. Check Medical Checkup
        $isMcValid = $karyawan->cekMedicalCheckup();

        // 3. Budgets
        $budgets = Budget::orderBy('tanggal', 'desc')->get();

        // 4. Daily Nutrition Needs
        $nutritionNeeds = KebutuhanHarian::orderBy('tanggal', 'desc')->get();

        // 5. Menus
        $menus = KaryawanMenu::with('gramasis.bahanBaku')->orderBy('tanggal', 'desc')->get();

        // 6. Ingredients lookup
        $bahanBakus = BahanBaku::all();

        // 7. Payroll History
        $penggajians = Penggajian::where('karyawan_id', $karyawan->id)->orderBy('periode', 'desc')->get();

        return view('karyawan.dashboard', compact(
            'karyawan', 'absensis', 'isMcValid', 'budgets', 'nutritionNeeds', 'menus', 'bahanBakus', 'penggajians'
        ));
    }

    /**
     * Karyawan ganti password sendiri.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $karyawan = $this->getKaryawan();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if (! Hash::check($request->input('current_password'), $karyawan->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama tidak sesuai.',
            ])->withInput();
        }

        $karyawan->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Update Medical Checkup date.
     */
    public function updateMedicalCheckup(Request $request): RedirectResponse
    {
        $request->validate([
            'last_medical_checkup' => 'required|date',
        ]);

        $karyawan = $this->getKaryawan();
        $karyawan->update([
            'last_medical_checkup' => $request->last_medical_checkup,
        ]);

        return back()->with('success', 'Tanggal Medical Checkup berhasil diperbarui.');
    }

    /**
     * Simulasi Fingerprint Karyawan.
     */
    public function rekamFingerprint()
    {
        $karyawan = $this->getKaryawan();
        $today = now()->toDateString();

        // Find or create attendance for today
        $absensi = Absensi::firstOrNew([
            'karyawan_id' => $karyawan->id,
            'tanggal' => $today,
        ]);

        if (!$absensi->exists) {
            $absensi->status_hadir = 'hadir';
        }

        $absensi->rekamFingerprint();

        $hours = $absensi->hitungJamKerja();
        $msg = $absensi->waktu_keluar 
            ? "Fingerprint OUT berhasil direkam. Jam Kerja hari ini: {$hours} jam." 
            : "Fingerprint IN berhasil direkam pada " . $absensi->waktu_masuk->format('H:i:s');

        return back()->with('success', $msg);
    }

    /**
     * AJAX action for Tentukan Buffer use case.
     */
    public function ajaxTentukanBuffer(Request $request)
    {
        $request->validate(['total_pm' => 'required|integer|min:1']);
        $karyawan = $this->getKaryawan();
        $bufferVal = $karyawan->tentukanBuffer($request->total_pm);
        // calculate default percentage equivalent
        $percent = ($bufferVal / $request->total_pm) * 100;
        return response()->json([
            'buffer_quantity' => $bufferVal,
            'buffer_percent' => $percent,
        ]);
    }

    /**
     * Harian Nutrition Needs Planning (KebutuhanHarian & Budget Check)
     */
    public function storeNutrition(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'total_pm' => 'required|integer|min:1',
            'buffer_persen' => 'required|numeric|min:0|max:100',
            'budget_harian' => 'required|numeric|min:0',
        ]);

        $karyawan = $this->getKaryawan();

        // Call UML method on Karyawan
        $kebutuhan = $karyawan->hitungKebutuhanHarian(
            $request->tanggal,
            $request->total_pm,
            $request->buffer_persen,
            $request->budget_harian
        );

        // Validate and adjust if budget exceeded
        $isValid = $kebutuhan->validasiBudget();
        $msg = "Perencanaan kebutuhan harian berhasil disimpan.";

        if (!$isValid) {
            $oldBuffer = $kebutuhan->buffer_persen;
            $kebutuhan->adjustJikaMelebihi();
            $msg .= " WARNING: Anggaran awal melebihi budget! Sistem otomatis menyesuaikan persentase buffer dari {$oldBuffer}% menjadi {$kebutuhan->buffer_persen}%.";
        }

        return back()->with('success', $msg);
    }

    public function updateNutrition(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'total_pm' => 'required|integer|min:1',
            'buffer_persen' => 'required|numeric|min:0|max:100',
            'budget_harian' => 'required|numeric|min:0',
        ]);

        $kebutuhan = KebutuhanHarian::findOrFail($id);
        $kebutuhan->update($request->all());

        // Validate and adjust if budget exceeded
        $isValid = $kebutuhan->validasiBudget();
        $msg = "Perencanaan kebutuhan harian berhasil diperbarui.";

        if (!$isValid) {
            $oldBuffer = $kebutuhan->buffer_persen;
            $kebutuhan->adjustJikaMelebihi();
            $msg .= " WARNING: Anggaran melebihi budget! Buffer disesuaikan menjadi {$kebutuhan->buffer_persen}%.";
        }

        return back()->with('success', $msg);
    }

    public function destroyNutrition($id)
    {
        $kebutuhan = KebutuhanHarian::findOrFail($id);
        $kebutuhan->delete();

        return back()->with('success', 'Rencana kebutuhan harian berhasil dihapus.');
    }

    /**
     * Buat Menu & Set Gramasi (GramasiMenu)
     */
    public function storeMenu(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string',
            'tanggal' => 'required|date',
            'jenis_porsi' => 'required|string',
            'total_pm' => 'required|integer|min:1',
            'items' => 'required|array|min:1',
            'items.*.bahan_baku_id' => 'required|exists:bahan_baku,id',
            'items.*.gramasi_bersih' => 'required|numeric|min:0.01',
            'items.*.gramasi_kotor' => 'required|numeric|min:0.01',
        ]);

        $karyawan = $this->getKaryawan();

        // Call UML method on Karyawan
        $menu = $karyawan->buatMenu(
            $request->nama_menu,
            $request->tanggal,
            $request->jenis_porsi,
            $request->total_pm
        );

        // Call setGramasi for each item
        foreach ($request->items as $item) {
            $karyawan->setGramasi(
                $menu->id,
                $item['bahan_baku_id'],
                $item['gramasi_bersih'],
                $item['gramasi_kotor']
            );
        }

        return back()->with('success', "Menu {$menu->nama_menu} berhasil dibuat (Draft).");
    }

    public function updateMenu(Request $request, $id)
    {
        $request->validate([
            'nama_menu' => 'required|string',
            'tanggal' => 'required|date',
            'jenis_porsi' => 'required|string',
            'total_pm' => 'required|integer|min:1',
            'items' => 'required|array|min:1',
            'items.*.bahan_baku_id' => 'required|exists:bahan_baku,id',
            'items.*.gramasi_bersih' => 'required|numeric|min:0.01',
            'items.*.gramasi_kotor' => 'required|numeric|min:0.01',
        ]);

        $menu = KaryawanMenu::findOrFail($id);
        $menu->update([
            'nama_menu' => $request->nama_menu,
            'tanggal' => $request->tanggal,
            'jenis_porsi' => $request->jenis_porsi,
            'total_pm' => $request->total_pm,
        ]);

        // Delete old grammages and create new ones
        $menu->gramasis()->delete();

        $karyawan = $this->getKaryawan();
        foreach ($request->items as $item) {
            $karyawan->setGramasi(
                $menu->id,
                $item['bahan_baku_id'],
                $item['gramasi_bersih'],
                $item['gramasi_kotor']
            );
        }

        return back()->with('success', "Menu {$menu->nama_menu} berhasil diperbarui.");
    }

    public function destroyMenu($id)
    {
        $menu = KaryawanMenu::findOrFail($id);
        $menu->delete(); // Cascades deletes to gramasis thanks to constrained onDelete('cascade')

        return back()->with('success', 'Menu berhasil dihapus.');
    }

    /**
     * Publish Menu (ValidasiMenu & PublishMenu)
     */
    public function publishMenu($id)
    {
        $menu = KaryawanMenu::findOrFail($id);

        if (!$menu->validasiMenu()) {
            return back()->withErrors(['menu' => 'Menu tidak valid! Pastikan menu memiliki bahan baku.']);
        }

        $menu->publishMenu();

        return back()->with('success', "Menu {$menu->nama_menu} berhasil dipublikasikan.");
    }

    /**
     * Proses Payroll (Penggajian)
     */
    public function prosesGaji(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'periode' => 'required|string|regex:/^\d{4}-\d{2}$/',
        ]);

        $karyawan = Karyawan::findOrFail($request->karyawan_id);

        // Check if payroll already processed for this period
        $exists = Penggajian::where('karyawan_id', $karyawan->id)
            ->where('periode', $request->periode)
            ->exists();

        if ($exists) {
            return back()->withErrors(['payroll' => "Gaji untuk karyawan {$karyawan->nama} periode {$request->periode} sudah pernah diproses."]);
        }

        // Count workdays in that month/period
        $hadirDays = Absensi::where('karyawan_id', $karyawan->id)
            ->where('tanggal', 'LIKE', "{$request->periode}%")
            ->where('status_hadir', 'hadir')
            ->count();

        $penggajian = Penggajian::create([
            'karyawan_id' => $karyawan->id,
            'periode' => $request->periode,
            'jumlah_hari_kerja' => $hadirDays ?: 20, // default to 20 if no attendance recorded
            'total_gaji' => 0,
            'status_bayar' => 'belum_dibayar',
        ]);

        // Calculate and process payment
        $penggajian->hitungGaji();
        $penggajian->prosesGaji();

        return back()->with('success', "Gaji untuk {$karyawan->nama} periode {$request->periode} berhasil diproses sebesar Rp " . number_format($penggajian->total_gaji, 0, ',', '.'));
    }

    /**
     * Delete/reset employee's medical checkup date.
     */
    public function destroyMedicalCheckup(): RedirectResponse
    {
        $karyawan = $this->getKaryawan();
        $karyawan->update(['last_medical_checkup' => null]);

        return back()->with('success', 'Tanggal Medical Checkup berhasil dihapus.');
    }

    /**
     * Store manual attendance record.
     */
    public function storeAbsensi(Request $request): RedirectResponse
    {
        $request->validate([
            'tanggal' => 'required|date',
            'waktu_masuk' => 'nullable',
            'waktu_keluar' => 'nullable',
            'status_hadir' => 'required|in:hadir,absen,izin,sakit',
        ]);

        $karyawan = $this->getKaryawan();
        $tgl = $request->tanggal;
        $masuk = $request->waktu_masuk ? "{$tgl} {$request->waktu_masuk}:00" : null;
        $keluar = $request->waktu_keluar ? "{$tgl} {$request->waktu_keluar}:00" : null;

        Absensi::create([
            'karyawan_id' => $karyawan->id,
            'tanggal' => $tgl,
            'waktu_masuk' => $masuk,
            'waktu_keluar' => $keluar,
            'status_hadir' => $request->status_hadir,
        ]);

        return back()->with('success', 'Data absensi manual berhasil ditambahkan.');
    }

    /**
     * Update attendance record.
     */
    public function updateAbsensi(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'tanggal' => 'required|date',
            'waktu_masuk' => 'nullable',
            'waktu_keluar' => 'nullable',
            'status_hadir' => 'required|in:hadir,absen,izin,sakit',
        ]);

        $absensi = Absensi::findOrFail($id);
        $tgl = $request->tanggal;
        $masuk = $request->waktu_masuk ? "{$tgl} {$request->waktu_masuk}" : null;
        if ($masuk && strlen($request->waktu_masuk) == 5) {
            $masuk .= ":00";
        }
        $keluar = $request->waktu_keluar ? "{$tgl} {$request->waktu_keluar}" : null;
        if ($keluar && strlen($request->waktu_keluar) == 5) {
            $keluar .= ":00";
        }

        $absensi->update([
            'tanggal' => $tgl,
            'waktu_masuk' => $masuk,
            'waktu_keluar' => $keluar,
            'status_hadir' => $request->status_hadir,
        ]);

        return back()->with('success', 'Data absensi berhasil diperbarui.');
    }

    /**
     * Delete attendance record.
     */
    public function destroyAbsensi($id): RedirectResponse
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();

        return back()->with('success', 'Data absensi berhasil dihapus.');
    }

    /**
     * Store new Budget.
     */
    public function storeBudget(Request $request): RedirectResponse
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_budget' => 'required|string',
            'total_budget' => 'required|numeric|min:0',
            'total_realisasi' => 'nullable|numeric|min:0',
        ]);

        $realisasi = $request->total_realisasi ?: 0;
        $sisa = $request->total_budget - $realisasi;

        Budget::create([
            'tanggal' => $request->tanggal,
            'jenis_budget' => $request->jenis_budget,
            'total_budget' => $request->total_budget,
            'total_realisasi' => $realisasi,
            'sisa' => $sisa,
        ]);

        return back()->with('success', 'Data budget berhasil ditambahkan.');
    }

    /**
     * Update Budget.
     */
    public function updateBudget(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_budget' => 'required|string',
            'total_budget' => 'required|numeric|min:0',
            'total_realisasi' => 'required|numeric|min:0',
        ]);

        $budget = Budget::findOrFail($id);
        $sisa = $request->total_budget - $request->total_realisasi;

        $budget->update([
            'tanggal' => $request->tanggal,
            'jenis_budget' => $request->jenis_budget,
            'total_budget' => $request->total_budget,
            'total_realisasi' => $request->total_realisasi,
            'sisa' => $sisa,
        ]);

        return back()->with('success', 'Data budget berhasil diperbarui.');
    }

    /**
     * Delete Budget.
     */
    public function destroyBudget($id): RedirectResponse
    {
        $budget = Budget::findOrFail($id);
        $budget->delete();

        return back()->with('success', 'Data budget berhasil dihapus.');
    }

    /**
     * Update Payroll/Penggajian record.
     */
    public function updateGaji(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'periode' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'jumlah_hari_kerja' => 'required|integer|min:0',
            'total_gaji' => 'required|numeric|min:0',
            'status_bayar' => 'required|in:belum_dibayar,dibayar',
            'tanggal_bayar' => 'nullable|date',
        ]);

        $penggajian = Penggajian::findOrFail($id);
        $penggajian->update([
            'periode' => $request->periode,
            'jumlah_hari_kerja' => $request->jumlah_hari_kerja,
            'total_gaji' => $request->total_gaji,
            'status_bayar' => $request->status_bayar,
            'tanggal_bayar' => $request->tanggal_bayar,
        ]);

        return back()->with('success', 'Data penggajian berhasil diperbarui.');
    }

    /**
     * Delete Payroll/Penggajian record.
     */
    public function destroyGaji($id): RedirectResponse
    {
        $penggajian = Penggajian::findOrFail($id);
        $penggajian->delete();

        return back()->with('success', 'Data penggajian berhasil dihapus.');
    }
}
