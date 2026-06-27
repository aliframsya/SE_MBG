@extends('adminlte::page')

@section('title', 'Detail Pembayaran')

@section('content_header')
    <h1>Detail Pembayaran</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header"><h3 class="card-title">Informasi Pembayaran</h3></div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Purchase Order</th>
                            <td>{{ $pembayaran->purchaseOrder->kode_po }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Bayar</th>
                            <td>{{ $pembayaran->tanggal_bayar->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Metode Pembayaran</th>
                            <td>{{ $pembayaran->metode_bayar }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah Bayar</th>
                            <td>Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge badge-{{ $pembayaran->status_bayar == 'lunas' ? 'success' : 'warning' }}">
                                    {{ strtoupper($pembayaran->status_bayar) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-default">Kembali</a>
                    @if($pembayaran->status_bayar == 'pending')
                        <form action="{{ route('admin.pembayaran.konfirmasi', $pembayaran->id) }}" method="POST" class="d-inline float-right">
                            @csrf
                            <button type="submit" class="btn btn-success">Konfirmasi Lunas</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-secondary">
                <div class="card-header"><h3 class="card-title">Bukti Transfer</h3></div>
                <div class="card-body text-center">
                    @if($pembayaran->bukti_transfer)
                        <img src="{{ asset('storage/' . $pembayaran->bukti_transfer) }}" alt="Bukti Transfer" class="img-fluid img-thumbnail" style="max-height: 300px;">
                    @else
                        <p class="text-muted">Tidak ada lampiran bukti transfer.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
