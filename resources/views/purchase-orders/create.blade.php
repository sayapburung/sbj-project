@extends('layouts.app')

@section('title', 'Tambah Purchase Order')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <b>Upload gagal:</b>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
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
    <label class="form-label fw-bold">Jenis PO *</label>

    <select name="jenis_po_id" class="form-control">
    <option value="" disabled selected>— Pilih Jenis PO —</option>

    @foreach($jenisPos->groupBy('kategori') as $kategori => $items)
        <optgroup label="{{ $kategori }}">
            @foreach($items as $item)
                <option value="{{ $item->id }}" {{ old('jenis_po_id') == $item->id ? 'selected' : '' }}>
                {{ $item->nama }}
                </option>
            @endforeach
        </optgroup>
    @endforeach
</select>
    @error('jenis_po')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label class="form-label fw-bold">Nomor PO</label>
    <input type="text"
           id="poNumberPreview"
           class="form-control"
           readonly>
    <small class="text-muted">
        Nomor otomatis berdasarkan Jenis PO & bulan berjalan
    </small>
</div>

<div class="mb-3">
    <label class="form-label">Nama Konsumen *</label>
    <input type="text"
           name="nama_konsumen"
           class="form-control @error('nama_konsumen') is-invalid @enderror"
           value="{{ old('nama_konsumen') }}"
           oninput="this.value = this.value.toUpperCase()"
           required>
    @error('nama_konsumen')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label class="form-label">Nama PO *</label>
    <input type="text"
           name="nama_po"
           class="form-control @error('nama_po') is-invalid @enderror"
           value="{{ old('nama_po') }}"
           oninput="this.value = this.value.toUpperCase()"
           required>
    @error('nama_po')
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
                    <!-- <div class="mb-3">
                        <label class="form-label">File</label>

                        <input type="file"
                            id="fileUpload"
                            name="file"
                            class="form-control @error('file') is-invalid @enderror">

                        <small class="text-muted d-block">
                            Max: 10MB Opsional
                        </small>

                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div id="fileError" class="text-danger mt-1" style="display:none;"></div>
                    </div> -->
                    <div class="mb-3">
                        <label class="form-label">Gambar Desain (Multi Upload)</label>

                        {{-- DROPZONE --}}
                        <div id="dropzone"
                            class="border border-2 border-dashed rounded p-4 text-center @error('images.*') border-danger @enderror"
                            style="cursor:pointer; background:#fafafa;">

                            <p class="mb-1">📌 Drag & Drop gambar disini</p>
                            <small class="text-muted">atau klik untuk pilih file</small>

                            {{-- INPUT ASLI (disembunyikan tapi tetap dipakai Laravel) --}}
                            <input type="file"
                                id="fileInput"
                                name="images[]"
                                multiple
                                accept="image/*"
                                class="@error('images.*') is-invalid @enderror"
                                hidden>
                        </div>

                        <small class="text-muted d-block mt-2">
                            Max per file: 5MB (Opsional)
                        </small>

                        {{-- ERROR VALIDATION --}}
                        @error('images.*')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                        {{-- PREVIEW --}}
                        <div id="preview" class="row mt-3 g-2"></div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <button type="submit" name="action" value="kirim_desain" class="btn btn-success">
                            <i class="fas fa-paper-plane"></i> Simpan & Kirim ke Desain
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


<script>
    const dropzone = document.getElementById("dropzone");
    const fileInput = document.getElementById("fileInput");
    const preview = document.getElementById("preview");
    const singleFileInput = document.getElementById('fileUpload');
    const singleFileError = document.getElementById('fileError');

    let filesArray = [];

    //Validasi Single File
        if (singleFileInput) {

        singleFileInput.addEventListener('change', function () {

            // reset error
            singleFileError.style.display = 'none';
            singleFileInput.classList.remove('is-invalid');

            const file = this.files[0];

            if (file) {

                const maxSize = 10 * 1024 * 1024; // 10MB

                if (file.size > maxSize) {

                    singleFileError.innerText = "Ukuran file terlalu besar! Maksimal 10MB.";
                    singleFileError.style.display = 'block';

                    this.value = ""; // reset file
                    singleFileInput.classList.add('is-invalid');
                }
            }
        });

    }

    // Klik dropzone → buka file picker
    dropzone.addEventListener("click", () => fileInput.click());

    // Saat pilih file manual
    fileInput.addEventListener("change", (e) => {
        handleFiles(e.target.files);
    });

    // Drag masuk
    dropzone.addEventListener("dragover", (e) => {
        e.preventDefault();
        dropzone.classList.add("bg-light");
    });

    // Drag keluar
    dropzone.addEventListener("dragleave", () => {
        dropzone.classList.remove("bg-light");
    });

    // Drop file
    dropzone.addEventListener("drop", (e) => {
        e.preventDefault();
        dropzone.classList.remove("bg-light");

        handleFiles(e.dataTransfer.files);
    });

    function handleFiles(files) {
        for (let file of files) {

            // Validasi max 5MB
            if (file.size > 5 * 1024 * 1024) {
                alert("File terlalu besar! Max 5MB per gambar.");
                continue;
            }

            filesArray.push(file);
        }

        renderPreview();
        updateInputFiles();
    }

    function renderPreview() {
        preview.innerHTML = "";

        filesArray.forEach((file, index) => {

            const reader = new FileReader();

            reader.onload = function(e) {
                const col = document.createElement("div");
                col.classList.add("col-md-3");

                col.innerHTML = `
                    <div class="card shadow-sm position-relative">
                        <img src="${e.target.result}"
                             class="card-img-top"
                             style="height:120px; object-fit:cover;">

                        <button type="button"
                                onclick="removeFile(${index})"
                                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1">
                            ✕
                        </button>
                    </div>
                `;

                preview.appendChild(col);
            };

            reader.readAsDataURL(file);
        });
    }

    function removeFile(index) {
        filesArray.splice(index, 1);
        renderPreview();
        updateInputFiles();
    }

    function updateInputFiles() {
        // Buat ulang FileList supaya Laravel tetap terima
        const dataTransfer = new DataTransfer();

        filesArray.forEach(file => {
            dataTransfer.items.add(file);
        });

        fileInput.files = dataTransfer.files;
    }
    document.querySelector('select[name="jenis_po_id"]').addEventListener('change', function () {

    const jenisPoId = this.value;
    const previewField = document.getElementById('poNumberPreview');

    if (!jenisPoId) {
        previewField.value = '';
        return;
    }

    fetch(`/generate-po-preview/${jenisPoId}`)
        .then(response => response.json())
        .then(data => {
            previewField.value = data.po_number;
        });
});
</script>

<style>
    #dropzone {
        transition: 0.2s;
    }

    #dropzone:hover {
        background: #f0f8ff;
        border-color: #0d6efd;
    }

    .border-dashed {
        border-style: dashed !important;
    }
</style>
@endsection