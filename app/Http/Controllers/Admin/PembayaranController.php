<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::with('purchaseOrder')->orderBy('created_at', 'desc')->get();
        // Get POs that need payment (status = 'dikirim' and no payment yet)
        $pendingPOs = PurchaseOrder::where('status', 'dikirim')->doesntHave('pembayaran')->get();
        
        return view('admin.pembayaran.index', compact('pembayarans', 'pendingPOs'));
    }

    public function create(Request $request)
    {
        $poId = $request->query('po_id');
        $po = null;
        if ($poId) {
            $po = PurchaseOrder::findOrFail($poId);
        }
        
        $pendingPOs = PurchaseOrder::where('status', 'dikirim')->doesntHave('pembayaran')->get();
        
        return view('admin.pembayaran.create', compact('po', 'pendingPOs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'po_id' => 'required|exists:purchase_orders,id',
            'tanggal_bayar' => 'required|date',
            'metode_bayar' => 'required|string',
            'bukti_transfer' => 'nullable|image|max:2048',
        ]);

        $po = PurchaseOrder::findOrFail($request->po_id);

        // Check if payment already exists
        if ($po->pembayaran) {
            return redirect()->route('admin.pembayaran.index')->withErrors('PO ini sudah memiliki data pembayaran.');
        }

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

        return redirect()->route('admin.pembayaran.index')->with('success', "Pembayaran untuk PO {$po->kode_po} berhasil diproses.");
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::with('purchaseOrder')->findOrFail($id);
        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    public function edit($id)
    {
        $pembayaran = Pembayaran::with('purchaseOrder')->findOrFail($id);
        if ($pembayaran->status_bayar == 'lunas') {
            return redirect()->route('admin.pembayaran.index')->withErrors('Pembayaran yang sudah lunas tidak dapat diubah.');
        }
        
        return view('admin.pembayaran.edit', compact('pembayaran'));
    }

    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        if ($pembayaran->status_bayar == 'lunas') {
            return redirect()->route('admin.pembayaran.index')->withErrors('Pembayaran yang sudah lunas tidak dapat diubah.');
        }

        $request->validate([
            'tanggal_bayar' => 'required|date',
            'metode_bayar' => 'required|string',
            'bukti_transfer' => 'nullable|image|max:2048',
        ]);

        $data = [
            'tanggal_bayar' => $request->tanggal_bayar,
            'metode_bayar' => $request->metode_bayar,
        ];

        if ($request->hasFile('bukti_transfer')) {
            $data['bukti_transfer'] = $request->file('bukti_transfer')->store('uploads/bukti_pembayaran', 'public');
        }

        $pembayaran->update($data);

        return redirect()->route('admin.pembayaran.index')->with('success', "Data pembayaran berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        if ($pembayaran->status_bayar == 'lunas') {
            return redirect()->route('admin.pembayaran.index')->withErrors('Pembayaran yang sudah lunas tidak dapat dihapus.');
        }

        $pembayaran->delete();

        return redirect()->route('admin.pembayaran.index')->with('success', "Data pembayaran berhasil dihapus.");
    }

    public function konfirmasi($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->konfirmasiBayar();
        
        return back()->with('success', "Pembayaran untuk PO {$pembayaran->purchaseOrder->kode_po} berhasil dikonfirmasi lunas.");
    }
}
