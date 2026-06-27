<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenerimaanBarang;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PenerimaanBarangController extends Controller
{
    public function index()
    {
        $penerimaans = PenerimaanBarang::with('purchaseOrder')->orderBy('created_at', 'desc')->get();
        // Get POs that need receiving (status = 'dikirim' and no receiving yet)
        $pendingPOs = PurchaseOrder::where('status', 'dikirim')->doesntHave('penerimaan')->get();
        
        return view('admin.penerimaan.index', compact('penerimaans', 'pendingPOs'));
    }

    public function create(Request $request)
    {
        $poId = $request->query('po_id');
        $po = null;
        if ($poId) {
            $po = PurchaseOrder::findOrFail($poId);
        }
        
        $pendingPOs = PurchaseOrder::where('status', 'dikirim')->doesntHave('penerimaan')->get();
        
        return view('admin.penerimaan.create', compact('po', 'pendingPOs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'po_id' => 'required|exists:purchase_orders,id',
            'tanggal_terima' => 'required|date',
            'kondisi_bahan' => 'required|string',
            'kuantitas_rijek' => 'required|numeric|min:0',
        ]);

        $po = PurchaseOrder::findOrFail($request->po_id);

        if ($po->penerimaan) {
            return redirect()->route('admin.penerimaan.index')->withErrors('PO ini sudah memiliki data penerimaan.');
        }

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

        return redirect()->route('admin.penerimaan.index')->with('success', "Penerimaan barang PO {$po->kode_po} berhasil diproses. QC Status: {$qcStatus}. Stok gudang telah diperbarui.");
    }

    public function show($id)
    {
        $penerimaan = PenerimaanBarang::with('purchaseOrder')->findOrFail($id);
        return view('admin.penerimaan.show', compact('penerimaan'));
    }

    public function edit($id)
    {
        $penerimaan = PenerimaanBarang::with('purchaseOrder')->findOrFail($id);
        // Generally you shouldn't edit receiving if stock is already updated, but for CRUD simulation we can allow basic edits.
        return view('admin.penerimaan.edit', compact('penerimaan'));
    }

    public function update(Request $request, $id)
    {
        $penerimaan = PenerimaanBarang::findOrFail($id);

        $request->validate([
            'tanggal_terima' => 'required|date',
            'kondisi_bahan' => 'required|string',
            // For simplicity, we disable changing rijek qty because it affects stock dynamically. 
            // In a real scenario, this would need complex reversal logic.
        ]);

        $penerimaan->update([
            'tanggal_terima' => $request->tanggal_terima,
            'kondisi_bahan' => $request->kondisi_bahan,
        ]);

        return redirect()->route('admin.penerimaan.index')->with('success', "Data penerimaan berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $penerimaan = PenerimaanBarang::findOrFail($id);
        // Note: In real app, deleting this should reverse stock. We will just delete the record for CRUD simulation.
        $penerimaan->delete();

        return redirect()->route('admin.penerimaan.index')->with('success', "Data penerimaan berhasil dihapus.");
    }
}
