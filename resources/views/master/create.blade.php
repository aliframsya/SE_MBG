@extends('layouts.app')

@section('content')
<h1>Tambah Karyawan</h1>

<form action="{{ route('master.karyawan.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>Kode</label>
    <input type="text" name="kode" class="form-control" required>

    <label>ID</label>
    <input type="text" name="nik" class="form-control" required>

    <label>Nama</label>
    <input type="text" name="nama" class="form-control" required>

    <label>Jabatan</label>
    <input type="text" name="jabatan" class="form-control" required>

    <label>Dapur</label>
    <select name="kitchen_kode" class="form-control">
        <option value="">- Pilih Dapur -</option>
        @foreach($kitchens as $kitchen)
            <option value="{{ $kitchen->kode }}">{{ $kitchen->nama }}</option>
        @endforeach
    </select>

    <label>No HP</label>
    <input type="text" name="no_hp" class="form-control">

    <label>Alamat</label>
    <textarea name="alamat" class="form-control"></textarea>

    <label>Tanggal Masuk</label>
    <input type="date" name="tanggal_masuk" class="form-control">

    <label>Status</label>
    <select name="status" class="form-control" required>
        <option value="aktif">Aktif</option>
        <option value="nonaktif">Nonaktif</option>
    </select>

    <label>Foto</label>
    <input type="file" name="foto" class="form-control">

    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
</form>
@endsection