<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class KaryawanAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('karyawan')->check()) {
            return redirect()->route('karyawan.login');
        }

        $karyawan = Auth::guard('karyawan')->user();

        // Jaga-jaga kalau karyawan dinonaktifkan admin saat sesi masih aktif
        if ($karyawan->status !== 'aktif') {
            Auth::guard('karyawan')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('karyawan.login')->withErrors([
                'nik' => 'Akun Anda sudah tidak aktif. Hubungi admin.',
            ]);
        }

        return $next($request);
    }
}