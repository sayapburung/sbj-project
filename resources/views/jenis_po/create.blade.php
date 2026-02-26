@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Tambah Jenis PO</h4>

    <form action="{{ route('jenis-po.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Kategori</label>
            <input type="text" name="kategori" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Kode (untuk nomor PO)</label>
            <input type="text" name="kode" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Nama Jenis</label>
            <input type="text" name="nama" class="form-control" required>
        </div>

        <button class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection