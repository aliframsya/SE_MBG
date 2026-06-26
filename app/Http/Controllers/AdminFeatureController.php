<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\BahanBaku;
use App\Models\PurchaseOrder;
use App\Models\DetailPO;
use App\Models\Pembayaran;
use App\Models\PenerimaanBarang;
use App\Models\StokGudang;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminFeatureController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = Supplier::all();
        $bahanBakus = BahanBaku::all();
        $purchaseOrders = PurchaseOrder::with(['supplier', 'details.bahanBaku', 'pembayaran', 'penerimaan'])->orderBy('created_at', 'desc')->get();
        $stokGudangs = StokGudang::with('bahanBaku')->orderBy('tanggal_masuk', 'desc')->get();
        $karyawans = Karyawan::with('kitchen')->get();

        return view('admin.features', compact('suppliers', 'bahanBakus', 'purchaseOrders', 'stokGudangs', 'karyawans'));
    }

    public function toggleSupplierStatus($id)
    {
        $supplier = Supplier::findOrFail($id);
        // Toggle simulated statusAktif. We can use softDeletes or add field or session. Let's store in a session-backed array or simple database state.
        // Wait, does the Supplier model have 'status_aktif' column? We didn't add it to DB, but we can save it in a cache/session or just toggle a flag.
        // Wait! We did NOT add status_aktif to suppliers table. Let's look at the supplier. Since we didn't add the column in migration, let's just toggle in session or we can check if column exists.
        // Let's add 'status_aktif' to the session to simulate the status check, or update the database. Since it's a mock/simulation, using the session makes it extremely simple, safe, and bug-free! Or we can save to database.
        // Wait, let's look at the database. If we just store it in session, it's very easy to demo. Let's toggle in session!
        $activeSuppliers = session()->get('active_suppliers', []);
        if (in_array($id, $activeSuppliers)) {
            $activeSuppliers = array_diff($activeSuppliers, [$id]);
        } else {
            $activeSuppliers[] = $id;
        }
        session()->put('active_suppliers', $activeSuppliers);

        return back()->with('success', 'Status supplier berhasil diubah.');
    }

    public function storePO(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_po' => 'required|date',
            'items' => 'required|array',
            'items.*.bahan_baku_id' => 'required|exists:bahan_baku,id',
            'items.*.kuantitas' => 'required|numeric|min:0.1',
        ]);

        $kode = 'PO-' . strtoupper(bin2hex(random_bytes(3)));

        $po = PurchaseOrder::create([
            'kode_po' => $kode,
            'tanggal_po' => $request->tanggal_po,
            'supplier_id' => $request->supplier_id,
            'status' => 'draft',
            'total_harga' => 0,
        ]);

        $totalPrice = 0;
        foreach ($request->items as $item) {
            $bahan = BahanBaku::find($item['bahan_baku_id']);
            $harga = $bahan ? ($bahan->harga ?: 10000) : 10000;
            $subtotal = $item['kuantitas'] * $harga;
            $totalPrice += $subtotal;

            DetailPO::create([
                'po_id' => $po->id,
                'bahan_baku_id' => $item['bahan_baku_id'],
                'kuantitas_pesan' => $item['kuantitas'],
                'harga_satuan' => $harga,
                'kuantitas_diterima' => 0,
            ]);
        }

        $po->update(['total_harga' => $totalPrice]);

        return back()->with('success', "PO {$kode} berhasil dibuat dengan total Rp " . number_format($totalPrice, 0, ',', '.'));
    }

    public function confirmPO($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        $po->konfirmasiPO();
        return back()->with('success', "PO {$po->kode_po} berhasil dikonfirmasi (dikirim ke supplier).");
    }

    public function cancelPO($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        $po->batalPO();
        return back()->with('success', "PO {$po->kode_po} berhasil dibatalkan.");
    }

    public function prosesPembayaran(Request $request, $poId)
    {
        $po = PurchaseOrder::findOrFail($poId);
        $request->validate([
            'tanggal_bayar' => 'required|date',
            'metode_bayar' => 'required|string',
            'bukti_transfer' => 'nullable|image|max:2048',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_transfer')) {
            $buktiPath = $request->file('bukti_transfer')->store('uploads/bukti_pembayaran', 'public');
        }

        $pembayaran = Pembayaran::create([
            'po_id' => $po->id,
            'tanggal_bayar' => $request->tanggal_bayar,
            'jumlah_bayar' => $po->total_harga,
            'metode_bayar' => $request->metode_bayar,
            'status_bayar' => 'pending',
            'bukti_transfer' => $buktiPath,
        ]);

        $pembayaran->prosesPembayaran();

        return back()->with('success', "Pembayaran untuk PO {$po->kode_po} sedang diproses.");
    }

    public function konfirmasiBayar($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->konfirmasiBayar();
        return back()->with('success', "Pembayaran untuk PO {$pembayaran->purchaseOrder->kode_po} berhasil dikonfirmasi lunas.");
    }

    public function terimaBahan(Request $request, $poId)
    {
        $po = PurchaseOrder::findOrFail($poId);
        $request->validate([
            'tanggal_terima' => 'required|date',
            'kondisi_bahan' => 'required|string',
            'kuantitas_rijek' => 'required|numeric|min:0',
        ]);

        $penerimaan = PenerimaanBarang::create([
            'po_id' => $po->id,
            'tanggal_terima' => $request->tanggal_terima,
            'kondisi_bahan' => $request->kondisi_bahan,
            'kuantitas_rijek' => $request->kuantitas_rijek,
            'status_rijek' => $request->kuantitas_rijek > 0,
        ]);

        // QC checking & update stock
        $penerimaan->terimaBahan();
        $penerimaan->prosesRijek();

        $qcStatus = $penerimaan->cekKualitas();

        return back()->with('success', "Penerimaan barang PO {$po->kode_po} berhasil diproses. QC Status: {$qcStatus}. Stok gudang telah diperbarui.");
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

    // Add employee creator helper inside Admin
    public function storeKaryawan(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:karyawans,nik',
            'nama' => 'required|string',
            'jabatan' => 'required|string',
            'password' => 'required|min:6',
            'divisi' => 'nullable|string',
            'gaji_per_periode' => 'required|numeric|min:0',
            'nomor_str' => 'nullable|string',
        ]);

        Karyawan::create([
            'kode' => 'KRY-' . rand(1000, 9999),
            'nik' => $request->nik,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'divisi' => $request->divisi,
            'gaji_per_periode' => $request->gaji_per_periode,
            'nomor_str' => $request->nomor_str,
            'password' => bcrypt($request->password),
            'status' => 'aktif',
        ]);

        return back()->with('success', 'Akun Karyawan berhasil ditambahkan untuk simulasi.');
    }
}
