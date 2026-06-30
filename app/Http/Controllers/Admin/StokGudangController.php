<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StokGudang;
use App\Models\BahanBaku;
use App\Models\DetailPO;
use Illuminate\Http\Request;

class StokGudangController extends Controller
{
    public function index()
    {
        $stokGudangs = StokGudang::with('bahanBaku.kitchen')->orderBy('tanggal_masuk', 'desc')->get();
        // Semua bahan baku untuk dropdown rekonsiliasi
        $bahanBakus = BahanBaku::with('kitchen')->orderBy('nama')->get();
        $suppliers = \App\Models\Supplier::with('kitchens')->get();

        // Bahan baku yang punya stok FIFO > 0 (untuk dropdown keluarkan)
        $bahanBakuDenganStok = BahanBaku::with('kitchen')
            ->has('stokGudangs')
            ->withSum('stokGudangs', 'kuantitas')
            ->get()
            ->filter(fn($item) => $item->stok_gudangs_sum_kuantitas > 0)
            ->sortBy('nama');

        // Cek stok kritis per lot FIFO (kuantitas > 0 dan ≤ 50)
        $stokKritis = StokGudang::with('bahanBaku.kitchen')
            ->where('kuantitas', '>', 0)
            ->where('kuantitas', '<=', 50)
            ->orderBy('kuantitas', 'asc')
            ->get();

        return view('admin.stok.index', compact('stokGudangs', 'bahanBakus', 'suppliers', 'bahanBakuDenganStok', 'stokKritis'));
    }

    public function keluarkanFIFO(Request $request)
    {
        $request->validate([
            'bahan_baku_id' => 'required|exists:bahan_baku,id',
            'jumlah' => 'required|numeric|min:0.1',
        ]);

        $bahan = BahanBaku::findOrFail($request->bahan_baku_id);
        $available = StokGudang::cekStokTersedia($bahan->id);

        if ($request->jumlah > $available) {
            return back()->withErrors(['jumlah' => "Stok tidak cukup! Tersedia di Gudang (FIFO Lots): {$available} unit."]);
        }

        StokGudang::keluarkanFIFO($bahan->id, $request->jumlah);

        // Cek stok kritis setelah pengeluaran
        $sisaStok = StokGudang::cekStokTersedia($bahan->id);
        $message = "Berhasil mengeluarkan {$request->jumlah} unit {$bahan->nama} menggunakan metode FIFO.";

        if ($sisaStok <= 0) {
            return back()->with('success', $message)
                ->with('stok_habis', "🚨 STOK HABIS: {$bahan->nama} sudah habis (0 unit)! Segera lakukan pemesanan ulang.");
        }
        if ($sisaStok <= 50) {
            return back()->with('success', $message)
                ->with('stok_kritis', "⚠️ STOK KRITIS: {$bahan->nama} tersisa {$sisaStok} unit! Segera lakukan pemesanan ulang.");
        }

        return back()->with('success', $message);
    }

    public function rekonsiliasiFIFO(Request $request)
    {
        $request->validate([
            'bahan_baku_id' => 'required|exists:bahan_baku,id',
        ]);

        $bahan = BahanBaku::findOrFail($request->bahan_baku_id);
        StokGudang::rekonsiliasiFIFO($bahan->id);

        return back()->with('success', "Rekonsiliasi FIFO untuk bahan {$bahan->nama} berhasil dijalankan.");
    }

    // Typical CRUD if needed
    public function create()
    {
        $bahanBakus = BahanBaku::all();
        return view('admin.stok.create', compact('bahanBakus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bahan_baku_id' => 'required|exists:bahan_baku,id',
            'kuantitas' => 'required|numeric|min:0.1',
            'tanggal_masuk' => 'required|date',
            'lokasi_gudang' => 'nullable|string',
        ]);

        StokGudang::create([
            'bahan_baku_id' => $request->bahan_baku_id,
            'kuantitas' => $request->kuantitas,
            'tanggal_masuk' => $request->tanggal_masuk,
            'lokasi_gudang' => $request->lokasi_gudang ?? 'Gudang Utama',
            'metode_fifo' => 'FIFO',
        ]);

        // Sync bahan_baku.stok dan qty
        $bahan = BahanBaku::find($request->bahan_baku_id);
        if ($bahan) {
            $bahan->stok += $request->kuantitas;
            $bahan->qty += $request->kuantitas;
            $bahan->save();
        }

        return redirect()->route('admin.stok.index')->with('success', "Berhasil menambahkan {$request->kuantitas} unit ke stok gudang.");
    }

    public function show($id)
    {
        $stok = StokGudang::with('bahanBaku')->findOrFail($id);
        return view('admin.stok.show', compact('stok'));
    }

    public function edit($id)
    {
        $stok = StokGudang::findOrFail($id);
        $bahanBakus = BahanBaku::all();
        return view('admin.stok.edit', compact('stok', 'bahanBakus'));
    }

    public function update(Request $request, $id)
    {
        $stok = StokGudang::findOrFail($id);

        $request->validate([
            'bahan_baku_id' => 'required|exists:bahan_baku,id',
            'kuantitas' => 'required|numeric|min:0.1',
            'tanggal_masuk' => 'required|date',
            'lokasi_gudang' => 'nullable|string',
        ]);

        $stok->update([
            'bahan_baku_id' => $request->bahan_baku_id,
            'kuantitas' => $request->kuantitas,
            'tanggal_masuk' => $request->tanggal_masuk,
            'lokasi_gudang' => $request->lokasi_gudang,
        ]);

        return redirect()->route('admin.stok.index')->with('success', 'Data stok (lot) berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $stok = StokGudang::findOrFail($id);
        $stok->delete();

        return redirect()->route('admin.stok.index')->with('success', 'Data stok (lot) berhasil dihapus.');
    }
}
