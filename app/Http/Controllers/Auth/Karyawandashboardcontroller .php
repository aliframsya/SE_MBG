<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class KaryawanDashboardController extends Controller
{
    /**
     * Halaman dashboard karyawan (profil ringkas).
     */
    public function index(): View
    {
        $karyawan = Auth::guard('karyawan')->user()->load('kitchen');

        return view('karyawan.dashboard', compact('karyawan'));
    }

    /**
     * Karyawan ganti password sendiri.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $karyawan = Auth::guard('karyawan')->user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if (! Hash::check($request->input('current_password'), $karyawan->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama tidak sesuai.',
            ])->withInput();
        }

        $karyawan->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}