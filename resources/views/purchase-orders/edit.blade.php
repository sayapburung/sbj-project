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
<form method="POST"
      action="{{ route('purchase-orders.update', $order->id) }}"
      enctype="multipart/form-data">
@csrf
@method('PUT')

{{-- NOMOR PO --}}
<div class="mb-3">
<label class="form-label fw-bold">Nomor PO</label>
<input type="text"
       class="form-control"
       value="{{ $order->po_number }}"
       readonly>
</div>

{{-- NAMA KONSUMEN --}}
<div class="mb-3">
<label class="form-label">Nama Konsumen *</label>
<input type="text"
       name="nama_konsumen"
       class="form-control"
       value="{{ old('nama_konsumen', $order->nama_konsumen) }}"
       required>
</div>

{{-- NAMA PO --}}
<div class="mb-3">
<label class="form-label">Nama PO *</label>
<input type="text"
       name="nama_po"
       class="form-control"
       value="{{ old('nama_po', $order->nama_po) }}"
       required>
</div>

<!-- {{-- JENIS PO --}}
<div class="mb-3">
<label class="form-label fw-bold">Jenis PO *</label>
<select name="jenis_po_id" class="form-control" required>
<option value="">— Pilih Jenis PO —</option>
@foreach($jenisPos->groupBy('kategori') as $kategori => $items)
<optgroup label="{{ $kategori }}">
@foreach($items as $item)
<option value="{{ $item->id }}"
{{ old('jenis_po_id', $order->jenis_po_id) == $item->id ? 'selected' : '' }}>
{{ $item->nama }}
</option>
@endforeach
</optgroup>
@endforeach
</select>
</div> -->

{{-- JENIS BAHAN --}}
<div class="mb-3">
<label class="form-label">Jenis Bahan *</label>
<input type="text"
       name="jenis_bahan"
       class="form-control"
       value="{{ old('jenis_bahan', $order->jenis_bahan) }}"
       required>
</div>

{{-- JUMLAH & METERAN --}}
<div class="row">
<div class="col-md-6 mb-3">
<label class="form-label">Jumlah</label>
<input type="number"
       name="jumlah"
       min="0"
       class="form-control"
       value="{{ old('jumlah', $order->jumlah) }}">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Meteran</label>
<input type="number"
       step="0.01"
       name="meteran"
       min="0"
       class="form-control"
       value="{{ old('meteran', $order->meteran) }}">
</div>
</div>

{{-- TANGGAL --}}
<div class="row">
<div class="col-md-6 mb-3">
<label class="form-label">Tanggal Order *</label>
<input type="date"
       name="tanggal_order"
       class="form-control"
       value="{{ old('tanggal_order', $order->tanggal_order->format('Y-m-d')) }}"
       required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Deadline *</label>
<input type="date"
       name="deadline"
       class="form-control"
       value="{{ old('deadline', $order->deadline->format('Y-m-d')) }}"
       required>
</div>
</div>

{{-- GAMBAR SAAT INI --}}
@if($order->images->count() > 0)
<div class="mb-3">
<label class="form-label">Gambar Saat Ini</label>
<div class="d-flex gap-2 flex-wrap">
@foreach($order->images as $image)
<div class="position-relative">
<img src="{{ asset('storage/'.$image->image_path) }}"
     style="width:100px;height:100px;object-fit:cover;border-radius:4px;">
<button type="button"
        class="btn btn-danger btn-sm position-absolute top-0 end-0"
        onclick="if(confirm('Hapus gambar ini?')) {
            document.getElementById('delete-image-{{ $image->id }}').submit();
        }">
✕
</button>
</div>
@endforeach
</div>
</div>
@endif

{{-- DROPZONE MODERN --}}
<div class="mb-3">
<label class="form-label">Tambah Gambar Desain</label>

<div id="dropzone"
class="border border-2 border-dashed rounded p-4 text-center"
style="cursor:pointer; background:#fafafa;">
<p class="mb-1">📌 Drag & Drop gambar disini</p>
<small class="text-muted">atau klik untuk pilih file</small>

<input type="file"
id="fileInput"
name="images[]"
multiple
accept="image/*"
hidden>
</div>

<small class="text-muted d-block mt-2">
Max per file: 5MB
</small>

<div id="preview" class="row mt-3 g-2"></div>

<div class="progress mt-3"
style="height:20px; display:none;"
id="uploadProgressWrapper">
<div id="uploadProgressBar"
class="progress-bar progress-bar-striped progress-bar-animated"
style="width:0%">0%</div>
</div>

</div>

{{-- BUTTON --}}
<div class="d-flex gap-2">
<button type="submit" class="btn btn-primary">
<i class="fas fa-save"></i> Update
</button>
<a href="{{ route('purchase-orders.index') }}"
class="btn btn-secondary">
<i class="fas fa-arrow-left"></i> Kembali
</a>
</div>

</form>
</div>
</div>

{{-- HISTORY --}}
<div class="card mt-4">
<div class="card-header bg-info text-white">
<h5 class="mb-0">
<i class="fas fa-history"></i> History Transisi Order
</h5>
</div>
<div class="card-body">
<x-order-history-timeline :histories="$order->histories" />
</div>
</div>

</div>
</div>

{{-- DELETE IMAGE FORM --}}
@foreach($order->images as $image)
<form id="delete-image-{{ $image->id }}"
action="{{ route('purchase-orders.delete-image', $image->id) }}"
method="POST"
style="display:none;">
@csrf
@method('DELETE')
</form>
@endforeach


<script>
document.addEventListener("DOMContentLoaded", function(){

const dropzone = document.getElementById("dropzone");
const fileInput = document.getElementById("fileInput");
const preview = document.getElementById("preview");
const form = document.querySelector("form");
const progressWrapper = document.getElementById("uploadProgressWrapper");
const progressBar = document.getElementById("uploadProgressBar");

let filesArray = [];

dropzone.addEventListener("click", () => fileInput.click());

fileInput.addEventListener("change", (e)=>{
handleFiles(e.target.files);
});

dropzone.addEventListener("dragover", (e)=>{
e.preventDefault();
dropzone.classList.add("bg-light");
});

dropzone.addEventListener("dragleave", ()=>{
dropzone.classList.remove("bg-light");
});

dropzone.addEventListener("drop", (e)=>{
e.preventDefault();
dropzone.classList.remove("bg-light");
handleFiles(e.dataTransfer.files);
});

function handleFiles(files){
for(let file of files){
if(file.size > 5 * 1024 * 1024){
alert("File terlalu besar! Max 5MB.");
continue;
}
filesArray.push(file);
}
renderPreview();
updateInputFiles();
}

function renderPreview(){
preview.innerHTML = "";
filesArray.forEach((file,index)=>{
const reader = new FileReader();
reader.onload = function(e){
const sizeMB = (file.size/(1024*1024)).toFixed(2);
preview.innerHTML += `
<div class="col-md-3">
<div class="card shadow-sm position-relative">
<img src="${e.target.result}"
class="card-img-top"
style="height:120px;object-fit:cover;">
<div class="card-body p-2">
<small class="fw-bold d-block text-truncate">${file.name}</small>
<small class="text-muted">${sizeMB} MB</small>
</div>
<button type="button"
class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
onclick="removeFile(${index})">
✕
</button>
</div>
</div>
`;
};
reader.readAsDataURL(file);
});
}

window.removeFile = function(index){
filesArray.splice(index,1);
renderPreview();
updateInputFiles();
}

function updateInputFiles(){
const dataTransfer = new DataTransfer();
filesArray.forEach(file=>{
dataTransfer.items.add(file);
});
fileInput.files = dataTransfer.files;
}

form.addEventListener("submit", function(e){

if(filesArray.length === 0) return;

e.preventDefault();

const formData = new FormData(form);

progressWrapper.style.display = "block";

const xhr = new XMLHttpRequest();
xhr.open("POST", form.action, true);

xhr.upload.addEventListener("progress", function(e){
if(e.lengthComputable){
const percent = Math.round((e.loaded/e.total)*100);
progressBar.style.width = percent + "%";
progressBar.innerText = percent + "%";
}
});

xhr.onload = function(){
if(xhr.status === 200){
progressBar.classList.remove("progress-bar-animated");
progressBar.classList.add("bg-success");
progressBar.innerText = "Upload selesai ✔";

setTimeout(()=>{
window.location.href = "{{ route('purchase-orders.index') }}";
},800);
}
};

xhr.send(formData);

});

});
</script>

@endsection