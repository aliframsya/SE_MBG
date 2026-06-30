@extends('adminlte::page')

@section('title', 'Detail PO')

@section('content_header')
    <h1>Detail Purchase Order: {{ $po->kode_po }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header"><h3 class="card-title">Informasi PO</h3></div>
                <div class="card-body">
                    <p><strong>Status:</strong> {{ strtoupper($po->status) }}</p>
                    <p><strong>Supplier:</strong> {{ $po->supplier?->nama ?? 'Supplier Terhapus' }}</p>
                    <p><strong>Tanggal PO:</strong> {{ $po->tanggal_po->format('d/m/Y') }}</p>
                    <p><strong>Total Harga:</strong> Rp {{ number_format($po->total_harga, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-secondary">
                <div class="card-header"><h3 class="card-title">Pembayaran & Penerimaan</h3></div>
                <div class="card-body">
                    <p><strong>Pembayaran:</strong> 
                        @if($po->pembayaran)
                            <span class="badge badge-success">{{ strtoupper($po->pembayaran->status_bayar) }}</span>
                        @else
                            <span class="badge badge-warning">Belum Ada Pembayaran</span>
                        @endif
                    </p>
                    <p><strong>Penerimaan (QC):</strong> 
                        @if($po->penerimaan)
                            <span class="badge badge-success">DITERIMA</span> (Kondisi: {{ $po->penerimaan->kondisi_bahan }})
                        @else
                            <span class="badge badge-warning">Belum Diterima</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3 class="card-title">Detail Bahan Baku</h3></div>
        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Bahan Baku</th>
                        <th>Qty Pesan</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($po->details as $d)
                        <tr>
                            <td>{{ $d->bahanBaku?->nama ?? 'Bahan Baku Terhapus' }}</td>
                            <td>{{ $d->kuantitas_pesan }}</td>
                            <td>Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($d->kuantitas_pesan * $d->harga_satuan, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.po.index') }}" class="btn btn-default">Kembali</a>
        </div>
    </div>
@stop
