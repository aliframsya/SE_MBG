<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Kitchen;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::with('kitchen');

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kode', 'like', '%' . $request->search . '%');
        }

        $karyawans = $query->paginate(10);

        return view('master.karyawan.index', compact('karyawans'));
    }

    public function create()
    {
        $kitchens = Kitchen::all();
        return view('master.karyawan.create', compact('kitchens'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:karyawans,kode',
            'nik' => 'required|unique:karyawans,nik',
            'nama' => 'required',
            'jabatan' => 'required',
            'kitchen_kode' => 'nullable|exists:kitchens,kode',
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
            'tanggal_masuk' => 'nullable|date',
            'status' => 'required|in:aktif,nonaktif',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('karyawan', 'public');
        }

        Karyawan::create($validated);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function edit(Karyawan $karyawan)
    {
        $kitchens = Kitchen::all();
        return view('master.karyawan.edit', compact('karyawan', 'kitchens'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:karyawans,kode,' . $karyawan->id,
            'nik' => 'required|unique:karyawans,nik,' . $karyawan->id,
            'nama' => 'required',
            'jabatan' => 'required',
            'kitchen_kode' => 'nullable|exists:kitchens,kode',
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
            'tanggal_masuk' => 'nullable|date',
            'status' => 'required|in:aktif,nonaktif',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('karyawan', 'public');
        }

        $karyawan->update($validated);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diperbarui.');
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}