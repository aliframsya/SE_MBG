@extends('adminlte::page')

@section('title', 'Simulasi Use Case Diagram (Admin)')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold">Simulasi Use Case - Admin</h1>
                <p class="text-muted">Kelola Supplier, PO, Pembayaran, Penerimaan Barang (QC), dan Stok Gudang (FIFO).</p>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                {{ session('success') }}
                <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5><i class="icon fas fa-ban"></i> Error!</h5>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="adminTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="supplier-tab" data-toggle="pill" href="#supplier" role="tab" aria-controls="supplier" aria-selected="true">
                                    <i class="fas fa-store mr-1"></i> Supplier & Status
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="po-tab" data-toggle="pill" href="#po" role="tab" aria-controls="po" aria-selected="false">
                                    <i class="fas fa-file-invoice mr-1"></i> Purchase Order (PO)
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pembayaran-tab" data-toggle="pill" href="#pembayaran" role="tab" aria-controls="pembayaran" aria-selected="false">
                                    <i class="fas fa-money-bill-wave mr-1"></i> Proses Pembayaran
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="qc-tab" data-toggle="pill" href="#qc" role="tab" aria-controls="qc" aria-selected="false">
                                    <i class="fas fa-clipboard-check mr-1"></i> Terima Barang & QC
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="fifo-tab" data-toggle="pill" href="#fifo" role="tab" aria-controls="fifo" aria-selected="false">
                                    <i class="fas fa-boxes mr-1"></i> FIFO Gudang
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="karyawan-tab" data-toggle="pill" href="#karyawan" role="tab" aria-controls="karyawan" aria-selected="false">
                                    <i class="fas fa-users-cog mr-1"></i> Tambah Karyawan (Simulasi)
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="adminTabContent">
                            
                            {{-- TAB SUPPLIER --}}
                            <div class="tab-pane fade show active" id="supplier" role="tabpanel" aria-labelledby="supplier-tab">
                                <h4 class="mb-3 font-weight-bold">Daftar Supplier</h4>
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Kode</th>
                                                <th>Nama Supplier</th>
                                                <th>Alamat</th>
                                                <th>Nomor HP</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($suppliers as $supplier)
                                                @php
                                                    $activeSuppliers = session()->get('active_suppliers', []);
                                                    $isActive = in_array($supplier->id, $activeSuppliers);
                                                @endphp
                                                <tr>
                                                    <td><code>{{ $supplier->kode }}</code></td>
                                                    <td><strong>{{ $supplier->nama }}</strong></td>
                                                    <td>{{ $supplier->alamat }}</td>
                                                    <td>{{ $supplier->kontak }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $isActive ? 'success' : 'danger' }}">
                                                            {{ $isActive ? 'Aktif' : 'Non-Aktif' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('admin-features.supplier.toggle', $supplier->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-{{ $isActive ? 'warning' : 'success' }}">
                                                                {{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }}
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- TAB PURCHASE ORDER --}}
                            <div class="tab-pane fade" id="po" role="tabpanel" aria-labelledby="po-tab">
                                <div class="row">
                                    {{-- PO Form --}}
                                    <div class="col-md-5">
                                        <div class="card card-outline card-primary p-3">
                                            <h5 class="font-weight-bold mb-3">Buat Purchase Order</h5>
                                            <form action="{{ route('admin-features.po.store') }}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <label>Supplier</label>
                                                    <select name="supplier_id" class="form-control" required>
                                                        <option value="">-- Pilih Supplier --</option>
                                                        @foreach($suppliers as $sup)
                                                            <option value="{{ $sup->id }}">{{ $sup->nama }} ({{ $sup->kode }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tanggal PO</label>
                                                    <input type="date" name="tanggal_po" class="form-control" value="{{ now()->toDateString() }}" required>
                                                </div>

                                                <label class="d-block">Bahan Baku & Kuantitas</label>
                                                <div id="po-items-container">
                                                    <div class="row mb-2 po-item-row">
                                                        <div class="col-7">
                                                            <select name="items[0][bahan_baku_id]" class="form-control form-control-sm" required>
                                                                <option value="">-- Bahan Baku --</option>
                                                                @foreach($bahanBakus as $b)
                                                                    <option value="{{ $b->id }}">{{ $b->nama }} - Rp {{ number_format($b->harga, 0, ',', '.') }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="number" step="0.1" name="items[0][kuantitas]" class="form-control form-control-sm" placeholder="Qty" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-xs btn-outline-primary mb-3" onclick="addPoItemField()">
                                                    <i class="fas fa-plus mr-1"></i> Tambah Bahan
                                                </button>

                                                <button type="submit" class="btn btn-primary btn-block">Buat PO & Hitung Total Harga</button>
                                            </form>
                                        </div>
                                    </div>

                                    {{-- PO List --}}
                                    <div class="col-md-7">
                                        <h5 class="font-weight-bold mb-3">Daftar Purchase Order</h5>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered table-sm text-center">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Kode PO</th>
                                                        <th>Supplier</th>
                                                        <th>Total Harga</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($purchaseOrders as $po)
                                                        <tr>
                                                            <td><code>{{ $po->kode_po }}</code></td>
                                                            <td>{{ $po->supplier->nama }}</td>
                                                            <td class="text-right">Rp {{ number_format($po->total_harga, 0, ',', '.') }}</td>
                                                            <td>
                                                                @php
                                                                    $statusBadge = [
                                                                        'draft' => 'secondary',
                                                                        'dikirim' => 'primary',
                                                                        'selesai' => 'success',
                                                                        'dibatalkan' => 'danger',
                                                                    ];
                                                                @endphp
                                                                <span class="badge badge-{{ $statusBadge[$po->status] ?? 'info' }}">{{ strtoupper($po->status) }}</span>
                                                            </td>
                                                            <td>
                                                                @if($po->status == 'draft')
                                                                    <form action="{{ route('admin-features.po.confirm', $po->id) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-xs btn-success" title="Konfirmasi / Kirim PO">Kirim</button>
                                                                    </form>
                                                                    <form action="{{ route('admin-features.po.cancel', $po->id) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-xs btn-danger" title="Batalkan PO">Batal</button>
                                                                    </form>
                                                                @else
                                                                    <span class="text-muted small">No action</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-muted py-3">Belum ada PO dibuat.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- TAB PROSES PEMBAYARAN --}}
                            <div class="tab-pane fade" id="pembayaran" role="tabpanel" aria-labelledby="pembayaran-tab">
                                <h4 class="mb-3 font-weight-bold">Proses Pembayaran PO</h4>
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered text-center">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Kode PO</th>
                                                <th>Total Harga</th>
                                                <th>Status Bayar</th>
                                                <th>Action / Form</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($purchaseOrders->where('status', 'dikirim') as $po)
                                                <tr>
                                                    <td><code>{{ $po->kode_po }}</code></td>
                                                    <td class="text-right">Rp {{ number_format($po->total_harga, 0, ',', '.') }}</td>
                                                    <td>
                                                        @if($po->pembayaran)
                                                            <span class="badge badge-{{ $po->pembayaran->status_bayar == 'lunas' ? 'success' : 'warning' }}">
                                                                {{ strtoupper($po->pembayaran->status_bayar) }}
                                                            </span>
                                                        @else
                                                            <span class="badge badge-danger">BELUM DIBAYAR</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!$po->pembayaran)
                                                            <form action="{{ route('admin-features.po.pembayaran', $po->id) }}" method="POST" enctype="multipart/form-data" class="form-inline justify-content-center">
                                                                @csrf
                                                                <input type="date" name="tanggal_bayar" class="form-control form-control-sm mr-2" value="{{ now()->toDateString() }}" required>
                                                                <select name="metode_bayar" class="form-control form-control-sm mr-2" required>
                                                                    <option value="Transfer Bank">Transfer Bank</option>
                                                                    <option value="Tunai">Tunai</option>
                                                                </select>
                                                                <div class="custom-file custom-file-sm mr-2" style="width: auto;">
                                                                    <input type="file" name="bukti_transfer" class="custom-file-input" id="bukti-{{ $po->id }}">
                                                                    <label class="custom-file-label custom-file-label-sm" for="bukti-{{ $po->id }}">Bukti</label>
                                                                </div>
                                                                <button type="submit" class="btn btn-sm btn-primary">Proses Bayar</button>
                                                            </form>
                                                        @elseif($po->pembayaran->status_bayar == 'pending')
                                                            <form action="{{ route('admin-features.pembayaran.konfirmasi', $po->pembayaran->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-success">Konfirmasi Lunas</button>
                                                            </form>
                                                        @else
                                                            <span class="text-success"><i class="fas fa-check-circle"></i> Pembayaran Selesai</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- TAB TERIMA BARANG (QC) --}}
                            <div class="tab-pane fade" id="qc" role="tabpanel" aria-labelledby="qc-tab">
                                <h4 class="mb-3 font-weight-bold">Penerimaan & Cek Kualitas Bahan (QC)</h4>
                                <p class="text-muted">Hanya menampilkan PO yang statusnya <code>DIKIRIM</code>.</p>
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered text-center">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Kode PO</th>
                                                <th>Supplier</th>
                                                <th>Status Penerimaan</th>
                                                <th>Penerimaan Form (Terima Bahan & Cek Kualitas)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($purchaseOrders->where('status', 'dikirim') as $po)
                                                <tr>
                                                    <td><code>{{ $po->kode_po }}</code></td>
                                                    <td>{{ $po->supplier->nama }}</td>
                                                    <td>
                                                        @if($po->penerimaan)
                                                            <span class="badge badge-success">DITERIMA</span>
                                                            <div class="small mt-1 text-muted">QC: {{ $po->penerimaan->cekKualitas() }}</div>
                                                            <div class="small text-danger">Rijek: {{ $po->penerimaan->kuantitas_rijek }} unit</div>
                                                        @else
                                                            <span class="badge badge-warning">MENUNGGU KIRIMAN</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!$po->penerimaan)
                                                            <form action="{{ route('admin-features.po.terima', $po->id) }}" method="POST" class="form-inline justify-content-center">
                                                                @csrf
                                                                <input type="date" name="tanggal_terima" class="form-control form-control-sm mr-2" value="{{ now()->toDateString() }}" required>
                                                                <select name="kondisi_bahan" class="form-control form-control-sm mr-2" required>
                                                                    <option value="Baik">Kondisi Baik (Lolos QC)</option>
                                                                    <option value="Rusak">Kondisi Rusak (Gagal QC)</option>
                                                                </select>
                                                                <input type="number" step="0.1" name="kuantitas_rijek" class="form-control form-control-sm mr-2" placeholder="Qty Rijek" value="0" required style="width: 100px;">
                                                                <button type="submit" class="btn btn-sm btn-success">Proses Penerimaan</button>
                                                            </form>
                                                        @else
                                                            <span class="text-success"><i class="fas fa-check-circle"></i> Selesai Diterima</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- TAB FIFO GUDANG --}}
                            <div class="tab-pane fade" id="fifo" role="tabpanel" aria-labelledby="fifo-tab">
                                <div class="row">
                                    {{-- FIFO release form --}}
                                    <div class="col-md-5">
                                        <div class="card card-outline card-warning p-3 mb-3">
                                            <h5 class="font-weight-bold mb-3">Keluarkan Stok (FIFO)</h5>
                                            <form action="{{ route('admin-features.fifo.keluar') }}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <label>Bahan Baku</label>
                                                    <select name="bahan_baku_id" class="form-control" required>
                                                        <option value="">-- Pilih Bahan Baku --</option>
                                                        @foreach($bahanBakus as $b)
                                                            @php
                                                                $fifoStock = \App\Models\StokGudang::cekStokTersedia($b->id);
                                                            @endphp
                                                            <option value="{{ $b->id }}">{{ $b->nama }} (Stok FIFO: {{ $fifoStock }} unit)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Jumlah yang dikeluarkan</label>
                                                    <input type="number" step="0.1" name="jumlah" class="form-control" placeholder="Qty" required>
                                                </div>
                                                <button type="submit" class="btn btn-warning btn-block text-dark font-weight-bold">
                                                    Keluarkan Stok (FIFO)
                                                </button>
                                            </form>
                                        </div>

                                        <div class="card card-outline card-info p-3">
                                            <h5 class="font-weight-bold mb-3">Rekonsiliasi Stok FIFO</h5>
                                            <form action="{{ route('admin-features.fifo.rekonsiliasi') }}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <label>Pilih Bahan Baku</label>
                                                    <select name="bahan_baku_id" class="form-control" required>
                                                        <option value="">-- Pilih Bahan Baku --</option>
                                                        @foreach($bahanBakus as $b)
                                                            <option value="{{ $b->id }}">{{ $b->nama }} (Stok Master: {{ $b->qty }} unit)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-info btn-block font-weight-bold">
                                                    Jalankan Rekonsiliasi FIFO
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    {{-- Lots listing --}}
                                    <div class="col-md-7">
                                        <h5 class="font-weight-bold mb-3">Stok Gudang (FIFO Lots)</h5>
                                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                            <table class="table table-hover table-bordered table-sm text-center">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Bahan Baku</th>
                                                        <th>Tanggal Masuk</th>
                                                        <th>Kuantitas Lot</th>
                                                        <th>Lokasi Gudang</th>
                                                        <th>Metode</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($stokGudangs as $lot)
                                                        <tr>
                                                            <td><strong>{{ $lot->bahanBaku->nama ?? '-' }}</strong></td>
                                                            <td>{{ $lot->tanggal_masuk->format('Y-m-d') }}</td>
                                                            <td><span class="badge badge-info">{{ $lot->kuantitas }} unit</span></td>
                                                            <td>{{ $lot->lokasi_gudang }}</td>
                                                            <td><code>{{ $lot->metode_fifo }}</code></td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-muted py-3">Belum ada lot stok di gudang. Silakan terima kiriman barang PO.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- TAB TAMBAH KARYAWAN --}}
                            <div class="tab-pane fade" id="karyawan" role="tabpanel" aria-labelledby="karyawan-tab">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="card card-outline card-success p-3">
                                            <h5 class="font-weight-bold mb-3">Tambah Akun Karyawan Baru</h5>
                                            <form action="{{ route('admin-features.karyawan.store') }}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <label>ID</label>
                                                    <input type="text" name="nik" class="form-control" placeholder="Contoh: 123456" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Nama Lengkap</label>
                                                    <input type="text" name="nama" class="form-control" placeholder="Nama Karyawan" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Jabatan</label>
                                                    <select name="jabatan" class="form-control" required onchange="toggleAhliGiziFields(this)">
                                                        <option value="Karyawan Biasa">Karyawan Biasa</option>
                                                        <option value="Ahli Gizi">Ahli Gizi</option>
                                                    </select>
                                                </div>
                                                <div class="form-group" id="str-group" style="display: none;">
                                                    <label>Nomor STR (Khusus Ahli Gizi)</label>
                                                    <input type="text" name="nomor_str" class="form-control" placeholder="STR123456">
                                                </div>
                                                <div class="form-group">
                                                    <label>Divisi</label>
                                                    <input type="text" name="divisi" class="form-control" placeholder="Dapur / Gizi / Kebersihan" value="Gizi">
                                                </div>
                                                <div class="form-group">
                                                    <label>Gaji Per Periode (Base Salary)</label>
                                                    <input type="number" name="gaji_per_periode" class="form-control" placeholder="Gaji Bulanan" value="5000000" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Password</label>
                                                    <input type="password" name="password" class="form-control" placeholder="Password" value="password" required>
                                                </div>
                                                <button type="submit" class="btn btn-success btn-block">Simpan Karyawan</button>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="col-md-7">
                                        <h5 class="font-weight-bold mb-3">Daftar Akun Karyawan Aktif</h5>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered table-sm text-center">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Kode</th>
                                                        <th>ID</th>
                                                        <th>Nama</th>
                                                        <th>Jabatan / STR</th>
                                                        <th>Gaji Periode</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($karyawans as $k)
                                                        <tr>
                                                            <td><code>{{ $k->kode }}</code></td>
                                                            <td><code>{{ $k->nik }}</code></td>
                                                            <td><strong>{{ $k->nama }}</strong></td>
                                                            <td>
                                                                {{ $k->jabatan }}
                                                                @if($k->nomor_str)
                                                                    <br><span class="badge badge-info">STR: {{ $k->nomor_str }}</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-right">Rp {{ number_format($k->gaji_per_periode, 0, ',', '.') }}</td>
                                                            <td>
                                                                <span class="badge badge-success">{{ strtoupper($k->status) }}</span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="6" class="text-muted py-3">Belum ada karyawan.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        let poItemIndex = 1;
        function addPoItemField() {
            const container = document.getElementById('po-items-container');
            const row = document.createElement('div');
            row.className = 'row mb-2 po-item-row';
            row.innerHTML = `
                <div class="col-7">
                    <select name="items[${poItemIndex}][bahan_baku_id]" class="form-control form-control-sm" required>
                        <option value="">-- Bahan Baku --</option>
                        @foreach($bahanBakus as $b)
                            <option value="{{ $b->id }}">{{ $b->nama }} - Rp {{ number_format($b->harga, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-5">
                    <input type="number" step="0.1" name="items[${poItemIndex}][kuantitas]" class="form-control form-control-sm" placeholder="Qty" required>
                </div>
            `;
            container.appendChild(row);
            poItemIndex++;
        }

        function toggleAhliGiziFields(select) {
            const strGroup = document.getElementById('str-group');
            if (select.value === 'Ahli Gizi') {
                strGroup.style.display = 'block';
            } else {
                strGroup.style.display = 'none';
            }
        }
    </script>
@stop
