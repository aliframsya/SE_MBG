<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard Karyawan - {{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}?v=3">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-orange-50/40 font-sans">

    <nav class="bg-white border-b border-orange-100 sticky top-0 z-10 shadow-sm">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 text-white flex items-center justify-center">
                    <i class="fas fa-id-badge"></i>
                </div>
                <span class="font-bold text-slate-800">Portal Karyawan</span>
            </div>

            <form method="POST" action="{{ route('karyawan.logout') }}">
                @csrf
                <button type="submit" class="text-sm font-medium text-slate-500 hover:text-orange-600 transition">
                    <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                </button>
            </form>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Halo, {{ $karyawan->nama }} 👋</h1>
            <p class="text-slate-500 text-sm mt-1">Selamat datang kembali di portal karyawan.</p>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- PROFIL -->
            <div class="md:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h2 class="text-base font-bold text-slate-800 mb-5 flex items-center gap-2">
                    <i class="fas fa-user text-orange-500"></i> Profil Saya
                </h2>

                <div class="flex items-center gap-4 mb-6">
                    @if ($karyawan->foto)
                        <img src="{{ asset('storage/' . $karyawan->foto) }}" alt="{{ $karyawan->nama }}"
                             class="w-16 h-16 rounded-full object-cover border-2 border-orange-100">
                    @else
                        <div class="w-16 h-16 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-xl font-bold">
                            {{ Str::upper(Str::substr($karyawan->nama, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="font-semibold text-slate-800">{{ $karyawan->nama }}</p>
                        <p class="text-sm text-slate-500">{{ $karyawan->jabatan }}</p>
                    </div>
                </div>

                <dl class="divide-y divide-slate-100 text-sm">
                    <div class="flex justify-between py-2.5">
                        <dt class="text-slate-500">Kode Karyawan</dt>
                        <dd class="font-medium text-slate-800">{{ $karyawan->kode }}</dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="text-slate-500">ID</dt>
                        <dd class="font-medium text-slate-800">{{ $karyawan->nik }}</dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="text-slate-500">Dapur</dt>
                        <dd class="font-medium text-slate-800">{{ $karyawan->kitchen->nama ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="text-slate-500">No. HP</dt>
                        <dd class="font-medium text-slate-800">{{ $karyawan->no_hp ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="text-slate-500">Alamat</dt>
                        <dd class="font-medium text-slate-800 text-right">{{ $karyawan->alamat ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="text-slate-500">Tanggal Masuk</dt>
                        <dd class="font-medium text-slate-800">
                            {{ $karyawan->tanggal_masuk?->translatedFormat('d F Y') ?? '-' }}
                        </dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="text-slate-500">Status</dt>
                        <dd>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold
                                {{ $karyawan->status === 'aktif' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                {{ ucfirst($karyawan->status) }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- GANTI PASSWORD -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 h-fit">
                <h2 class="text-base font-bold text-slate-800 mb-5 flex items-center gap-2">
                    <i class="fas fa-lock text-orange-500"></i> Ganti Password
                </h2>

                <form method="POST" action="{{ route('karyawan.password.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1.5">Password Lama</label>
                        <input type="password" name="current_password"
                               class="w-full text-sm px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-400">
                        @error('current_password')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1.5">Password Baru</label>
                        <input type="password" name="password"
                               class="w-full text-sm px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-400">
                        @error('password')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1.5">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation"
                               class="w-full text-sm px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-400">
                    </div>

                    <button type="submit"
                            class="w-full bg-gradient-to-r from-orange-400 to-orange-600 text-white text-sm font-semibold py-2.5 rounded-xl hover:shadow-lg transition">
                        Simpan Password
                    </button>
                </form>
            </div>
        </div>
    </main>

</body>
</html>