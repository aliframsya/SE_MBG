<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DistribusiController extends Controller
{
    public function index()
    {
        $distribusis = \App\Models\Distribusi::orderBy('tanggal', 'desc')->get();
        return view('admin.distribusi.index', compact('distribusis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_sekolah' => 'required|string',
            'nama_driver' => 'required|string',
            'jumlah_porsi_dikirim' => 'required|integer|min:1',
            'jumlah_sisa_kembali' => 'nullable|integer|min:0',
            'keterangan_dibuang' => 'nullable|string'
        ]);

        \App\Models\Distribusi::create($request->all());

        return redirect()->route('distribusi.index')->with('success', 'Data distribusi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_sekolah' => 'required|string',
            'nama_driver' => 'required|string',
            'jumlah_porsi_dikirim' => 'required|integer|min:1',
            'jumlah_sisa_kembali' => 'nullable|integer|min:0',
            'keterangan_dibuang' => 'nullable|string'
        ]);

        $distribusi = \App\Models\Distribusi::findOrFail($id);
        $distribusi->update($request->all());

        return redirect()->route('distribusi.index')->with('success', 'Data distribusi berhasil diupdate.');
    }

    public function destroy($id)
    {
        $distribusi = \App\Models\Distribusi::findOrFail($id);
        $distribusi->delete();

        return redirect()->route('distribusi.index')->with('success', 'Data distribusi berhasil dihapus.');
    }
}
