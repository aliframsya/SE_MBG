<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Kitchen;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Concerns\HasPerPage;

class BahanBakuController extends Controller
{
    use HasPerPage;
    // Tampilkan halaman bahan baku
    public function index(Request $request)
    {
        $user = Auth::user();

        $canManage = $this->canManage();

        // -----------------------------------------------------------
        // 1. AMBIL KITCHEN MILIK USER (LOGIC BARU)
        // -----------------------------------------------------------
        $myKitchenCodes = DB::table('kitchen_user')
            ->where('user_id', $user->id)
            ->pluck('kitchen_kode')
            ->toArray();

        $kitchens = Kitchen::whereIn('kode', $myKitchenCodes)->get();

        $kitchenIds = $kitchens->pluck('id');

        if ($request->filled('kitchen_kode')) {
            $selectedKitchen = $kitchens->where('kode', $request->kitchen_kode)->first();

            if ($selectedKitchen) {
                $kitchenIds = collect([$selectedKitchen->id]);
            } else {
                $kitchenIds = collect([]); 
            }
        }

        // -----------------------------------------------------------
        // 2. QUERY DATA BAHAN BAKU
        // -----------------------------------------------------------
        $query = BahanBaku::with(['kitchen']);

        if ($kitchenIds->isNotEmpty()) {
            $query->whereIn('kitchen_id', $kitchenIds);
        } else {
            $query->where('id', -1);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('kode', 'LIKE', "%{$search}%");
            });
        }

       $items = $query->paginate($this->resolvePerPage($request))
               ->withQueryString();

        $generatedCodes = [];
        foreach ($kitchens as $k) {
            $generatedCodes[$k->id] = $this->generateKode($k->kode);
        }

        return view('dashboard.master.bahan-baku.index', compact('items', 'kitchens', 'generatedCodes', 'canManage'));
    }

    // Generate kode bahan baku: 2 digit + kode dapur
    private function generateKode($kodeDapur)
    {
        $lastItem = BahanBaku::withTrashed()
            ->where('kode', 'LIKE', "BN{$kodeDapur}%")
            ->orderBy('kode', 'desc')
            ->first();

        if (!$lastItem) {
            return 'BN' . $kodeDapur . '111';
        }

        $lastNumber = (int) substr($lastItem->kode, -3);
        $nextNumber = $lastNumber + 1;

        if ($nextNumber > 999)
            $nextNumber = 999;

        $num = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return 'BN' . $kodeDapur . $num;
    }

    // Simpan bahan baku baru
    public function store(Request $request)
    {
        if (!$this->canManage()) {
            abort(403, 'Anda tidak memiliki akses untuk menambah data.');
        }

        $user = Auth::user();

        $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('bahan_baku')->where(function ($q) use ($request) {
                    return $q->where('kitchen_id', $request->kitchen_id);
                }),
            ],
            'harga' => 'nullable|numeric|min:0',
            'kitchen_id' => 'required|exists:kitchens,id',
            'total_stok' => 'nullable|numeric|min:0', // Menangkap input total_stok
        ]);

        $kitchen = Kitchen::findOrFail($request->kitchen_id);

        // -----------------------------------------------------------
        // VALIDASI AKSES
        // -----------------------------------------------------------
        $hasAccess = DB::table('kitchen_user')
            ->where('user_id', $user->id)
            ->where('kitchen_kode', $kitchen->kode)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke kitchen ini');
        }

        BahanBaku::create([
            'kode' => $this->generateKode($kitchen->kode),
            'nama' => $request->nama,
            'harga' => $request->input('harga', 0),
            'kitchen_id' => $request->kitchen_id,
            'qty' => $request->input('total_stok', 0), // Menyimpan input total_stok ke kolom qty
        ]);

        return redirect()->route('dashboard.master.bahan-baku.index')
            ->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        if (!$this->canManage()) {
            abort(403, 'Anda tidak memiliki akses untuk menambah data.');
        }

        $user = Auth::user();

        $item = BahanBaku::with('kitchen')->findOrFail($id);

        // -----------------------------------------------------------
        // VALIDASI AKSES DATA LAMA
        // -----------------------------------------------------------
        $hasAccessOrigin = DB::table('kitchen_user')
            ->where('user_id', $user->id)
            ->where('kitchen_kode', $item->kitchen->kode)
            ->exists();

        if (!$hasAccessOrigin) {
            abort(403, 'Anda tidak memiliki akses ke data ini');
        }

        $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('bahan_baku')->where(function ($q) use ($request) {
                    return $q->where('kitchen_id', $request->kitchen_id);
                })->ignore($item->id),
            ],
            'harga' => 'nullable|numeric|min:0',
            'kitchen_id' => 'required|exists:kitchens,id',
            'total_stok' => 'nullable|numeric|min:0', // Menangkap input total_stok saat update
        ]);

        $data = [
            'nama' => $request->nama,
            'harga' => $request->input('harga', 0),
            'kitchen_id' => $request->kitchen_id,
            'qty' => $request->input('total_stok', $item->qty), // Update qty
        ];

        // Jika pindah dapur, cek akses dapur tujuan & generate kode baru
        if ($item->kitchen_id != $request->kitchen_id) {
            $kitchen = Kitchen::findOrFail($request->kitchen_id);

            // Cek akses ke dapur TUJUAN
            $hasAccessDest = DB::table('kitchen_user')
                ->where('user_id', $user->id)
                ->where('kitchen_kode', $kitchen->kode)
                ->exists();

            if (!$hasAccessDest) {
                abort(403, 'Anda tidak memiliki akses ke kitchen tujuan');
            }

            $data['kode'] = $this->generateKode($kitchen->kode);
        }

        $item->update($data);

        return redirect()
            ->route('dashboard.master.bahan-baku.index')
            ->with('success', 'Bahan baku berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if (!$this->canManage()) {
            abort(403, 'Anda tidak memiliki akses untuk menambah data.');
        }

        $user = Auth::user();
        $item = BahanBaku::with('kitchen')->findOrFail($id);

        // Cek Validasi Hapus
        $hasAccess = DB::table('kitchen_user')
            ->where('user_id', $user->id)
            ->where('kitchen_kode', $item->kitchen->kode)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke data ini');
        }

        $item->delete();

        return redirect()->route('dashboard.master.bahan-baku.index')
            ->with('success', 'Bahan baku berhasil dihapus.');
    }
    
    // Fungsi bantuan untuk cek role
    private function canManage()
    {
        $user = Auth::user();
        // Pastikan user memiliki salah satu dari role ini
        return $user->hasAnyRole(['superadmin', 'operatorDapur','superadminDapur']);
    }
}