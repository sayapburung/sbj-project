<!-- resources/views/stages/desain.blade.php - UPDATE LENGKAP -->
@extends('layouts.app')

@section('title', 'Desain')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-paint-brush"></i> Desain / Layout</h2>
    <span class="badge bg-info fs-6">{{ $orders->total() }} Orders</span>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('desain.index') }}">
            <div class="input-group">
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="Cari Data"
                       value="{{ request('search') }}">
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Cari
                </button>

                @if(request('search'))
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="row">
    @forelse($orders as $order)
    <div class="col-md-6 mb-4">
        <div class="card stage-card" style="border-left-color: #17a2b8;">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $order->po_number }}</strong>
                    <br><small>{{ $order->nama_konsumen }}</small>
                </div>
                @if($order->stage_status == 'selesai')
                    <span class="badge bg-success">Selesai</span>
                @elseif($order->stage_status == 'progress')
                    <span class="badge bg-warning text-dark">Progress</span>
                @elseif($order->stage_status == 'start')
                    <span class="badge bg-primary">Start</span>
                @else
                    <span class="badge bg-secondary">Pending</span>
                @endif
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Jenis PO</small>
                        <p class="mb-0"><strong>{{ $order->jenis_po }}</strong></p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Deadline</small>
                        <p class="mb-0">
                            <strong class="text-danger">
                                <i class="fas fa-calendar"></i> {{ $order->deadline->format('d/m/Y') }}
                            </strong>
                        </p>
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Jenis Bahan</small>
                    <p class="mb-0">{{ $order->jenis_bahan }}</p>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Jumlah</small>
                        <p class="mb-0"><strong>{{ $order->jumlah }}</strong></p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Meteran Order</small>
                        <p class="mb-0"><strong>{{ $order->meteran }}</strong></p>
                    </div>
                </div>

                <!-- TAMPILKAN METERAN DESAIN -->
                @if($order->stageInputs && $order->stageInputs->meteran_desain)
                <div class="alert alert-info mb-3">
                    <i class="fas fa-ruler"></i> <strong>Meteran Desain:</strong> {{ $order->stageInputs->meteran_desain }} m
                </div>
                @endif

                <!-- GAMBAR DESAIN dengan filter by stage -->
                @if($order->images->count() > 0)
                <div class="mb-3">
                    <small class="text-muted">
                        <strong>Semua Gambar ({{ $order->images->count() }})</strong>
                    </small>
                    <div class="d-flex gap-2 flex-wrap mt-2">
                        @foreach($order->images as $image)
                        <div class="position-relative">
                            <a href="{{ asset('storage/'.$image->image_path) }}" target="_blank" title="{{ $image->original_name }}">
                                <img src="{{ asset('storage/'.$image->image_path) }}" alt="Gambar" 
                                     style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; 
                                            border: 2px solid {{ $image->uploaded_from_stage == 'desain' ? '#17a2b8' : '#ddd' }};">
                            </a>
                            <!-- Badge indicator dari stage mana -->
                            <span class="position-absolute top-0 start-0 badge bg-{{ $image->uploaded_from_stage == 'desain' ? 'info' : 'secondary' }}" 
                                  style="font-size: 8px;">
                                {{ $image->uploaded_from_stage == 'desain' ? 'Desain' : 'Admin' }}
                            </span>
                            <!-- Tombol hapus hanya untuk gambar yang diupload dari desain -->
                            @if($image->uploaded_from_stage == 'desain' && $image->uploaded_by == auth()->id())
                            <form method="POST" action="{{ route('desain.delete-image', $image->id) }}" 
                                  class="position-absolute top-0 end-0" 
                                  onsubmit="return confirm('Hapus gambar ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" style="padding: 2px 6px; font-size: 10px;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <hr>

                <!-- UPDATE STATUS dengan Upload & Meteran -->
                <form method="POST" action="{{ route('desain.update-status', $order->id) }}" enctype="multipart/form-data" class="mb-3">
                    @csrf
                    
                    <!-- INPUT METERAN DESAIN -->
                    <div class="mb-2">
                        <label class="form-label small"><strong><i class="fas fa-ruler"></i> Meteran Desain:</strong></label>
                        <input type="number" step="0.01" name="meteran_desain" class="form-control form-control-sm" 
                               value="{{ $order->stageInputs->meteran_desain ?? '' }}" 
                               placeholder="Masukkan meteran desain">
                        <small class="text-muted">Input meteran hasil perhitungan desain</small>
                    </div>

                    <!-- UPLOAD GAMBAR DESAIN -->
                    <div class="mb-2">
                        <label class="form-label small">
                            <strong><i class="fas fa-images"></i> Upload Gambar Desain:</strong>
                        </label>

                        <div class="desain-dropzone border border-2 border-dashed rounded p-3 text-center"
                            data-order="{{ $order->id }}"
                            style="cursor:pointer; background:#fafafa;">

                            <p class="mb-1 small">📌 Drag & Drop gambar disini</p>
                            <small class="text-muted">atau klik untuk pilih file</small>

                            <input type="file"
                                name="images[]"
                                class="desain-file-input"
                                data-order="{{ $order->id }}"
                                multiple
                                accept="image/*"
                                hidden>
                        </div>

                        <small class="text-muted d-block mt-1">
                            Max 5MB per file
                        </small>

                        <div class="text-danger small mt-1 file-error" 
                            data-order="{{ $order->id }}" 
                            style="display:none;"></div>

                        <div class="row mt-2 g-2 preview-container" 
                            data-order="{{ $order->id }}"></div>

                        @error('images.*')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- DESKRIPSI GAMBAR (OPSIONAL) -->
                    <div class="mb-2">
                        <label class="form-label small">Keterangan Gambar (Opsional):</label>
                        <input type="text" name="description" class="form-control form-control-sm" 
                               placeholder="Isi Keterangan">
                    </div>

                    <!-- UPDATE STATUS -->
                    <label class="form-label small"><strong>Update Status:</strong></label>
                    <div class="input-group">
                        <div class="d-flex gap-2">
                            <!-- Tombol START / PROGRESS -->
                            <button type="submit"
                                    name="stage_status"
                                    value="progress"
                                    class="btn btn-sm 
                                        {{ $order->stage_status == 'progress' ? 'btn-warning' : 'btn-outline-warning' }}">
                                <i class="fas fa-play"></i> Start
                            </button>

                            <!-- Tombol SELESAI -->
                            <button type="submit"
                                    name="stage_status"
                                    value="selesai"
                                    class="btn btn-sm 
                                        {{ $order->stage_status == 'selesai' ? 'btn-success' : 'btn-outline-success' }}">
                                <i class="fas fa-check"></i> Selesai
                            </button>

                        </div>
                        <!-- <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button> -->
                    </div>
                </form>

                <!-- MOVE TO STAGE -->
                @if($order->stage_status === 'selesai')
                <form method="POST" action="{{ route('desain.move', $order->id) }}">
                    @csrf
                    <label class="form-label small"><strong>Pindahkan ke Stage:</strong></label>
                    <div class="input-group">
                        <select name="next_stage" class="form-select form-select-sm" required>
                            <option value="">Pilih Stage</option>
                            <option value="printing">Printing</option>
                            <option value="press">Press</option>
                            <option value="qc">QC</option>
                            <option value="pengiriman">Pengiriman</option>
                            <option value="selesai">Selesai</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="fas fa-arrow-right"></i> Pindahkan
                        </button>
                    </div>
                </form>
                @else
                <div class="alert alert-warning mb-0 small">
                    <i class="fas fa-info-circle"></i> Status harus <strong>"Selesai"</strong> untuk memindahkan ke stage lain
                </div>
                @endif

                <!-- HISTORY COLLAPSE (OPSIONAL) -->
                @if($order->histories->count() > 0)
                <div class="mt-3">
                    <button class="btn btn-sm btn-outline-info w-100" type="button" data-bs-toggle="collapse" data-bs-target="#history{{ $order->id }}">
                        <i class="fas fa-history"></i> Lihat History ({{ $order->histories->count() }})
                    </button>
                    <div class="collapse mt-2" id="history{{ $order->id }}">
                        <div class="card card-body">
                            @foreach($order->histories->take(3) as $history)
                            <div class="d-flex justify-content-between align-items-start mb-2 pb-2 border-bottom">
                                <div class="small">
                                    @if($history->from_stage !== $history->to_stage)
                                    <strong>Pindah:</strong> {{ $history->from_stage_label }} → {{ $history->to_stage_label }}
                                    @else
                                    <strong>{{ $history->notes }}</strong>
                                    @endif
                                    <br>
                                    <span class="text-muted">{{ $history->user->name }}</span>
                                </div>
                                <small class="text-muted">{{ $history->created_at->format('d/m H:i') }}</small>
                            </div>
                            @endforeach
                            @if($order->histories->count() > 3)
                            <small class="text-muted">+{{ $order->histories->count() - 3 }} history lainnya</small>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="card-footer text-muted small">
                <i class="fas fa-clock"></i> Dibuat: {{ $order->created_at->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center">
            <i class="fas fa-inbox fa-3x mb-3"></i>
            <h5>Tidak ada order di stage Desain</h5>
            <p class="mb-0">Belum ada order yang masuk ke proses desain saat ini.</p>
        </div>
    </div>
    @endforelse
</div>

<div class="d-flex justify-content-center">
    {{ $orders->links() }}
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const allDropzones = document.querySelectorAll(".desain-dropzone");

    allDropzones.forEach(dropzone => {

        const orderId = dropzone.dataset.order;
        const fileInput = document.querySelector(`.desain-file-input[data-order="${orderId}"]`);
        const previewContainer = document.querySelector(`.preview-container[data-order="${orderId}"]`);
        const errorDiv = document.querySelector(`.file-error[data-order="${orderId}"]`);

        let filesArray = [];

        dropzone.addEventListener("click", () => fileInput.click());

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

        fileInput.addEventListener("change", (e) => {
            handleFiles(e.target.files);
        });

        function handleFiles(files) {

            errorDiv.style.display = "none";
            errorDiv.innerText = "";

            for (let file of files) {

                if (file.size > 5 * 1024 * 1024) {
                    errorDiv.innerText = "File terlalu besar! Maksimal 5MB.";
                    errorDiv.style.display = "block";
                    continue;
                }

                filesArray.push(file);
            }

            renderPreview();
            updateInputFiles();
        }

        function formatSize(bytes) {
            if (bytes < 1024) return bytes + " B";
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + " KB";
            return (bytes / (1024 * 1024)).toFixed(2) + " MB";
        }

        function renderPreview() {

            previewContainer.innerHTML = "";

            filesArray.forEach((file, index) => {

                const reader = new FileReader();

                reader.onload = function(e) {

                    const col = document.createElement("div");
                    col.classList.add("col-12");

                    col.innerHTML = `
                        <div class="card shadow-sm p-2">
                            <div class="d-flex gap-3 align-items-center">

                                <img src="${e.target.result}"
                                     style="width:70px; height:70px; object-fit:cover; border-radius:6px;">

                                <div class="flex-grow-1">
                                    <div class="fw-bold small">${file.name}</div>
                                    <div class="text-muted small">${formatSize(file.size)}</div>

                                    <div class="progress mt-2" style="height:6px;">
                                        <div class="progress-bar bg-info progress-bar-${orderId}-${index}" 
                                             style="width: 0%"></div>
                                    </div>
                                </div>

                                <button type="button"
                                        class="btn btn-danger btn-sm"
                                        onclick="removeDesainFile(${orderId}, ${index})">
                                    ✕
                                </button>

                            </div>
                        </div>
                    `;

                    previewContainer.appendChild(col);

                    simulateProgress(orderId, index);
                };

                reader.readAsDataURL(file);
            });
        }

        function simulateProgress(orderId, index) {

            let progress = 0;
            const bar = document.querySelector(`.progress-bar-${orderId}-${index}`);

            const interval = setInterval(() => {

                progress += 10;
                bar.style.width = progress + "%";

                if (progress >= 100) {
                    clearInterval(interval);
                }

            }, 100);
        }

        function updateInputFiles() {
            const dataTransfer = new DataTransfer();
            filesArray.forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;
        }

        window.removeDesainFile = function(orderId, index) {

            filesArray.splice(index, 1);
            renderPreview();
            updateInputFiles();
        };

    });

});
</script>
<style>
.desain-dropzone {
    transition: 0.2s;
}
.desain-dropzone:hover {
    background: #e9f7ff;
    border-color: #17a2b8;
}
.border-dashed {
    border-style: dashed !important;
}
</style>
@endsection