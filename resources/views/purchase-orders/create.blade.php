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
                        <div class="progress mt-3" style="height:20px; display:none;" id="uploadProgressWrapper">
    <div id="uploadProgressBar"
         class="progress-bar progress-bar-striped progress-bar-animated"
         role="progressbar"
         style="width: 0%">
        0%
    </div>
</div>
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
document.addEventListener("DOMContentLoaded", function() {

    const dropzone = document.getElementById("dropzone");
    const fileInput = document.getElementById("fileInput");
    const preview = document.getElementById("preview");
    const form = document.querySelector("form");
    const progressWrapper = document.getElementById("uploadProgressWrapper");
    const progressBar = document.getElementById("uploadProgressBar");
    const jenisSelect = document.querySelector('select[name="jenis_po_id"]');

    let filesArray = [];

    // =========================
    // GENERATE NOMOR PO
    // =========================
    if (jenisSelect) {
        jenisSelect.addEventListener("change", function() {

            const jenisPoId = this.value;
            const previewField = document.getElementById("poNumberPreview");

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
    }

    // =========================
    // DROPZONE CLICK
    // =========================
    dropzone.addEventListener("click", () => fileInput.click());

    // =========================
    // FILE SELECT
    // =========================
    fileInput.addEventListener("change", (e) => {
        handleFiles(e.target.files);
    });

    // =========================
    // DRAG EVENTS
    // =========================
    dropzone.addEventListener("dragover", (e) => {
        e.preventDefault();
        dropzone.classList.add("bg-light");
    });

    dropzone.addEventListener("dragleave", () => {
        dropzone.classList.remove("bg-light");
    });

    dropzone.addEventListener("drop", (e) => {
        e.preventDefault();
        dropzone.classList.remove("bg-light");
        handleFiles(e.dataTransfer.files);
    });

    // =========================
    // HANDLE FILES
    // =========================
    function handleFiles(files) {

        for (let file of files) {

            if (file.size > 5 * 1024 * 1024) {
                alert("File terlalu besar! Max 5MB per gambar.");
                continue;
            }

            filesArray.push(file);
        }

        renderPreview();
        updateInputFiles();
    }

    // =========================
    // RENDER PREVIEW
    // =========================
    function renderPreview() {

        preview.innerHTML = "";

        filesArray.forEach((file, index) => {

            const reader = new FileReader();

            reader.onload = function(e) {

                const sizeMB = (file.size / (1024 * 1024)).toFixed(2);

                const col = document.createElement("div");
                col.classList.add("col-md-3");

                col.innerHTML = `
                    <div class="card shadow-sm position-relative">

                        <img src="${e.target.result}"
                             class="card-img-top"
                             style="height:120px; object-fit:cover;">

                        <div class="card-body p-2">
                            <small class="fw-bold d-block text-truncate">
                                ${file.name}
                            </small>
                            <small class="text-muted">
                                ${sizeMB} MB
                            </small>
                        </div>

                        <button type="button"
                                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
                                data-index="${index}">
                            ✕
                        </button>

                    </div>
                `;

                preview.appendChild(col);
            };

            reader.readAsDataURL(file);
        });
    }

    // =========================
    // REMOVE FILE (EVENT DELEGATION)
    // =========================
    preview.addEventListener("click", function(e) {

        if (e.target.dataset.index !== undefined) {

            const index = e.target.dataset.index;
            filesArray.splice(index, 1);

            renderPreview();
            updateInputFiles();
        }

    });

    // =========================
    // UPDATE INPUT FILES
    // =========================
    function updateInputFiles() {

        const dataTransfer = new DataTransfer();

        filesArray.forEach(file => {
            dataTransfer.items.add(file);
        });

        fileInput.files = dataTransfer.files;
    }

    // =========================
    // FORM SUBMIT WITH PROGRESS
    // =========================
    if (form && progressWrapper && progressBar) {

        form.addEventListener("submit", function(e) {

            if (filesArray.length === 0) return;

            e.preventDefault();

            const formData = new FormData(form);

            progressWrapper.style.display = "block";

            const xhr = new XMLHttpRequest();
            xhr.open("POST", form.action, true);

            xhr.upload.addEventListener("progress", function(e) {

                if (e.lengthComputable) {

                    const percent = Math.round((e.loaded / e.total) * 100);

                    progressBar.style.width = percent + "%";
                    progressBar.innerText = percent + "%";
                }
            });

            xhr.onload = function() {

                if (xhr.status === 200) {

                    progressBar.classList.remove("progress-bar-animated");
                    progressBar.classList.add("bg-success");
                    progressBar.innerText = "Upload selesai ✔";

                    setTimeout(() => {
                        window.location.href = "{{ route('purchase-orders.index') }}";
                    }, 800);
                }

            };

            xhr.send(formData);

        });
    }

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