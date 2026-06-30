@extends('adminlte::page')

@section('title', 'Edit Penerimaan')

@section('content_header')
    <h1>Edit Data Penerimaan Barang</h1>
@stop

@section('content')
    <div class="card card-warning">
        <form action="{{ route('admin.penerimaan.update', $penerimaan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="form-group">
                    <label>Purchase Order</label>
                    <input type="text" class="form-control" value="{{ $penerimaan->purchaseOrder->kode_po }}" disabled>
                </div>

                <div class="form-group">
                    <label>Tanggal Terima</label>
                    <input type="date" name="tanggal_terima" class="form-control" value="{{ $penerimaan->tanggal_terima->format('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label>Kondisi Bahan (Catatan QC)</label>
                    <input type="text" name="kondisi_bahan" class="form-control" value="{{ $penerimaan->kondisi_bahan }}" required>
                </div>

                <div class="form-group">
                    <label>Total Kuantitas Rijek (Jika Ada)</label>
                    <input type="number" step="0.1" name="kuantitas_rijek" class="form-control" value="{{ $penerimaan->kuantitas_rijek }}" required>
                    <small class="text-muted">Isi dengan 0 jika tidak ada barang yang rijek/rusak.</small>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-warning">Update Data</button>
                <a href="{{ route('admin.penerimaan.index') }}" class="btn btn-default">Batal</a>
            </div>
        </form>
    </div>
@stop
