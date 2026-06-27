@extends('adminlte::page')

@section('title', 'Stok Gudang')

@section('content_header')
    <h1>Stok Gudang (FIFO)</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header"><h3 class="card-title">Keluarkan Bahan (Metode FIFO)</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.stok.keluarkan') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Bahan Baku</label>
                            <select name="bahan_baku_id" class="form-control" required>
                                <option value="">-- Pilih Bahan --</option>
                                @foreach($bahanBakus as $b)
                                    <option value="{{ $b->id }}">{{ $b->nama }} ({{ $b->kitchen->nama ?? 'Pusat' }})</option>
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

        <div class="col-md-6">
            <div class="card card-warning">
                <div class="card-header"><h3 class="card-title">Rekonsiliasi FIFO</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.stok.rekonsiliasi') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Bahan Baku</label>
                            <select name="bahan_baku_id" class="form-control" required>
                                <option value="">-- Pilih Bahan --</option>
                                @foreach($bahanBakus as $b)
                                    <option value="{{ $b->id }}">{{ $b->nama }} ({{ $b->kitchen->nama ?? 'Pusat' }})</option>
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

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Lot Stok di Gudang</h3>
        </div>
        <div class="card-body p-0">
            @if(session('success'))
                <div class="alert alert-success m-3">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger m-3">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

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
                                @else
                                    <span class="badge badge-success">{{ $stok->kuantitas }}</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($stok->tanggal_masuk)->format('d/m/Y') }}</td>
                            <td>{{ $stok->lokasi_gudang ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5">Gudang kosong. Belum ada stok barang.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
