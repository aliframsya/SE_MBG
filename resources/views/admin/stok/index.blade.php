@extends('adminlte::page')

@section('title', 'Stok Gudang')

@section('content_header')
    <h1>Stok Gudang (FIFO)</h1>
@stop

@section('content')
    {{-- Alert Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('success') }}
        </div>
    @endif
    @if(session('stok_kritis'))
        <div class="alert alert-warning alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>{{ session('stok_kritis') }}</strong>
        </div>
    @endif
    @if(session('stok_habis'))
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>{{ session('stok_habis') }}</strong>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <ul class="mb-0">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    {{-- Peringatan Stok Kritis --}}
    @if($stokKritis->count() > 0)
        <div class="alert alert-danger">
            <h5><i class="icon fas fa-exclamation-triangle"></i> Stok Kritis!</h5>
            <p class="mb-1">Lot FIFO berikut tersisa ≤ 50 unit dan perlu segera dipesan ulang:</p>
            <ul class="mb-0">
                @foreach($stokKritis as $lot)
                    <li><strong>{{ $lot->bahanBaku->nama }}</strong> ({{ $lot->bahanBaku->kitchen->nama ?? 'Pusat' }}) — tersisa <strong>{{ (float) $lot->kuantitas }}</strong> unit</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        {{-- Form Keluarkan Bahan (tetap seperti semula) --}}
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header"><h3 class="card-title">Keluarkan Bahan (Metode FIFO)</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.stok.keluarkan') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Filter berdasarkan Supplier (Opsional)</label>
                            <select id="supplier_filter_keluarkan" class="form-control supplier-filter" data-target="bahan_baku_keluarkan">
                                <option value="">-- Semua Supplier --</option>
                                @foreach($suppliers as $sup)
                                    <option value="{{ $sup->id }}" data-kategori="{{ $sup->kategori }}" data-kitchens="{{ json_encode($sup->kitchens->pluck('id')) }}">{{ $sup->nama }} ({{ $sup->kode }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Bahan Baku</label>
                            <select name="bahan_baku_id" id="bahan_baku_keluarkan" class="form-control" required>
                                <option value="">-- Pilih Bahan --</option>
                                @foreach($bahanBakus as $b)
                                    <option value="{{ $b->id }}" data-nama="{{ $b->nama }}" data-kitchen="{{ $b->kitchen_id }}">{{ $b->nama }} ({{ $b->kitchen->nama ?? 'Pusat' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Jumlah Dikeluarkan</label>
                            <input type="number" step="0.1" name="jumlah" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Proses Pengeluaran</button>
                    </form>
                </div>
            </div>
        </div>
        {{-- Rekonsiliasi FIFO --}}
        <div class="col-md-6">
            <div class="card card-warning">
                <div class="card-header"><h3 class="card-title">Rekonsiliasi FIFO</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.stok.rekonsiliasi') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Filter berdasarkan Supplier (Opsional)</label>
                            <select id="supplier_filter_rekonsiliasi" class="form-control supplier-filter" data-target="bahan_baku_rekonsiliasi">
                                <option value="">-- Semua Supplier --</option>
                                @foreach($suppliers as $sup)
                                    <option value="{{ $sup->id }}" data-kategori="{{ $sup->kategori }}" data-kitchens="{{ json_encode($sup->kitchens->pluck('id')) }}">{{ $sup->nama }} ({{ $sup->kode }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Bahan Baku</label>
                            <select name="bahan_baku_id" id="bahan_baku_rekonsiliasi" class="form-control" required>
                                <option value="">-- Pilih Bahan --</option>
                                @foreach($bahanBakus as $b)
                                    <option value="{{ $b->id }}" data-nama="{{ $b->nama }}" data-kitchen="{{ $b->kitchen_id }}">{{ $b->nama }} ({{ $b->kitchen->nama ?? 'Pusat' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <p class="text-muted small">Fitur ini akan menghapus semua catatan stok kosong dan mengatur ulang lot stok yang bermasalah.</p>
                        <button type="submit" class="btn btn-warning">Jalankan Rekonsiliasi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Lot Stok --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Lot Stok di Gudang</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped text-center">
                <thead>
                    <tr>
                        <th>Metode FIFO (Lot)</th>
                        <th>Bahan Baku</th>
                        <th>Kuantitas Tersisa</th>
                        <th>Tanggal Masuk</th>
                        <th>Lokasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stokGudangs as $stok)
                        <tr>
                            <td>{{ $stok->metode_fifo }}</td>
                            <td>{{ $stok->bahanBaku->nama }} <br><small class="text-muted">({{ $stok->bahanBaku->kitchen->nama ?? 'Pusat' }})</small></td>
                            <td>
                                @if($stok->kuantitas <= 0)
                                    <span class="badge badge-danger">Habis ({{ $stok->kuantitas }})</span>
                                @elseif($stok->kuantitas <= 50)
                                    <span class="badge badge-warning">{{ $stok->kuantitas }} ⚠️</span>
                                @else
                                    <span class="badge badge-success">{{ $stok->kuantitas }}</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($stok->tanggal_masuk)->format('d/m/Y') }}</td>
                            <td>{{ $stok->lokasi_gudang ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5">Gudang kosong. Belum ada stok barang. Silahkan input stok dari data penerimaan di atas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter Bahan Baku berdasarkan Supplier
        document.querySelectorAll('.supplier-filter').forEach(filter => {
            filter.addEventListener('change', function() {
                const targetId = this.dataset.target;
                const targetSelect = document.getElementById(targetId);
                const selectedOption = this.options[this.selectedIndex];
                const kategoriStr = selectedOption ? selectedOption.getAttribute('data-kategori') : '';
                const allowedKategoris = kategoriStr ? kategoriStr.split(',').map(s => s.trim().toLowerCase()) : [];
                
                const kitchensStr = selectedOption ? selectedOption.getAttribute('data-kitchens') : '[]';
                let allowedKitchens = [];
                try {
                    allowedKitchens = JSON.parse(kitchensStr);
                } catch(e) {}

                const currentVal = targetSelect.value;
                let valStillValid = false;

                Array.from(targetSelect.options).forEach(opt => {
                    if (opt.value === '') return;
                    const optNama = opt.getAttribute('data-nama');
                    const optKitchen = opt.getAttribute('data-kitchen');
                    if (!optNama) return;
                    
                    const optNamaLower = optNama.trim().toLowerCase();
                    
                    let isKategoriMatch = allowedKategoris.length === 0 || allowedKategoris.includes(optNamaLower);
                    let isKitchenMatch = allowedKitchens.length === 0 || allowedKitchens.includes(parseInt(optKitchen)) || allowedKitchens.includes(optKitchen);
                    
                    if (selectedOption.value === '' || (isKategoriMatch && isKitchenMatch)) {
                        opt.style.display = '';
                        opt.hidden = false;
                        opt.disabled = false;
                        if (opt.value === currentVal) valStillValid = true;
                    } else {
                        opt.style.display = 'none';
                        opt.hidden = true;
                        opt.disabled = true;
                    }
                });

                if (!valStillValid && currentVal !== '') targetSelect.value = '';
            });
        });
    });
</script>
@endpush
