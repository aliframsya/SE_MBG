@extends('adminlte::page')

@section('title', 'Buat PO')

@section('content_header')
    <h1>Buat Purchase Order Baru</h1>
@stop

@section('content')
    <div class="card card-primary">
        <form action="{{ route('admin.po.store') }}" method="POST">
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
                    <label>Supplier</label>
                    <select name="supplier_id" class="form-control" required>
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->nama }} ({{ $sup->kode }}) - {{ $sup->kategori ?? 'Tanpa Kategori' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal PO</label>
                    <input type="date" name="tanggal_po" class="form-control" value="{{ now()->toDateString() }}" required>
                </div>

                <label>Bahan Baku & Kuantitas</label>
                <div id="po-items-container">
                    <div class="row mb-2 po-item-row">
                        <div class="col-7">
                            <select name="items[0][bahan_baku_id]" class="form-control form-control-sm" required>
                                <option value="">-- Bahan Baku --</option>
                                @foreach($bahanBakus as $b)
                                    <option value="{{ $b->id }}">{{ $b->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4">
                            <input type="number" step="0.1" name="items[0][kuantitas]" class="form-control form-control-sm" placeholder="Qty" required>
                        </div>
                        <div class="col-1"></div>
                    </div>
                </div>
                <button type="button" class="btn btn-xs btn-outline-primary mb-3" onclick="addPoItemField()">
                    <i class="fas fa-plus mr-1"></i> Tambah Bahan
                </button>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan PO</button>
                <a href="{{ route('admin.po.index') }}" class="btn btn-default">Batal</a>
            </div>
        </form>
    </div>
@stop

@section('js')
<script>
    let poItemIndex = 1;
    function addPoItemField() {
        const container = document.getElementById('po-items-container');
        const row = document.createElement('div');
        row.className = 'row mb-2 po-item-row';
        row.innerHTML = `
            <div class="col-7">
                <select name="items[${poItemIndex}][bahan_baku_id]" class="form-control form-control-sm" required>
                    <option value="">-- Bahan Baku --</option>
                    @foreach($bahanBakus as $b)
                        <option value="{{ $b->id }}">{{ $b->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <input type="number" step="0.1" name="items[${poItemIndex}][kuantitas]" class="form-control form-control-sm" placeholder="Qty" required>
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-sm btn-danger" onclick="this.parentElement.parentElement.remove()">X</button>
            </div>
        `;
        container.appendChild(row);
        poItemIndex++;
    }
</script>
@stop
