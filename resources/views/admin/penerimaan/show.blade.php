@extends('adminlte::page')

@section('title', 'Detail Penerimaan')

@section('content_header')
    <h1>Detail Penerimaan Barang</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header"><h3 class="card-title">Informasi Penerimaan</h3></div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Purchase Order</th>
                            <td>{{ $penerimaan->purchaseOrder->kode_po }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Terima</th>
                            <td>{{ $penerimaan->tanggal_terima->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Kondisi Bahan (QC)</th>
                            <td>{{ $penerimaan->kondisi_bahan }}</td>
                        </tr>
                        <tr>
                            <th>Status Rijek</th>
                            <td>
                                @if($penerimaan->status_rijek)
                                    <span class="badge badge-danger">Ada Rijek</span>
                                @else
                                    <span class="badge badge-success">Tidak Ada Rijek</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Kuantitas Rijek</th>
                            <td>{{ $penerimaan->kuantitas_rijek }} unit</td>
                        </tr>
                        <tr>
                            <th>Status QC Internal</th>
                            <td>{{ $penerimaan->cekKualitas() }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.penerimaan.index') }}" class="btn btn-default">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@stop
