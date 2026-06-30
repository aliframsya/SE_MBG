<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Kitchen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolController extends Controller
{
    public function index()
    {
        $canManage = $this->canManage();
        $schools = School::with('kitchen')->paginate(10);
        $kitchens = Kitchen::all();
        return view('master.school.index', compact('schools', 'kitchens', 'canManage'));
    }

    public function store(Request $request)
    {
        if (!$this->canManage()) {
            abort(403, 'Anda tidak memiliki akses untuk menambah data.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'province' => 'nullable|string',
            'kepala_sekolah' => 'nullable|string',
            'no_telepon' => 'nullable|string',
            'jumlah_siswa' => 'nullable|integer',
            'kitchen_id' => 'nullable|exists:kitchens,id',
        ]);

        School::create([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city ?? 'Kota Bogor',
            'province' => $request->province ?? 'Jawa Barat',
            'kepala_sekolah' => $request->kepala_sekolah,
            'no_telepon' => $request->no_telepon,
            'jumlah_siswa' => $request->jumlah_siswa ?? 0,
            'kitchen_id' => $request->kitchen_id,
        ]);

        return redirect()->route('master.school.index')->with('success', 'Sekolah berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        if (!$this->canManage()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah data.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'province' => 'nullable|string',
            'kepala_sekolah' => 'nullable|string',
            'no_telepon' => 'nullable|string',
            'jumlah_siswa' => 'nullable|integer',
            'kitchen_id' => 'nullable|exists:kitchens,id',
        ]);

        $school = School::findOrFail($id);
        $school->update([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city ?? 'Kota Bogor',
            'province' => $request->province ?? 'Jawa Barat',
            'kepala_sekolah' => $request->kepala_sekolah,
            'no_telepon' => $request->no_telepon,
            'jumlah_siswa' => $request->jumlah_siswa ?? 0,
            'kitchen_id' => $request->kitchen_id,
        ]);

        return redirect()->route('master.school.index')->with('success', 'Sekolah berhasil diperbarui');
    }

    public function destroy($id)
    {
        if (!$this->canManage()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data.');
        }

        School::findOrFail($id)->delete();

        return redirect()->route('master.school.index')->with('success', 'Sekolah berhasil dihapus');
    }

    private function canManage()
    {
        $user = Auth::user();
        return $user->hasAnyRole(['superadmin']);
    }
}
