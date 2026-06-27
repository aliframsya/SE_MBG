<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StokGudang;
use App\Models\BahanBaku;
use Illuminate\Http\Request;

class StokGudangController extends Controller
{
    public function index()
    {
        $stokGudangs = StokGudang::with('bahanBaku.kitchen')->orderBy('tanggal_masuk', 'desc')->get();
        // Hanya tampilkan bahan baku yang ada di daftar stok gudang untuk mempermudah pencarian
        $bahanBakus = BahanBaku::with('kitchen')->whereIn('id', StokGudang::select('bahan_baku_id'))->get();
        
        return view('admin.stok.index', compact('stokGudangs', 'bahanBakus'));
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

        return back()->with('success', "Berhasil mengeluarkan {$request->jumlah} unit {$bahan->nama} menggunakan metode FIFO.");
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
            'lokasi_gudang' => $request->lokasi_gudang,
            'metode_fifo' => 'FIFO-' . date('Ymd', strtotime($request->tanggal_masuk)),
        ]);

        return redirect()->route('admin.stok.index')->with('success', 'Data stok (lot) berhasil ditambahkan secara manual.');
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
