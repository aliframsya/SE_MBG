@extends('adminlte::page')

@section('title', 'Edit Pembayaran')

@section('content_header')
    <h1>Edit Pembayaran</h1>
@stop

@section('content')
    <div class="card card-warning">
        <form action="{{ route('admin.pembayaran.update', $pembayaran->id) }}" method="POST" enctype="multipart/form-data">
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
                    <input type="text" class="form-control" value="{{ $pembayaran->purchaseOrder->kode_po }} (Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }})" disabled>
                </div>

                <div class="form-group">
                    <label>Tanggal Bayar</label>
                    <input type="date" name="tanggal_bayar" class="form-control" value="{{ $pembayaran->tanggal_bayar->format('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label>Metode Pembayaran</label>
                    <select name="metode_bayar" class="form-control" required>
                        <option value="Transfer Bank" {{ $pembayaran->metode_bayar == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                        <option value="Tunai" {{ $pembayaran->metode_bayar == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="Cek" {{ $pembayaran->metode_bayar == 'Cek' ? 'selected' : '' }}>Cek / Giro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Bukti Transfer Baru (Opsional)</label>
                    <input type="file" name="bukti_transfer" class="form-control-file" accept="image/*">
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah bukti yang sudah ada.</small>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-warning">Update Pembayaran</button>
                <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-default">Batal</a>
            </div>
        </form>
    </div>
@stop
