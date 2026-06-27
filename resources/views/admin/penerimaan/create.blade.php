@extends('adminlte::page')

@section('title', 'Terima Barang')

@section('content_header')
    <h1>Proses Penerimaan Barang (QC)</h1>
@stop

@section('content')
    <div class="card card-primary">
        <form action="{{ route('admin.penerimaan.store') }}" method="POST">
            @csrf
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="form-group">
                    <label>Pilih Purchase Order</label>
                    <select name="po_id" class="form-control" required>
                        <option value="">-- Pilih PO (Status: Dikirim) --</option>
                        @if($po)
                            <option value="{{ $po->id }}" selected>{{ $po->kode_po }} - Rp {{ number_format($po->total_harga, 0, ',', '.') }}</option>
                        @endif
                        @foreach($pendingPOs as $p)
                            @if(!$po || $p->id !== $po->id)
                                <option value="{{ $p->id }}">{{ $p->kode_po }} - Rp {{ number_format($p->total_harga, 0, ',', '.') }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Terima</label>
                    <input type="date" name="tanggal_terima" class="form-control" value="{{ now()->toDateString() }}" required>
                </div>

                <div class="form-group">
                    <label>Kondisi Bahan (Catatan QC)</label>
                    <input type="text" name="kondisi_bahan" class="form-control" placeholder="Contoh: Baik, kemasan utuh" required>
                </div>

                <div class="form-group">
                    <label>Total Kuantitas Rijek (Jika Ada)</label>
                    <input type="number" step="0.1" name="kuantitas_rijek" class="form-control" value="0" required>
                    <small class="text-muted">Isi dengan 0 jika tidak ada barang yang rijek/rusak.</small>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Proses & Masukkan Gudang</button>
                <a href="{{ route('admin.penerimaan.index') }}" class="btn btn-default">Batal</a>
            </div>
        </form>
    </div>
@stop
