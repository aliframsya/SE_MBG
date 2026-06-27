@extends('adminlte::page')

@section('title', 'Daftar Penerimaan Barang')

@section('content_header')
    <h1>Daftar Penerimaan Barang (QC)</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Penerimaan</h3>
            <div class="card-tools">
                <a href="{{ route('admin.penerimaan.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Terima Barang Baru
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
                        <th>Tanggal Terima</th>
                        <th>Kondisi</th>
                        <th>Status Rijek</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penerimaans as $pen)
                        <tr>
                            <td>{{ $pen->purchaseOrder->kode_po }}</td>
                            <td>{{ $pen->tanggal_terima->format('d/m/Y') }}</td>
                            <td>{{ $pen->kondisi_bahan }}</td>
                            <td>
                                @if($pen->status_rijek)
                                    <span class="badge badge-danger">Ada Rijek ({{ $pen->kuantitas_rijek }})</span>
                                @else
                                    <span class="badge badge-success">Aman (0)</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.penerimaan.show', $pen->id) }}" class="btn btn-xs btn-info">Detail</a>
                                <a href="{{ route('admin.penerimaan.edit', $pen->id) }}" class="btn btn-xs btn-warning">Edit</a>
                                <form action="{{ route('admin.penerimaan.destroy', $pen->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus Data Penerimaan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger" title="Hapus">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">Belum ada data penerimaan barang.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
