@extends('adminlte::page')

@section('title', 'Sekolah')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/notification-pop-up.css') }}">
@endsection

@section('content_header')
    <h1>Data Sekolah Penerima MBG</h1>
@endsection

@section('content')
    @can('master.school.create')
        <x-button-add idTarget="#modalAddSchool" text="Tambah Sekolah" />
    @endcan

    <x-notification-pop-up />

    <div class="card mt-2">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Sekolah</th>
                        <th>Alamat</th>
                        <th>Kota/Kab</th>
                        <th>Kepala Sekolah</th>
                        <th>No Telepon</th>
                        <th>Jumlah Siswa</th>
                        <th>Dapur Pengirim (Kitchen)</th>
                        @canany(['master.school.update', 'master.school.delete'])
                            <th>Aksi</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @forelse($schools as $index => $school)
                        <tr>
                            <td>{{ $schools->firstItem() + $index }}</td>
                            <td>{{ $school->name }}</td>
                            <td>{{ $school->address }}</td>
                            <td>{{ $school->city }}, {{ $school->province }}</td>
                            <td>{{ $school->kepala_sekolah }}</td>
                            <td>{{ $school->no_telepon }}</td>
                            <td>{{ $school->jumlah_siswa }}</td>
                            <td>{{ $school->kitchen ? $school->kitchen->nama : '-' }}</td>

                            @canany(['master.school.update', 'master.school.delete'])
                                <td>
                                    @can('master.school.update')
                                        <button class="btn btn-warning btn-sm" onclick="editSchool(this)"
                                            data-id="{{ $school->id }}" 
                                            data-name="{{ $school->name }}" 
                                            data-address="{{ $school->address }}"
                                            data-city="{{ $school->city }}"
                                            data-province="{{ $school->province }}"
                                            data-kepala="{{ $school->kepala_sekolah }}"
                                            data-telp="{{ $school->no_telepon }}"
                                            data-siswa="{{ $school->jumlah_siswa }}"
                                            data-kitchen="{{ $school->kitchen_id }}">
                                            Edit
                                        </button>
                                    @endcan

                                    @can('master.school.delete')
                                        <x-button-delete idTarget="#modalDeleteSchool" formId="formDeleteSchool"
                                            action="{{ route('master.school.destroy', $school->id) }}" text="Hapus" />
                                    @endcan
                                </td>
                            @endcanany
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Belum ada Sekolah</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3 d-flex justify-content-end">
                {{ $schools->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    {{-- MODAL ADD SCHOOL --}}
    <x-modal-form id="modalAddSchool" title="Tambah Sekolah" action="{{ route('master.school.store') }}"
        submitText="Simpan">
        <div class="form-group mt-2">
            <label for="name">Nama Sekolah</label>
            <input type="text" name="name" class="form-control" required />
        </div>

        <div class="form-group mt-2">
            <label for="address">Alamat</label>
            <textarea name="address" class="form-control" rows="2"></textarea>
        </div>

        <div class="form-group mt-2">
            <label for="city">Kota/Kabupaten</label>
            <input type="text" name="city" class="form-control" value="Kota Bogor" />
        </div>
        
        <div class="form-group mt-2">
            <label for="province">Provinsi</label>
            <input type="text" name="province" class="form-control" value="Jawa Barat" />
        </div>

        <div class="form-group mt-2">
            <label for="kepala_sekolah">Kepala Sekolah</label>
            <input type="text" name="kepala_sekolah" class="form-control" />
        </div>

        <div class="form-group mt-2">
            <label for="no_telepon">No Telepon</label>
            <input type="text" name="no_telepon" class="form-control" />
        </div>

        <div class="form-group mt-2">
            <label for="jumlah_siswa">Jumlah Siswa</label>
            <input type="number" name="jumlah_siswa" class="form-control" value="0" />
        </div>

        <div class="form-group mt-2">
            <label for="kitchen_id">Dapur Pengirim</label>
            <select name="kitchen_id" class="form-control">
                <option value="">-- Pilih Dapur --</option>
                @foreach($kitchens as $kitchen)
                    <option value="{{ $kitchen->id }}">{{ $kitchen->nama }}</option>
                @endforeach
            </select>
        </div>
    </x-modal-form>

    {{-- MODAL EDIT SCHOOL --}}
    <x-modal-form id="modalEditSchool" title="Edit Sekolah" action="" submitText="Update">
        @method('PUT')
        <input type="hidden" name="id" id="edit_id">

        <div class="form-group mt-2">
            <label for="name">Nama Sekolah</label>
            <input type="text" id="edit_name" name="name" class="form-control" required />
        </div>

        <div class="form-group mt-2">
            <label for="address">Alamat</label>
            <textarea id="edit_address" name="address" class="form-control" rows="2"></textarea>
        </div>

        <div class="form-group mt-2">
            <label for="city">Kota/Kabupaten</label>
            <input type="text" id="edit_city" name="city" class="form-control" />
        </div>
        
        <div class="form-group mt-2">
            <label for="province">Provinsi</label>
            <input type="text" id="edit_province" name="province" class="form-control" />
        </div>

        <div class="form-group mt-2">
            <label for="kepala_sekolah">Kepala Sekolah</label>
            <input type="text" id="edit_kepala" name="kepala_sekolah" class="form-control" />
        </div>

        <div class="form-group mt-2">
            <label for="no_telepon">No Telepon</label>
            <input type="text" id="edit_telp" name="no_telepon" class="form-control" />
        </div>

        <div class="form-group mt-2">
            <label for="jumlah_siswa">Jumlah Siswa</label>
            <input type="number" id="edit_siswa" name="jumlah_siswa" class="form-control" />
        </div>

        <div class="form-group mt-2">
            <label for="kitchen_id">Dapur Pengirim</label>
            <select id="edit_kitchen" name="kitchen_id" class="form-control">
                <option value="">-- Pilih Dapur --</option>
                @foreach($kitchens as $kitchen)
                    <option value="{{ $kitchen->id }}">{{ $kitchen->nama }}</option>
                @endforeach
            </select>
        </div>
    </x-modal-form>

    {{-- MODAL DELETE --}}
    <x-modal-delete id="modalDeleteSchool" formId="formDeleteSchool" title="Konfirmasi Hapus"
        message="Apakah Anda yakin ingin menghapus sekolah ini?" confirmText="Hapus" />
@endsection

<script>
    function editSchool(button) {
        const id = button.dataset.id;

        const form = document.querySelector('#modalEditSchool form');
        form.action = `/dashboard/master/sekolah/${id}`;

        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = button.dataset.name;
        document.getElementById('edit_address').value = button.dataset.address;
        document.getElementById('edit_city').value = button.dataset.city;
        document.getElementById('edit_province').value = button.dataset.province;
        document.getElementById('edit_kepala').value = button.dataset.kepala;
        document.getElementById('edit_telp').value = button.dataset.telp;
        document.getElementById('edit_siswa').value = button.dataset.siswa;
        document.getElementById('edit_kitchen').value = button.dataset.kitchen;

        $('#modalEditSchool').modal('show');
    }
</script>
