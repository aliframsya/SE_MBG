@php

$pendingCount =
\App\Models\Submission::where(
'status',
'diajukan'
)->count();

$rejectedCount =
\App\Models\Submission::where(
'status',
'ditolak'
)->count();

$lowStockCount =
\App\Models\BahanBaku::whereColumn(
'stok',
'<=',
'stok_minimal'
)->count();

$totalNotif =
$pendingCount +
$rejectedCount +
$lowStockCount;

@endphp
<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50 shadow-sm">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <button class="p-2 mr-3 rounded-lg text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                </button>

                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard.index') }}" class="flex items-center gap-2">
                        <x-application-logo class="block h-8 w-auto fill-current text-indigo-600" />
                        <span class="font-bold text-lg tracking-tight text-gray-800 dark:text-gray-200 hidden md:block">ADMIN<span class="font-light text-indigo-500">LTE</span></span>
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                
                <div class="relative border-r border-gray-200 dark:border-gray-700 pr-4">

    <button
        onclick="document.getElementById('notifDropdown').classList.toggle('hidden')"
        class="relative p-2 text-gray-500 hover:text-indigo-600 transition-colors">

        <svg class="w-5 h-5"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
            </path>
        </svg>

        @if($totalNotif > 0)
            <span
                class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full px-2 py-0.5">
                {{ $totalNotif }}
            </span>
        @endif

    </button>

    <div id="notifDropdown"
         class="hidden absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 shadow-lg rounded-lg z-50">

        <div class="p-3 border-b font-bold">
            Notifikasi Sistem
        </div>

        <div class="max-h-80 overflow-y-auto">

            @if($pendingCount > 0)
                <div class="p-3 border-b text-yellow-600">
                    🟡 {{ $pendingCount }}
                    Pengajuan menunggu approval
                </div>
            @endif

            @if($rejectedCount > 0)
                <div class="p-3 border-b text-red-600">
                    🔴 {{ $rejectedCount }}
                    Pengajuan ditolak
                </div>
            @endif

            @if($lowStockCount > 0)
                <div class="p-3 border-b text-orange-600">
                    📦 {{ $lowStockCount }}
                    Stok hampir habis
                </div>
            @endif


            @if($totalNotif == 0)
                <div class="p-4 text-green-600">
                    Tidak ada notifikasi
                </div>
            @endif

        </div>
    </div>

</div>
    </nav>