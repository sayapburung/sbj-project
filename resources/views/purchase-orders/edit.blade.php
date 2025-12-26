@extends('layouts.app')

@section('title', 'Edit Purchase Order')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Edit Purchase Order - {{ $order->po_number }}</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('purchase-orders.update', $order->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Konsumen *</label>
                        <input type="text" name="nama_konsumen" class="form-control @error('nama_konsumen') is-invalid @enderror" value="{{ old('nama_konsumen', $order->nama_konsumen) }}" required>
                        @error('nama_konsumen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis PO *</label>
                        <input type="text" name="jenis_po" class="form-control @error('jenis_po') is-invalid @enderror" value="{{ old('jenis_po', $order->jenis_po) }}" required>
                        @error('jenis_po')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">File</label>
                        @if($order->file)
                        <div class="mb-2">
                            <a href="{{ asset('storage/'.$order->file) }}" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-file"></i> Lihat File Saat Ini
                            </a>
                        </div>
                        @endif
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">
                        <small class="text-muted">Max: 10MB. Kosongkan jika tidak ingin mengubah file.</small>
                        @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah', $order->jumlah) }}" min="0">
                            @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Meteran</label>
                            <input type="number" step="0.01" name="meteran" class="form-control @error('meteran') is-invalid @enderror" value="{{ old('meteran', $order->meteran) }}" min="0">
                            @error('meteran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    @if($order->images->count() > 0)
                    <div class="mb-3">
                        <label class="form-label">Gambar Saat Ini</label>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach($order->images as $image)
                            <div class="position-relative">
                                <img src="{{ asset('storage/'.$image->image_path) }}" alt="Gambar" style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px;">
                                <form method="POST" action="{{ route('purchase-orders.delete-image', $image->id) }}" class="position-absolute top-0 end-0" onsubmit="return confirm('Hapus gambar ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" style="padding: 2px 6px;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Tambah Gambar Desain</label>
                        <input type="file" name="images[]" class="form-control @error('images.*') is-invalid @enderror" multiple accept="image/*">
                        <small class="text-muted">Max per file: 5MB</small>
                        @error('images.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Order *</label>
                            <input type="date" name="tanggal_order" class="form-control @error('tanggal_order') is-invalid @enderror" value="{{ old('tanggal_order', $order->tanggal_order->format('Y-m-d')) }}" required>
                            @error('tanggal_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Deadline *</label>
                            <input type="date" name="deadline" class="form-control @error('deadline') is-invalid @enderror" value="{{ old('deadline', $order->deadline->format('Y-m-d')) }}" required>
                            @error('deadline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Bahan *</label>
                        <input type="text" name="jenis_bahan" class="form-control @error('jenis_bahan') is-invalid @enderror" value="{{ old('jenis_bahan', $order->jenis_bahan) }}" required>
                        @error('jenis_bahan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card mt-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fas fa-history"></i> History Transisi Order</h5>
    </div>
    <div class="card-body">
        <x-order-history-timeline :histories="$order->histories" />
    </div>
</div>
</div>
@endsection