@extends('adminlte::page')

@section('title', 'Purchase Order (PO)')

@section('content_header')
    <h1>Daftar Purchase Order</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar PO</h3>
            <div class="card-tools">
                <a href="{{ route('admin.po.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Buat PO Baru
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
                        <th>Supplier</th>
                        <th>Tanggal PO</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $po)
                        <tr>
                            <td>{{ $po->kode_po }}</td>
                            <td>{{ $po->supplier?->nama ?? '-' }}</td>
                            <td>{{ $po->tanggal_po->format('d/m/Y') }}</td>
                            <td>Rp {{ number_format($po->total_harga, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $statusBadge = ['draft' => 'secondary', 'dikirim' => 'primary', 'selesai' => 'success', 'dibatalkan' => 'danger'];
                                @endphp
                                <span class="badge badge-{{ $statusBadge[$po->status] ?? 'info' }}">{{ strtoupper($po->status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.po.show', $po->id) }}" class="btn btn-xs btn-info">Detail</a>
                                
                                @php
                                    $waNumber = preg_replace('/[^0-9]/', '', $po->supplier?->nomor ?? '');
                                    if(strlen($waNumber) > 0 && substr($waNumber, 0, 1) == '0') {
                                        $waNumber = '62' . substr($waNumber, 1);
                                    }
                                    
                                    $waText = "Halo " . ($po->supplier?->nama ?? 'Supplier') . ",\n\nBerikut adalah rincian Purchase Order (PO) kami:\nKode PO: " . $po->kode_po . "\nTanggal: " . $po->tanggal_po->format('d/m/Y') . "\n\nBarang yang dipesan:\n";
                                    foreach($po->details as $idx => $detail) {
                                        $waText .= ($idx+1) . ". " . $detail->bahanBaku->nama . " - " . $detail->kuantitas_pesan . " " . ($detail->bahanBaku->unit->nama ?? '') . "\n";
                                    }
                                    $waText .= "\nMohon konfirmasinya. Terima kasih.";
                                    $waUrl = "https://wa.me/" . $waNumber . "?text=" . urlencode($waText);
                                @endphp
                                <a href="{{ $waUrl }}" target="_blank" class="btn btn-xs btn-success"><i class="fab fa-whatsapp"></i> Kirim WA</a>

                                @if($po->status == 'draft')
                                    <a href="{{ route('admin.po.edit', $po->id) }}" class="btn btn-xs btn-warning">Edit</a>
                                    
                                    <form action="{{ route('admin.po.confirm', $po->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-xs btn-success" title="Kirim PO">Kirim</button>
                                    </form>

                                    <form action="{{ route('admin.po.destroy', $po->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus PO ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-danger" title="Hapus">Hapus</button>
                                    </form>
                                @endif
                                
                                @if($po->status == 'dikirim' && !$po->pembayaran)
                                    <form action="{{ route('admin.po.cancel', $po->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Batalkan PO ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-xs btn-danger">Batal</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">Tidak ada Purchase Order.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
