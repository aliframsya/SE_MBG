<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyawan Portal - dapoerMBG</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-grad: linear-gradient(135deg, #FF7E40 0%, #FF5100 100%);
            --secondary-grad: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 81, 0, 0.08);
            --dark-blue: #0F172A;
            --accent-orange: #FF5100;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #FFF9F6 0%, #FFEFE6 50%, #FFF9F6 100%);
            min-height: 100vh;
            color: #334155;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Glassmorphism Sidebar */
        .portal-header {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 81, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 15px 30px;
        }

        .logo {
            font-weight: 800;
            font-size: 24px;
            color: var(--dark-blue);
            letter-spacing: -0.5px;
        }
        .logo span {
            color: var(--accent-orange);
        }

        .portal-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(255, 81, 0, 0.04);
            transition: all 0.3s ease;
            overflow: hidden;
            margin-bottom: 25px;
        }
        .portal-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(255, 81, 0, 0.08);
        }

        .card-header-gradient {
            background: var(--primary-grad);
            color: #fff;
            padding: 20px 25px;
            border: none;
        }

        /* Nav Pills Styling */
        .nav-pills .nav-link {
            color: #64748b;
            font-weight: 600;
            border-radius: 12px;
            padding: 12px 20px;
            transition: all 0.3s ease;
            margin-bottom: 8px;
        }
        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            background: var(--primary-grad);
            color: #fff;
            box-shadow: 0 8px 20px rgba(255, 81, 0, 0.25);
        }
        .nav-pills .nav-link:hover:not(.active) {
            background: rgba(255, 81, 0, 0.05);
            color: var(--accent-orange);
        }

        .btn-gradient-primary {
            background: var(--primary-grad);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px 25px;
            font-weight: 600;
            box-shadow: 0 8px 20px rgba(255, 81, 0, 0.2);
            transition: all 0.3s ease;
        }
        .btn-gradient-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(255, 81, 0, 0.3);
            color: #fff;
        }

        .btn-secondary-custom {
            background: var(--secondary-grad);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-secondary-custom:hover {
            background: #1e293b;
            color: #fff;
        }

        /* Forms Styling */
        .form-control {
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            padding: 12px 16px;
            height: auto;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: var(--accent-orange);
            box-shadow: 0 0 0 3px rgba(255, 81, 0, 0.15);
        }

        /* Badges */
        .badge-mc-valid {
            background-color: rgba(34, 197, 94, 0.1);
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, 0.2);
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 12px;
        }
        .badge-mc-expired {
            background-color: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 12px;
        }

        /* Lists and Tables */
        .table-custom {
            border-collapse: separate;
            border-spacing: 0 8px;
        }
        .table-custom tr {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.01);
            transition: all 0.2s ease;
        }
        .table-custom tr:hover {
            transform: scale(1.005);
            box-shadow: 0 8px 12px rgba(255, 81, 0, 0.03);
        }
        .table-custom th {
            border: none;
            font-weight: 600;
            color: #64748b;
        }
        .table-custom td {
            border: none;
            padding: 16px 20px;
            vertical-align: middle;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .tab-pane {
            animation: fadeIn 0.4s ease-out;
        }
    </style>
</head>
<body>

    <!-- Portal Header -->
    <header class="portal-header d-flex justify-content-between align-items-center">
        <div class="logo">
            dapoer<span>MBG</span> <small class="text-muted text-xs">| Portal Karyawan</small>
        </div>
        <div class="d-flex align-items-center" style="gap: 20px;">
            <div class="text-right d-none d-md-block">
                <h6 class="mb-0 font-weight-bold">{{ $karyawan->nama }}</h6>
                <span class="text-xs text-muted">{{ $karyawan->jabatan }} | NIK: {{ $karyawan->nik }}</span>
            </div>
            @php
                $logoutRoute = Auth::guard('karyawan')->check() ? route('karyawan.logout') : route('logout');
            @endphp
            <form action="{{ $logoutRoute }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 10px; padding: 6px 14px;">
                    <i class="fas fa-sign-out-alt mr-1"></i> Logout
                </button>
            </form>
        </div>
    </header>

    <div class="container-fluid py-4 px-4">
        
        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show portal-card border-success p-4 mb-4" role="alert">
                <h5 class="font-weight-bold text-success"><i class="fas fa-check-circle mr-2"></i>Berhasil!</h5>
                <p class="mb-0">{{ session('success') }}</p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show portal-card border-danger p-4 mb-4" role="alert">
                <h5 class="font-weight-bold text-danger"><i class="fas fa-exclamation-triangle mr-2"></i>Terjadi Kesalahan!</h5>
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <!-- Sidebar Nav -->
            <div class="col-md-3">
                <div class="portal-card p-4">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="true">
                            <i class="fas fa-user-circle mr-2"></i> Profil & MCU
                        </a>
                        <a class="nav-link" id="v-pills-fingerprint-tab" data-toggle="pill" href="#v-pills-fingerprint" role="tab" aria-controls="v-pills-fingerprint" aria-selected="false">
                            <i class="fas fa-fingerprint mr-2"></i> Absensi Fingerprint
                        </a>
                        <a class="nav-link" id="v-pills-nutrition-tab" data-toggle="pill" href="#v-pills-nutrition" role="tab" aria-controls="v-pills-nutrition" aria-selected="false">
                            <i class="fas fa-calculator mr-2"></i> Perencanaan Gizi
                        </a>
                        <a class="nav-link" id="v-pills-menu-tab" data-toggle="pill" href="#v-pills-menu" role="tab" aria-controls="v-pills-menu" aria-selected="false">
                            <i class="fas fa-utensils mr-2"></i> Pembuatan Menu
                        </a>
                        <a class="nav-link" id="v-pills-payroll-tab" data-toggle="pill" href="#v-pills-payroll" role="tab" aria-controls="v-pills-payroll" aria-selected="false">
                            <i class="fas fa-file-invoice-dollar mr-2"></i> Gaji & Budget
                        </a>
                        <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                            <i class="fas fa-key mr-2"></i> Ganti Password
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tab Contents -->
            <div class="col-md-9">
                <div class="tab-content" id="v-pills-tabContent">
                    
                    {{-- PROFILE TAB --}}
                    <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                        <div class="portal-card p-5">
                            <h3 class="font-weight-bold mb-4">Profil Karyawan</h3>
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center mb-4 mb-md-0">
                                    <div class="bg-light d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 140px; height: 140px; border: 3px solid var(--accent-orange);">
                                        <i class="fas fa-user-tie fa-4x text-muted"></i>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <th class="pl-0 text-muted" style="width: 200px;">Nama Lengkap</th>
                                            <td><strong>{{ $karyawan->nama }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0 text-muted">NIK</th>
                                            <td><code>{{ $karyawan->nik }}</code></td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0 text-muted">Jabatan</th>
                                            <td>
                                                <span class="badge badge-primary px-3 py-2" style="border-radius: 8px;">
                                                    {{ $karyawan->jabatan }}
                                                </span>
                                            </td>
                                        </tr>
                                        @if($karyawan->nomor_str)
                                            <tr>
                                                <th class="pl-0 text-muted">Nomor STR</th>
                                                <td><span class="badge badge-info px-3 py-2" style="border-radius: 8px;">{{ $karyawan->nomor_str }}</span></td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th class="pl-0 text-muted">Divisi</th>
                                            <td>{{ $karyawan->divisi ?: 'Operasional' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0 text-muted">Status Akun</th>
                                            <td><span class="text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Aktif</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <hr class="my-5" style="border-color: rgba(255, 81, 0, 0.1);">
                            
                            <h4 class="font-weight-bold mb-3">Status Medical Checkup</h4>
                            <p class="text-muted">Karyawan pangan wajib memiliki hasil MCU valid kurang dari 1 tahun untuk memastikan higienitas pengolahan makanan.</p>
                            
                            <div class="p-4 rounded-lg bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div>
                                        <div class="text-muted small">Terakhir MCU:</div>
                                        <strong class="h5 mb-0">{{ $karyawan->last_medical_checkup ? $karyawan->last_medical_checkup->format('d F Y') : 'Belum pernah MCU' }}</strong>
                                    </div>
                                    <div>
                                        @if($isMcValid)
                                            <span class="badge-mc-valid"><i class="fas fa-heartbeat mr-2"></i> FIT / MCU VALID</span>
                                        @else
                                            <span class="badge-mc-expired"><i class="fas fa-exclamation-triangle mr-2"></i> MCU KADALUARSA / EXPIRED</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="border-top pt-3">
                                    <h6 class="font-weight-bold mb-2">Kelola Data MCU:</h6>
                                    <form action="{{ route('karyawan.medicalcheckup.update') }}" method="POST" class="form-inline d-inline">
                                        @csrf
                                        <div class="form-group mr-2 mb-2">
                                            <input type="date" name="last_medical_checkup" class="form-control form-control-sm" value="{{ $karyawan->last_medical_checkup ? $karyawan->last_medical_checkup->format('Y-m-d') : '' }}" required>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-gradient-primary mb-2">Update / Simpan Tanggal</button>
                                    </form>
                                    @if($karyawan->last_medical_checkup)
                                        <form action="{{ route('karyawan.medicalcheckup.destroy') }}" method="POST" class="d-inline ml-md-2" onsubmit="return confirm('Apakah Anda yakin ingin menghapus/mereset tanggal MCU?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger mb-2">Reset / Hapus Tanggal</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FINGERPRINT TAB --}}
                    <div class="tab-pane fade" id="v-pills-fingerprint" role="tabpanel" aria-labelledby="v-pills-fingerprint-tab">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="portal-card p-4 text-center">
                                    <h4 class="font-weight-bold mb-4">Mesin Fingerprint</h4>
                                    <div class="my-4">
                                        <i class="fas fa-fingerprint fa-6x text-orange mb-3" style="color: var(--accent-orange); filter: drop-shadow(0 0 15px rgba(255, 81, 0, 0.2));"></i>
                                    </div>
                                    <p class="text-muted small mb-4">Tekan tombol di bawah untuk melakukan check-in (IN) atau check-out (OUT) kehadiran hari ini.</p>
                                    
                                    <form action="{{ route('karyawan.fingerprint') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-gradient-primary btn-block py-3 font-weight-bold h5">
                                            <i class="fas fa-fingerprint mr-2"></i> Tempel Sidik Jari
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="col-md-7">
                                <div class="portal-card p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h4 class="font-weight-bold mb-0">Rekap Absensi Terakhir</h4>
                                        <button type="button" class="btn btn-sm btn-gradient-primary" data-toggle="modal" data-target="#modalAddAbsensi">
                                            <i class="fas fa-plus mr-1"></i> Tambah Manual
                                        </button>
                                    </div>
                                    <div class="table-responsive" style="max-height: 400px;">
                                        <table class="table table-custom text-center">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Jam Masuk</th>
                                                    <th>Jam Keluar</th>
                                                    <th>Jam Kerja (HitungJamKerja)</th>
                                                    <th>Status Hadir (GetStatusHadir)</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($absensis as $a)
                                                    <tr>
                                                        <td><strong>{{ $a->tanggal->format('Y-m-d') }}</strong></td>
                                                        <td><code>{{ $a->waktu_masuk ? $a->waktu_masuk->format('H:i:s') : '-' }}</code></td>
                                                        <td><code>{{ $a->waktu_keluar ? $a->waktu_keluar->format('H:i:s') : '-' }}</code></td>
                                                        <td><span class="badge badge-info">{{ $a->hitungJamKerja() }} Jam</span></td>
                                                        <td>
                                                            <span class="badge badge-success">{{ strtoupper($a->getStatusHadir()) }}</span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex justify-content-center" style="gap: 5px;">
                                                                <button class="btn btn-xs btn-outline-info" onclick="editAbsensi({
                                                                    id: {{ $a->id }},
                                                                    tanggal: '{{ $a->tanggal->format('Y-m-d') }}',
                                                                    waktu_masuk: '{{ $a->waktu_masuk ? $a->waktu_masuk->format('H:i') : '' }}',
                                                                    waktu_keluar: '{{ $a->waktu_keluar ? $a->waktu_keluar->format('H:i') : '' }}',
                                                                    status_hadir: '{{ $a->status_hadir }}'
                                                                })"><i class="fas fa-edit"></i></button>
                                                                <form action="{{ route('karyawan.absensi.destroy', $a->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data absensi ini?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-xs btn-outline-danger"><i class="fas fa-trash"></i></button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-muted text-center py-4">Belum ada absensi tercatat.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- NUTRITION PLANNER TAB --}}
                    <div class="tab-pane fade" id="v-pills-nutrition" role="tabpanel" aria-labelledby="v-pills-nutrition-tab">
                        <div class="row">
                            <div class="col-md-5">
                                {{-- UML USECASE: Tentukan Buffer --}}
                                <div class="portal-card p-4 mb-3">
                                    <h5 class="font-weight-bold mb-3"><i class="fas fa-percent mr-2" style="color: var(--accent-orange);"></i>Tentukan Buffer (pm)</h5>
                                    <div class="form-group mb-2">
                                        <label class="small text-muted">Total Porsi (PM) untuk Hitung Buffer</label>
                                        <input type="number" id="calc_pm" class="form-control form-control-sm" placeholder="Contoh: 150" value="150">
                                    </div>
                                    <button type="button" class="btn btn-sm btn-gradient-primary btn-block" onclick="calculateBuffer()">
                                        Hitung & Set Buffer
                                    </button>
                                    <div id="calc_buffer_result" class="mt-2 small text-muted text-center" style="display:none; background: #fff8f5; padding: 10px; border-radius: 8px; border: 1px dashed var(--accent-orange);">
                                        Hasil Buffer: <strong id="calc_qty" class="text-orange">0</strong> Porsi (yaitu <strong id="calc_pct">0</strong>%)
                                    </div>
                                </div>

                                {{-- UML USECASE: Hitung Kebutuhan Harian --}}
                                <div class="portal-card p-4">
                                    <h5 class="font-weight-bold mb-3"><i class="fas fa-calculator mr-2" style="color: var(--accent-orange);"></i>Hitung Kebutuhan Harian</h5>
                                    <form action="{{ route('karyawan.nutrition.store') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label>Tanggal Rencana</label>
                                            <input type="date" name="tanggal" class="form-control" value="{{ now()->toDateString() }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Total Penerima Manfaat (PM)</label>
                                            <input type="number" name="total_pm" class="form-control" placeholder="Porsi / Orang" value="150" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Buffer Persen (%)</label>
                                            <input type="number" step="0.1" name="buffer_persen" class="form-control" placeholder="Tambahan Buffer" value="10.0" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Budget Harian (Rp)</label>
                                            <input type="number" name="budget_harian" class="form-control" placeholder="Maksimal Anggaran" value="5000000" required>
                                        </div>
                                        <button type="submit" class="btn btn-gradient-primary btn-block">Simpan & Validasi Budget</button>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="col-md-7">
                                <div class="portal-card p-4">
                                    <h4 class="font-weight-bold mb-4">History Perencanaan Kebutuhan</h4>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-sm text-center">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Total PM</th>
                                                    <th>Buffer %</th>
                                                    <th>Budget</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($nutritionNeeds as $need)
                                                    <tr>
                                                        <td>{{ $need->tanggal->format('Y-m-d') }}</td>
                                                        <td><span class="badge badge-info">{{ $need->total_pm }} PM</span></td>
                                                        <td><strong>{{ $need->buffer_persen }}%</strong></td>
                                                        <td class="text-right">Rp {{ number_format($need->budget_harian, 0, ',', '.') }}</td>
                                                        <td>
                                                            <div class="d-flex justify-content-center" style="gap: 5px;">
                                                                <button class="btn btn-xs btn-outline-info" onclick="editNutrition({
                                                                    id: {{ $need->id }},
                                                                    tanggal: '{{ $need->tanggal->format('Y-m-d') }}',
                                                                    total_pm: {{ $need->total_pm }},
                                                                    buffer_persen: {{ $need->buffer_persen }},
                                                                    budget_harian: {{ $need->budget_harian }}
                                                                })"><i class="fas fa-edit"></i></button>
                                                                <form action="{{ route('karyawan.nutrition.destroy', $need->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data perencanaan gizi ini?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-xs btn-outline-danger"><i class="fas fa-trash"></i></button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-muted py-3">Belum ada perencanaan gizi dibuat.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- MENU PLANNER TAB --}}
                    <div class="tab-pane fade" id="v-pills-menu" role="tabpanel" aria-labelledby="v-pills-menu-tab">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="portal-card p-4">
                                    <h4 class="font-weight-bold mb-4">Buat Menu & Gramasi</h4>
                                    <form action="{{ route('karyawan.menu.store') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label>Nama Menu</label>
                                            <input type="text" name="nama_menu" class="form-control" placeholder="Nasi Ayam Hainan" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Tanggal Sajian</label>
                                            <input type="date" name="tanggal" class="form-control" value="{{ now()->toDateString() }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Jenis Porsi</label>
                                            <select name="jenis_porsi" class="form-control" required>
                                                <option value="Standard">Standard (Dewasa)</option>
                                                <option value="Kecil">Kecil (Anak-anak)</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Target PM</label>
                                            <input type="number" name="total_pm" class="form-control" placeholder="Target Porsi" value="150" required>
                                        </div>

                                        <label class="d-block font-weight-bold text-sm">Setting Gramasi Bahan Baku</label>
                                        <div id="menu-items-container">
                                            <div class="row mb-2 border-bottom pb-2 menu-item-row">
                                                <div class="col-12 mb-1">
                                                    <select name="items[0][bahan_baku_id]" class="form-control form-control-sm" required>
                                                        <option value="">-- Bahan Baku --</option>
                                                        @foreach($bahanBakus as $b)
                                                            <option value="{{ $b->id }}">{{ $b->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <input type="number" step="0.01" name="items[0][gramasi_bersih]" class="form-control form-control-sm" placeholder="Bersih (gram)" required>
                                                </div>
                                                <div class="col-6">
                                                    <input type="number" step="0.01" name="items[0][gramasi_kotor]" class="form-control form-control-sm" placeholder="Kotor (gram)" required>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-xs btn-outline-primary mb-3" onclick="addMenuItemField()">
                                            <i class="fas fa-plus mr-1"></i> Tambah Bahan Baku
                                        </button>

                                        <button type="submit" class="btn btn-gradient-primary btn-block">Simpan Menu Draft</button>
                                    </form>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <h4 class="font-weight-bold mb-4">Daftar Menu Makanan</h4>
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-sm text-center">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Nama Menu</th>
                                                <th>Tanggal</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($menus as $menu)
                                                <tr>
                                                    <td><strong>{{ $menu->nama_menu }}</strong></td>
                                                    <td>{{ $menu->tanggal->format('Y-m-d') }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $menu->status == 'published' ? 'success' : 'secondary' }}">
                                                            {{ strtoupper($menu->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center" style="gap: 5px;">
                                                            @if($menu->status == 'draft')
                                                                <form action="{{ route('karyawan.menu.publish', $menu->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-xs btn-success" title="Publish Menu"><i class="fas fa-paper-plane"></i></button>
                                                                </form>
                                                            @else
                                                                <span class="badge badge-success" title="Published" style="padding: 6px;"><i class="fas fa-check-double"></i></span>
                                                            @endif
                                                            <button class="btn btn-xs btn-outline-info" onclick='editMenu({!! json_encode($menu) !!})'><i class="fas fa-edit"></i></button>
                                                            <form action="{{ route('karyawan.menu.destroy', $menu->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini beserta data gramasinya?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-xs btn-outline-danger"><i class="fas fa-trash"></i></button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-muted py-3">Belum ada menu yang dibuat.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- PAYROLL TAB --}}
                    <div class="tab-pane fade" id="v-pills-payroll" role="tabpanel" aria-labelledby="v-pills-payroll-tab">
                        <div class="row">
                            <div class="col-md-5">
                                {{-- Kalkulasi Gaji Karyawan (HitungGaji) --}}
                                <div class="portal-card p-4 mb-3">
                                    <h4 class="font-weight-bold mb-3"><i class="fas fa-wallet mr-2" style="color: var(--accent-orange);"></i>Kalkulasi Gaji Anda</h4>
                                    <p class="text-muted small">Slip rincian gaji Anda saat ini yang dihitung otomatis berdasarkan data kehadiran.</p>
                                    
                                    <div class="p-3 rounded-lg bg-light">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">Gaji Pokok:</span>
                                            <strong>Rp {{ number_format($karyawan->gaji_per_periode, 0, ',', '.') }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">Jumlah Kehadiran:</span>
                                            <strong>{{ $absensis->where('status_hadir', 'hadir')->count() }} Hari</strong>
                                        </div>
                                        <hr class="my-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted font-weight-bold">Total Diterima (HitungGaji):</span>
                                            <h5 class="font-weight-bold text-orange mb-0">Rp {{ number_format($karyawan->hitungGaji(), 0, ',', '.') }}</h5>
                                        </div>
                                    </div>
                                </div>

                                {{-- Cek Sisa Budget & CRUD Budget --}}
                                <div class="portal-card p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="font-weight-bold mb-0"><i class="fas fa-chart-pie mr-2" style="color: var(--accent-orange);"></i>Budget Koperasi</h4>
                                        <button type="button" class="btn btn-xs btn-gradient-primary" data-toggle="modal" data-target="#modalAddBudget">
                                            <i class="fas fa-plus mr-1"></i> Tambah
                                        </button>
                                    </div>
                                    <div class="p-3 rounded-lg bg-light text-center mb-3">
                                        <div class="text-muted small">Sisa Budget Terakhir:</div>
                                        @php
                                            $latestBudget = \App\Models\Budget::orderBy('tanggal', 'desc')->first();
                                            $sisa = $latestBudget ? $latestBudget->cekSisaBudget() : 0;
                                        @endphp
                                        <h3 class="font-weight-bold text-success mt-1">Rp {{ number_format($sisa, 0, ',', '.') }}</h3>
                                    </div>
                                    
                                    <div class="table-responsive" style="max-height: 250px;">
                                        <table class="table table-sm table-hover table-bordered text-center" style="font-size: 13px;">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Jenis</th>
                                                    <th>Total Budget</th>
                                                    <th>Realisasi</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($budgets as $b)
                                                    <tr>
                                                        <td>{{ $b->tanggal->format('Y-m-d') }}</td>
                                                        <td>{{ $b->jenis_budget }}</td>
                                                        <td class="text-right">Rp {{ number_format($b->total_budget, 0, ',', '.') }}</td>
                                                        <td class="text-right">Rp {{ number_format($b->total_realisasi, 0, ',', '.') }}</td>
                                                        <td>
                                                            <div class="d-flex justify-content-center" style="gap: 3px;">
                                                                <button class="btn btn-xs btn-outline-info" onclick="editBudget({
                                                                    id: {{ $b->id }},
                                                                    tanggal: '{{ $b->tanggal->format('Y-m-d') }}',
                                                                    jenis_budget: '{{ $b->jenis_budget }}',
                                                                    total_budget: {{ $b->total_budget }},
                                                                    total_realisasi: {{ $b->total_realisasi }}
                                                                })"><i class="fas fa-edit"></i></button>
                                                                <form action="{{ route('karyawan.budget.destroy', $b->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data budget ini?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-xs btn-outline-danger"><i class="fas fa-trash"></i></button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-muted text-center py-2">Belum ada budget.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="portal-card p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h4 class="font-weight-bold mb-0">Riwayat Slip Gaji</h4>
                                        <button type="button" class="btn btn-sm btn-gradient-primary" data-toggle="modal" data-target="#modalAddGaji">
                                            <i class="fas fa-plus mr-1"></i> Hitung Gaji
                                        </button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-sm text-center">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Periode</th>
                                                    <th>Hari Kerja</th>
                                                    <th>Total Gaji</th>
                                                    <th>Status Bayar</th>
                                                    <th>Tanggal Bayar</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($penggajians as $p)
                                                    <tr>
                                                        <td><code>{{ $p->periode }}</code></td>
                                                        <td>{{ $p->jumlah_hari_kerja }} Hari</td>
                                                        <td class="text-right"><strong>Rp {{ number_format($p->total_gaji, 0, ',', '.') }}</strong></td>
                                                        <td>
                                                            <span class="badge badge-{{ $p->status_bayar == 'dibayar' ? 'success' : 'warning' }}">{{ strtoupper($p->status_bayar) }}</span>
                                                        </td>
                                                        <td>{{ $p->tanggal_bayar ? $p->tanggal_bayar->format('Y-m-d') : '-' }}</td>
                                                        <td>
                                                            <div class="d-flex justify-content-center" style="gap: 5px;">
                                                                <button class="btn btn-xs btn-outline-info" onclick="editGaji({
                                                                    id: {{ $p->id }},
                                                                    periode: '{{ $p->periode }}',
                                                                    jumlah_hari_kerja: {{ $p->jumlah_hari_kerja }},
                                                                    total_gaji: {{ $p->total_gaji }},
                                                                    status_bayar: '{{ $p->status_bayar }}',
                                                                    tanggal_bayar: '{{ $p->tanggal_bayar ? $p->tanggal_bayar->format('Y-m-d') : '' }}'
                                                                })"><i class="fas fa-edit"></i></button>
                                                                <form action="{{ route('karyawan.payroll.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data slip gaji ini?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-xs btn-outline-danger"><i class="fas fa-trash"></i></button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-muted py-3">Belum ada slip gaji yang diproses.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SETTINGS TAB --}}
                    <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                        <div class="portal-card p-5" style="max-width: 600px; margin: 0 auto;">
                            <h3 class="font-weight-bold mb-4">Ganti Password Akun</h3>
                            <form action="{{ route('karyawan.password.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label>Password Lama</label>
                                    <input type="password" name="current_password" class="form-control" placeholder="Masukkan password lama" required>
                                </div>
                                <div class="form-group">
                                    <label>Password Baru</label>
                                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                                </div>
                                <div class="form-group">
                                    <label>Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
                                </div>
                                <button type="submit" class="btn btn-gradient-primary btn-block mt-4">Perbarui Password</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- Modals for CRUD Features -->
    <!-- 1. Add Absensi Manual -->
    <div class="modal fade" id="modalAddAbsensi" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('karyawan.absensi.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Tambah Absensi Manual</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="form-group">
                        <label>Waktu Masuk</label>
                        <input type="time" name="waktu_masuk" class="form-control" value="08:00">
                    </div>
                    <div class="form-group">
                        <label>Waktu Keluar</label>
                        <input type="time" name="waktu_keluar" class="form-control" value="17:00">
                    </div>
                    <div class="form-group">
                        <label>Status Kehadiran</label>
                        <select name="status_hadir" class="form-control" required>
                            <option value="hadir">Hadir</option>
                            <option value="absen">Absen</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-gradient-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. Edit Absensi -->
    <div class="modal fade" id="modalEditAbsensi" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formEditAbsensi" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Edit Absensi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" id="edit_absensi_tanggal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Waktu Masuk</label>
                        <input type="time" name="waktu_masuk" id="edit_absensi_waktu_masuk" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Waktu Keluar</label>
                        <input type="time" name="waktu_keluar" id="edit_absensi_waktu_keluar" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Status Kehadiran</label>
                        <select name="status_hadir" id="edit_absensi_status_hadir" class="form-control" required>
                            <option value="hadir">Hadir</option>
                            <option value="absen">Absen</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-gradient-primary">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 3. Edit Nutrition Needs -->
    <div class="modal fade" id="modalEditNutrition" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formEditNutrition" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Edit Perencanaan Gizi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tanggal Rencana</label>
                        <input type="date" name="tanggal" id="edit_nut_tanggal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Total Penerima Manfaat (PM)</label>
                        <input type="number" name="total_pm" id="edit_nut_total_pm" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Buffer Persen (%)</label>
                        <input type="number" step="0.1" name="buffer_persen" id="edit_nut_buffer_persen" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Budget Harian (Rp)</label>
                        <input type="number" name="budget_harian" id="edit_nut_budget_harian" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-gradient-primary">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 4. Edit Menu & Gramasi -->
    <div class="modal fade" id="modalEditMenu" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="formEditMenu" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Edit Menu & Gramasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Menu</label>
                                <input type="text" name="nama_menu" id="edit_menu_nama_menu" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Sajian</label>
                                <input type="date" name="tanggal" id="edit_menu_tanggal" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jenis Porsi</label>
                                <select name="jenis_porsi" id="edit_menu_jenis_porsi" class="form-control" required>
                                    <option value="Standard">Standard (Dewasa)</option>
                                    <option value="Kecil">Kecil (Anak-anak)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Target PM</label>
                                <input type="number" name="total_pm" id="edit_menu_total_pm" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <label class="d-block font-weight-bold text-sm mt-3">Setting Gramasi Bahan Baku</label>
                    <div id="edit-menu-items-container">
                        <!-- filled dynamically by js -->
                    </div>
                    <button type="button" class="btn btn-xs btn-outline-primary mt-2" onclick="addEditMenuItemField()">
                        <i class="fas fa-plus mr-1"></i> Tambah Bahan Baku
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-gradient-primary">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 5. Add Budget -->
    <div class="modal fade" id="modalAddBudget" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('karyawan.budget.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Tambah Budget Koperasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="form-group">
                        <label>Jenis Budget</label>
                        <select name="jenis_budget" class="form-control" required>
                            <option value="Harian">Harian</option>
                            <option value="Mingguan">Mingguan</option>
                            <option value="Bulanan">Bulanan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Total Budget (Rp)</label>
                        <input type="number" name="total_budget" class="form-control" placeholder="Contoh: 10000000" required>
                    </div>
                    <div class="form-group">
                        <label>Total Realisasi (Rp)</label>
                        <input type="number" name="total_realisasi" class="form-control" placeholder="Contoh: 0" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-gradient-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 6. Edit Budget -->
    <div class="modal fade" id="modalEditBudget" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formEditBudget" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Edit Budget Koperasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" id="edit_budget_tanggal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Jenis Budget</label>
                        <select name="jenis_budget" id="edit_budget_jenis_budget" class="form-control" required>
                            <option value="Harian">Harian</option>
                            <option value="Mingguan">Mingguan</option>
                            <option value="Bulanan">Bulanan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Total Budget (Rp)</label>
                        <input type="number" name="total_budget" id="edit_budget_total_budget" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Total Realisasi (Rp)</label>
                        <input type="number" name="total_realisasi" id="edit_budget_total_realisasi" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-gradient-primary">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 7. Add Gaji / Hitung Gaji Baru -->
    <div class="modal fade" id="modalAddGaji" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('karyawan.payroll.store') }}" method="POST" class="modal-content">
                @csrf
                <input type="hidden" name="karyawan_id" value="{{ $karyawan->id }}">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Hitung Gaji Periode Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Sistem akan menghitung gaji berdasarkan jumlah kehadiran dalam periode yang dipilih.</p>
                    <div class="form-group">
                        <label>Periode (YYYY-MM)</label>
                        <input type="month" name="periode" class="form-control" value="{{ now()->format('Y-m') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-gradient-primary">Proses Kalkulasi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 8. Edit Gaji -->
    <div class="modal fade" id="modalEditGaji" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formEditGaji" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Edit Rincian Slip Gaji</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Periode (YYYY-MM)</label>
                        <input type="text" name="periode" id="edit_gaji_periode" class="form-control" required placeholder="YYYY-MM">
                    </div>
                    <div class="form-group">
                        <label>Jumlah Hari Kerja</label>
                        <input type="number" name="jumlah_hari_kerja" id="edit_gaji_hari_kerja" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Total Gaji (Rp)</label>
                        <input type="number" name="total_gaji" id="edit_gaji_total_gaji" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Status Pembayaran</label>
                        <select name="status_bayar" id="edit_gaji_status_bayar" class="form-control" required>
                            <option value="belum_dibayar">Belum Dibayar</option>
                            <option value="dibayar">Dibayar</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Pembayaran</label>
                        <input type="date" name="tanggal_bayar" id="edit_gaji_tanggal_bayar" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-gradient-primary">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let menuItemIndex = 1;
        function addMenuItemField() {
            const container = document.getElementById('menu-items-container');
            const row = document.createElement('div');
            row.className = 'row mb-2 border-bottom pb-2 menu-item-row';
            row.innerHTML = `
                <div class="col-12 mb-1">
                    <select name="items[${menuItemIndex}][bahan_baku_id]" class="form-control form-control-sm" required>
                        <option value="">-- Bahan Baku --</option>
                        @foreach($bahanBakus as $b)
                            <option value="{{ $b->id }}">{{ $b->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6">
                    <input type="number" step="0.01" name="items[${menuItemIndex}][gramasi_bersih]" class="form-control form-control-sm" placeholder="Bersih (gram)" required>
                </div>
                <div class="col-6">
                    <input type="number" step="0.01" name="items[${menuItemIndex}][gramasi_kotor]" class="form-control form-control-sm" placeholder="Kotor (gram)" required>
                </div>
            `;
            container.appendChild(row);
            menuItemIndex++;
        }

        function calculateBuffer() {
            const pm = document.getElementById('calc_pm').value;
            fetch(`{{ route('karyawan.ajax.tentukan-buffer') }}?total_pm=${pm}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('calc_qty').innerText = data.buffer_quantity;
                    document.getElementById('calc_pct').innerText = data.buffer_percent;
                    document.getElementById('calc_buffer_result').style.display = 'block';
                    
                    // Update form fields automatically
                    document.querySelector('input[name="total_pm"]').value = pm;
                    document.querySelector('input[name="buffer_persen"]').value = data.buffer_percent;
                });
        }

        // CRUD Modal Populate & Action Handlers
        function editAbsensi(data) {
            document.getElementById('formEditAbsensi').action = `/karyawan/absensi/${data.id}`;
            document.getElementById('edit_absensi_tanggal').value = data.tanggal;
            document.getElementById('edit_absensi_waktu_masuk').value = data.waktu_masuk;
            document.getElementById('edit_absensi_waktu_keluar').value = data.waktu_keluar;
            document.getElementById('edit_absensi_status_hadir').value = data.status_hadir;
            $('#modalEditAbsensi').modal('show');
        }

        function editNutrition(data) {
            document.getElementById('formEditNutrition').action = `/karyawan/nutrition/${data.id}`;
            document.getElementById('edit_nut_tanggal').value = data.tanggal;
            document.getElementById('edit_nut_total_pm').value = data.total_pm;
            document.getElementById('edit_nut_buffer_persen').value = data.buffer_persen;
            document.getElementById('edit_nut_budget_harian').value = data.budget_harian;
            $('#modalEditNutrition').modal('show');
        }

        function editBudget(data) {
            document.getElementById('formEditBudget').action = `/karyawan/budget/${data.id}`;
            document.getElementById('edit_budget_tanggal').value = data.tanggal;
            document.getElementById('edit_budget_jenis_budget').value = data.jenis_budget;
            document.getElementById('edit_budget_total_budget').value = data.total_budget;
            document.getElementById('edit_budget_total_realisasi').value = data.total_realisasi;
            $('#modalEditBudget').modal('show');
        }

        function editGaji(data) {
            document.getElementById('formEditGaji').action = `/karyawan/payroll/${data.id}`;
            document.getElementById('edit_gaji_periode').value = data.periode;
            document.getElementById('edit_gaji_hari_kerja').value = data.jumlah_hari_kerja;
            document.getElementById('edit_gaji_total_gaji').value = data.total_gaji;
            document.getElementById('edit_gaji_status_bayar').value = data.status_bayar;
            document.getElementById('edit_gaji_tanggal_bayar').value = data.tanggal_bayar;
            $('#modalEditGaji').modal('show');
        }

        const bahanBakusList = {!! json_encode($bahanBakus) !!};
        let editMenuItemIndex = 0;

        function editMenu(menu) {
            document.getElementById('formEditMenu').action = `/karyawan/menu/${menu.id}`;
            document.getElementById('edit_menu_nama_menu').value = menu.nama_menu;
            document.getElementById('edit_menu_tanggal').value = menu.tanggal.substring(0, 10);
            document.getElementById('edit_menu_jenis_porsi').value = menu.jenis_porsi;
            document.getElementById('edit_menu_total_pm').value = menu.total_pm;

            const container = document.getElementById('edit-menu-items-container');
            container.innerHTML = '';
            editMenuItemIndex = 0;

            if (menu.gramasis && menu.gramasis.length > 0) {
                menu.gramasis.forEach(item => {
                    addEditMenuItemField(item.bahan_baku_id, item.gramasi_bersih, item.gramasi_kotor);
                });
            } else {
                addEditMenuItemField();
            }

            $('#modalEditMenu').modal('show');
        }

        function addEditMenuItemField(selectedId = '', bersih = '', kotor = '') {
            const container = document.getElementById('edit-menu-items-container');
            const row = document.createElement('div');
            row.className = 'row mb-2 border-bottom pb-2 edit-menu-item-row';
            
            let options = '<option value="">-- Bahan Baku --</option>';
            bahanBakusList.forEach(b => {
                const selected = b.id == selectedId ? 'selected' : '';
                options += `<option value="${b.id}" ${selected}>${b.nama}</option>`;
            });

            row.innerHTML = `
                <div class="col-12 mb-1">
                    <select name="items[${editMenuItemIndex}][bahan_baku_id]" class="form-control form-control-sm" required>
                        ${options}
                    </select>
                </div>
                <div class="col-6">
                    <input type="number" step="0.01" name="items[${editMenuItemIndex}][gramasi_bersih]" class="form-control form-control-sm" placeholder="Bersih (gram)" value="${bersih}" required>
                </div>
                <div class="col-6">
                    <input type="number" step="0.01" name="items[${editMenuItemIndex}][gramasi_kotor]" class="form-control form-control-sm" placeholder="Kotor (gram)" value="${kotor}" required>
                </div>
            `;
            container.appendChild(row);
            editMenuItemIndex++;
        }
    </script>

</body>
</html>