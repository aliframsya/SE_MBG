@extends('adminlte::page')

@section('title', 'Buat Pembayaran')

@section('content_header')
    <h1>Proses Pembayaran</h1>
@stop

@section('content')
    <div class="card card-primary">
        <form action="{{ route('admin.pembayaran.store') }}" method="POST" enctype="multipart/form-data">
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
                    <label>Tanggal Bayar</label>
                    <input type="date" name="tanggal_bayar" class="form-control" value="{{ now()->toDateString() }}" required>
                </div>

                <div class="form-group">
                    <label>Metode Pembayaran</label>
                    <select name="metode_bayar" class="form-control" required>
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="Tunai">Tunai</option>
                        <option value="Cek">Cek / Giro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Bukti Transfer (Opsional)</label>
                    <input type="file" name="bukti_transfer" class="form-control-file" accept="image/*">
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
                <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-default">Batal</a>
            </div>
        </form>
    </div>
@stop
