@extends('adminlte::page')

@section('title', 'Distribusi Lapangan & Sisa Makanan')

@section('content_header')
    <h1>Distribusi Lapangan & Sisa Makanan (Food Waste)</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Log Distribusi Makanan</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah">
                    <i class="fas fa-plus"></i> Tambah Log Distribusi
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="tabel-distribusi">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Sekolah Tujuan</th>
                        <th>Nama Driver</th>
                        <th>Porsi Dikirim</th>
                        <th>Sisa / Kembali</th>
                        <th>Keterangan Dibuang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($distribusis as $d)
                        <tr>
                            <td>{{ $d->tanggal->format('Y-m-d') }}</td>
                            <td>{{ $d->nama_sekolah }}</td>
                            <td>{{ $d->nama_driver }}</td>
                            <td>{{ $d->jumlah_porsi_dikirim }} Porsi</td>
                            <td>
                                @if($d->jumlah_sisa_kembali > 0)
                                    <span class="badge badge-danger">{{ $d->jumlah_sisa_kembali }} Sisa</span>
                                @else
                                    <span class="badge badge-success">Habis</span>
                                @endif
                            </td>
                            <td>{{ $d->keterangan_dibuang ?? '-' }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="editDistribusi({{ json_encode($d) }})"><i class="fas fa-edit"></i></button>
                                <form action="{{ route('distribusi.destroy', $d->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="{{ route('distribusi.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Log Distribusi</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Sekolah Tujuan</label>
                        <input type="text" name="nama_sekolah" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Driver</label>
                        <input type="text" name="nama_driver" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Porsi Dikirim</label>
                        <input type="number" name="jumlah_porsi_dikirim" class="form-control" required min="1">
                    </div>
                    <div class="form-group">
                        <label>Jumlah Sisa / Kembali (Food Waste)</label>
                        <input type="number" name="jumlah_sisa_kembali" class="form-control" value="0" min="0">
                        <small class="text-muted">Isi jika ada porsi yang dikembalikan / tidak dimakan.</small>
                    </div>
                    <div class="form-group">
                        <label>Keterangan Dibuang</label>
                        <textarea name="keterangan_dibuang" class="form-control" rows="2" placeholder="Contoh: 10 porsi basi / tidak ada siswa"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form id="formEdit" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Log Distribusi</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" id="edit_tanggal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Sekolah Tujuan</label>
                        <input type="text" name="nama_sekolah" id="edit_nama_sekolah" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Driver</label>
                        <input type="text" name="nama_driver" id="edit_nama_driver" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Porsi Dikirim</label>
                        <input type="number" name="jumlah_porsi_dikirim" id="edit_jumlah_porsi_dikirim" class="form-control" required min="1">
                    </div>
                    <div class="form-group">
                        <label>Jumlah Sisa / Kembali (Food Waste)</label>
                        <input type="number" name="jumlah_sisa_kembali" id="edit_jumlah_sisa_kembali" class="form-control" min="0">
                    </div>
                    <div class="form-group">
                        <label>Keterangan Dibuang</label>
                        <textarea name="keterangan_dibuang" id="edit_keterangan_dibuang" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
<script>
    function editDistribusi(data) {
        document.getElementById('formEdit').action = '/distribusi/' + data.id;
        document.getElementById('edit_tanggal').value = data.tanggal.substring(0, 10);
        document.getElementById('edit_nama_sekolah').value = data.nama_sekolah;
        document.getElementById('edit_nama_driver').value = data.nama_driver;
        document.getElementById('edit_jumlah_porsi_dikirim').value = data.jumlah_porsi_dikirim;
        document.getElementById('edit_jumlah_sisa_kembali').value = data.jumlah_sisa_kembali;
        document.getElementById('edit_keterangan_dibuang').value = data.keterangan_dibuang;
        $('#modalEdit').modal('show');
    }
</script>
@stop
