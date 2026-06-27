@extends('adminlte::page')

@section('title', 'Daftar Pembayaran')

@section('content_header')
    <h1>Daftar Pembayaran</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pembayaran</h3>
            <div class="card-tools">
                <a href="{{ route('admin.pembayaran.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Buat Pembayaran
                </a>
            </div>
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
                        <th>Kode PO</th>
                        <th>Tanggal Bayar</th>
                        <th>Metode</th>
                        <th>Jumlah (Rp)</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayarans as $pem)
                        <tr>
                            <td>{{ $pem->purchaseOrder->kode_po }}</td>
                            <td>{{ $pem->tanggal_bayar->format('d/m/Y') }}</td>
                            <td>{{ $pem->metode_bayar }}</td>
                            <td>{{ number_format($pem->jumlah_bayar, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-{{ $pem->status_bayar == 'lunas' ? 'success' : 'warning' }}">
                                    {{ strtoupper($pem->status_bayar) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.pembayaran.show', $pem->id) }}" class="btn btn-xs btn-info">Detail</a>
                                @if($pem->status_bayar == 'pending')
                                    <a href="{{ route('admin.pembayaran.edit', $pem->id) }}" class="btn btn-xs btn-warning">Edit</a>
                                    
                                    <form action="{{ route('admin.pembayaran.konfirmasi', $pem->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-xs btn-success" title="Konfirmasi Lunas">Konfirmasi Lunas</button>
                                    </form>

                                    <form action="{{ route('admin.pembayaran.destroy', $pem->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus Pembayaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-danger" title="Hapus">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">Belum ada data pembayaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
