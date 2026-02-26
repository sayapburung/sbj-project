@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">✏️ Edit Jenis PO</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('jenis-po.update', $jenisPo->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Kategori --}}
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <input type="text"
                           name="kategori"
                           class="form-control @error('kategori') is-invalid @enderror"
                           value="{{ old('kategori', $jenisPo->kategori) }}"
                           required>

                    @error('kategori')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Kode --}}
                <div class="mb-3">
                    <label class="form-label">Kode</label>
                    <input type="text"
                           name="kode"
                           class="form-control @error('kode') is-invalid @enderror"
                           value="{{ old('kode', $jenisPo->kode) }}"
                           required>

                    @error('kode')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Nama --}}
                <div class="mb-3">
                    <label class="form-label">Nama Jenis PO</label>
                    <input type="text"
                           name="nama"
                           class="form-control @error('nama') is-invalid @enderror"
                           value="{{ old('nama', $jenisPo->nama) }}"
                           required>

                    @error('nama')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Status Active --}}
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="1" {{ $jenisPo->is_active ? 'selected' : '' }}>
                            Aktif
                        </option>
                        <option value="0" {{ !$jenisPo->is_active ? 'selected' : '' }}>
                            Nonaktif
                        </option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('jenis-po.index') }}" class="btn btn-secondary">
                        ← Kembali
                    </a>

                    <button type="submit" class="btn btn-warning">
                        💾 Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection