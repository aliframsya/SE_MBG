<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\BahanBaku;
use App\Models\DetailPO;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['supplier', 'details.bahanBaku'])->orderBy('created_at', 'desc')->get();
        return view('admin.po.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $bahanBakus = BahanBaku::all();
        return view('admin.po.create', compact('suppliers', 'bahanBakus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_po' => 'required|date',
            'items' => 'required|array|min:1',
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

        return redirect()->route('admin.po.index')->with('success', "PO {$kode} berhasil dibuat.");
    }

    public function show($id)
    {
        $po = PurchaseOrder::with(['supplier', 'details.bahanBaku', 'pembayaran', 'penerimaan'])->findOrFail($id);
        return view('admin.po.show', compact('po'));
    }

    public function edit($id)
    {
        $po = PurchaseOrder::with('details')->findOrFail($id);
        if ($po->status !== 'draft') {
            return redirect()->route('admin.po.index')->withErrors('PO yang sudah diproses tidak dapat diubah.');
        }

        $suppliers = Supplier::all();
        $bahanBakus = BahanBaku::all();
        return view('admin.po.edit', compact('po', 'suppliers', 'bahanBakus'));
    }

    public function update(Request $request, $id)
    {
        $po = PurchaseOrder::findOrFail($id);
        if ($po->status !== 'draft') {
            return redirect()->route('admin.po.index')->withErrors('PO yang sudah diproses tidak dapat diubah.');
        }

        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_po' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.bahan_baku_id' => 'required|exists:bahan_baku,id',
            'items.*.kuantitas' => 'required|numeric|min:0.1',
        ]);

        $po->update([
            'tanggal_po' => $request->tanggal_po,
            'supplier_id' => $request->supplier_id,
        ]);

        // Recreate items
        $po->details()->delete();

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

        return redirect()->route('admin.po.index')->with('success', "PO {$po->kode_po} berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        if ($po->status !== 'draft') {
            return redirect()->route('admin.po.index')->withErrors('Hanya PO draft yang dapat dihapus.');
        }
        $po->details()->delete();
        $po->delete();

        return redirect()->route('admin.po.index')->with('success', "PO {$po->kode_po} berhasil dihapus.");
    }

    public function confirm($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        $po->konfirmasiPO();
        return back()->with('success', "PO {$po->kode_po} berhasil dikonfirmasi.");
    }

    public function cancel($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        $po->batalPO();
        return back()->with('success', "PO {$po->kode_po} berhasil dibatalkan.");
    }
}
