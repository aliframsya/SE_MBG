<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class KaryawanAuthController extends Controller
{
    /**
     * Tampilkan form login karyawan.
     */
    public function create(): View
    {
        return view('karyawan.login');
    }

    /**
     * Proses login karyawan (pakai NIK + password).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nik' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $throttleKey = Str::lower($request->input('nik')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'nik' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        $karyawan = Karyawan::where('nik', $request->input('nik'))->first();

        if (! $karyawan || ! $karyawan->password || ! Hash::check($request->input('password'), $karyawan->password)) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'nik' => 'NIK atau password tidak sesuai.',
            ]);
        }

        if ($karyawan->status !== 'aktif') {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'nik' => 'Akun Anda tidak aktif. Hubungi admin.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        Auth::guard('karyawan')->login($karyawan, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->route('karyawan.dashboard');
    }

    /**
     * Logout karyawan.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('karyawan')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('karyawan.login');
    }
}