@extends('layouts.app')

@section('title', 'Tambah Purchase Order')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Tambah Purchase Order</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('purchase-orders.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Konsumen *</label>
                        <input type="text" name="nama_konsumen" class="form-control @error('nama_konsumen') is-invalid @enderror" value="{{ old('nama_konsumen') }}" required>
                        @error('nama_konsumen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
    <label class="form-label fw-bold">Jenis PO *</label>

    <select name="jenis_po" 
            class="form-select @error('jenis_po') is-invalid @enderror" 
            required>

        <option value="" disabled selected>— Pilih Jenis PO —</option>

        <!-- Kategori PRINTING -->
        <optgroup label="📌 Printing">
            <option value="Print Only" {{ old('jenis_po') == 'Print Only' ? 'selected' : '' }}>
                Print Only
            </option>
            <option value="Print Press" {{ old('jenis_po') == 'Print Press' ? 'selected' : '' }}>
                Print Press
            </option>
            <option value="Print Press + Bahan" {{ old('jenis_po') == 'Print Press + Bahan' ? 'selected' : '' }}>
                Print Press + Bahan
            </option>
            <option value="Print Press + Laser" {{ old('jenis_po') == 'Print Press + Laser' ? 'selected' : '' }}>
                Print Press + Laser
            </option>
            <option value="Print Press + Bahan + Laser" {{ old('jenis_po') == 'Print Press + Bahan + Laser' ? 'selected' : '' }}>
                Print Press + Bahan + Laser
            </option>
        </optgroup>

        <!-- Kategori FULL ORDER -->
        <optgroup label="🧥 Full Order">
            <option value="Full Order Jaket" {{ old('jenis_po') == 'Full Order Jaket' ? 'selected' : '' }}>
                Full Order Jaket
            </option>
            <option value="Full Order Atasan" {{ old('jenis_po') == 'Full Order Atasan' ? 'selected' : '' }}>
                Full Order Atasan
            </option>
            <option value="Full Order Stelan Pendek" {{ old('jenis_po') == 'Full Order Stelan Pendek' ? 'selected' : '' }}>
                Full Order Stelan Pendek
            </option>
            <option value="Full Order Stelan Panjang" {{ old('jenis_po') == 'Full Order Stelan Panjang' ? 'selected' : '' }}>
                Full Order Stelan Panjang
            </option>
            <option value="Full Order Stelan Panjang dan Pendek" {{ old('jenis_po') == 'Full Order Stelan Panjang dan Pendek' ? 'selected' : '' }}>
                Full Order Stelan Panjang dan Pendek
            </option>
            <option value="Full Order Stelan & Atasan" {{ old('jenis_po') == 'Full Order Stelan & Atasan' ? 'selected' : '' }}>
                Full Order Stelan & Atasan
            </option>
            <option value="Full Order Celana Pendek" {{ old('jenis_po') == 'Full Order Celana Pendek' ? 'selected' : '' }}>
                Full Order Celana Pendek
            </option>
            <option value="Full Order Celana Panjang" {{ old('jenis_po') == 'Full Order Celana Panjang' ? 'selected' : '' }}>
                Full Order Celana Panjang
            </option>
        </optgroup>

    </select>

    @error('jenis_po')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Bahan *</label>
                        <input type="text" name="jenis_bahan" class="form-control @error('jenis_bahan') is-invalid @enderror" value="{{ old('jenis_bahan') }}" required>
                        @error('jenis_bahan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Pcs</label>
                            <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah', 0) }}" min="0">
                            @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Meteran Admin</label>
                            <input type="number" step="0.01" name="meteran" class="form-control @error('meteran') is-invalid @enderror" value="{{ old('meteran', 0) }}" min="0">
                            @error('meteran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Order *</label>
                            <input type="date" name="tanggal_order" class="form-control @error('tanggal_order') is-invalid @enderror" value="{{ old('tanggal_order', date('Y-m-d')) }}" required>
                            @error('tanggal_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Deadline *</label>
                            <input type="date" name="deadline" class="form-control @error('deadline') is-invalid @enderror" value="{{ old('deadline') }}" required>
                            @error('deadline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                     <div class="mb-3">
                        <label class="form-label">File</label>
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">
                        <small class="text-muted">Max: 10MB Opsional</small>
                        @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar Desain (Multi Upload)</label>
                        <input type="file" name="images[]" class="form-control @error('images.*') is-invalid @enderror" multiple accept="image/*">
                        <small class="text-muted">Max per file: 5MB Opsional</small>
                        @error('images.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection