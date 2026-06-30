@extends('layouts.app')

@section('content')
<h1>Data Karyawan</h1>

<a href="{{ route('master.karyawan.') }}" class="btn btn-primary mb-3">+ Tambah Karyawan</a>

<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>ID</th>
            <th>Nama</th>
            <th>Jabatan</th>
            <th>Dapur</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($karyawan as $i => $karyawan)
        <tr>
            <td>{{ $karyawan->firstItem() + $i }}</td>
            <td>{{ $karyawan->kode }}</td>
            <td>{{ $karyawan->nik }}</td>
            <td>{{ $karyawan->nama }}</td>
            <td>{{ $karyawan->jabatan }}</td>
            <td>{{ $karyawan->kitchen->nama ?? '-' }}</td>
            <td>{{ $karyawan->status }}</td>
            <td>
                <a href="{{ route('master.karyawan.edit', $karyawan->id) }}">Edit</a>
                <form action="{{ route('master.karyawan.destroy', $karyawan->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Yakin hapus?')">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center">Belum ada karyawan</td></tr>
        @endforelse
    </tbody>
</table>

{{ $karyawan->links() }}
@endsection