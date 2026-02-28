<!-- resources/views/stages/qc.blade.php - UPDATE LENGKAP dengan Rejection Form -->
@extends('layouts.app')

@section('title', 'QC')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-check-circle"></i> Quality Control</h2>
    <span class="badge bg-dark fs-6">{{ $orders->total() }} Orders</span>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('qc.index') }}">
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
        <div class="card stage-card" style="border-left-color: #343a40;">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $order->po_number }}</strong>
                    <br><small>{{ $order->nama_konsumen }}</small>
                </div>
                @if($order->stage_status == 'approved')
                    <span class="badge bg-success">Approved</span>
                @elseif($order->stage_status == 'rejected')
                    <span class="badge bg-danger">Rejected</span>
                @else
                    <span class="badge bg-secondary">Pending Review</span>
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

                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Jumlah</small>
                        <p class="mb-0"><strong>{{ $order->jumlah }}</strong></p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Jenis Bahan</small>
                        <p class="mb-0"><strong>{{ $order->jenis_bahan }}</strong></p>
                    </div>
                </div>

                <!-- DATA PRODUKSI -->
                @if($order->stageInputs)
                <div class="alert alert-secondary mb-3 small">
                    <strong><i class="fas fa-industry"></i> Data Produksi:</strong>
                    @if($order->stageInputs->meteran_desain)
                    <div><i class="fas fa-ruler"></i> Meteran Desain: {{ $order->stageInputs->meteran_desain }} m</div>
                    @endif
                    @if($order->stageInputs->kiloan)
                    <div><i class="fas fa-weight"></i> Kiloan: {{ $order->stageInputs->kiloan }} kg</div>
                    @endif
                    @if($order->stageInputs->meteran_press)
                    <div><i class="fas fa-ruler"></i> Meteran Press: {{ $order->stageInputs->meteran_press }} m</div>
                    @endif
                    @if($order->stageInputs->meteran_printing)
                    <div><i class="fas fa-ruler"></i> Meteran Printing: {{ $order->stageInputs->meteran_printing }} m</div>
                    @endif
                </div>
                @endif

                <!-- HISTORY REJECTION -->
                @if($order->qcRejections->count() > 0)
                <div class="alert alert-warning mb-3">
                    <strong><i class="fas fa-exclamation-triangle"></i> History Rejection:</strong>
                    @foreach($order->qcRejections->take(2) as $rejection)
                    <div class="small mt-1">
                        <span class="badge bg-{{ $rejection->severity_color }}">{{ $rejection->severity_label }}</span>
                        <strong>{{ $rejection->rejection_reason }}</strong>
                        @if($rejection->is_resolved)
                        <span class="badge bg-success">✓ Resolved</span>
                        @else
                        <span class="badge bg-danger">Unresolved</span>
                        @endif
                    </div>
                    @endforeach
                    @if($order->qcRejections->count() > 2)
                    <div class="small text-muted mt-1">+{{ $order->qcRejections->count() - 2 }} rejection lainnya</div>
                    @endif
                </div>
                @endif

                <!-- GAMBAR DESAIN -->
                @if($order->images->count() > 0)
                <div class="mb-3">
                    <small class="text-muted">Gambar untuk Review ({{ $order->images->count() }})</small>
                    <div class="d-flex gap-2 flex-wrap mt-2">
                        @foreach($order->images->take(6) as $image)
                        <a href="{{ asset('storage/'.$image->image_path) }}" target="_blank">
                            <img src="{{ asset('storage/'.$image->image_path) }}" alt="Gambar" 
                                 style="width: 70px; height: 70px; object-fit: cover; border-radius: 4px; border: 2px solid #343a40;">
                        </a>
                        @endforeach
                        @if($order->images->count() > 6)
                        <div class="d-flex align-items-center justify-content-center" 
                             style="width: 70px; height: 70px; background: #f0f0f0; border-radius: 4px; font-size: 11px;">
                            +{{ $order->images->count() - 6 }}
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <hr>

                <!-- UPDATE STATUS QC dengan Form Rejection -->
                <form method="POST" action="{{ route('qc.update-status', $order->id) }}" class="mb-3">
                    @csrf
                    <label class="form-label small"><strong>Status QC:</strong></label>
                    <div class="input-group mb-2">
                        <input type="hidden" name="stage_status" id="qcStatusInput{{ $order->id }}">
                    <div class="d-flex gap-2 flex-wrap">

    <!-- Pending -->
 <button type="submit"
        onclick="setQCStatus({{ $order->id }}, 'pending')"
        class="btn btn-sm btn-outline-secondary">
    ⏳ Pending
</button>

<button type="submit"
        onclick="setQCStatus({{ $order->id }}, 'approved')"
        class="btn btn-sm btn-outline-success">
    ✓ Approved
</button>

<button type="button"
        onclick="setQCStatus({{ $order->id }}, 'rejected')"
        class="btn btn-sm btn-outline-danger">
    ✗ Rejected
</button>

</div>
                    </div>

                    <!-- FORM REJECTION (Hidden by default) -->
                    <div id="rejectionForm{{ $order->id }}" style="display: {{ $order->stage_status == 'rejected' ? 'block' : 'none' }};">
                        <div class="card bg-light">
                            <div class="card-body p-2">
                                <label class="form-label small mb-1"><strong>Alasan Reject: *</strong></label>
                                <select name="rejection_reason" class="form-select form-select-sm mb-2">
                                    <option value="">Pilih Alasan</option>
                                    <option value="Warna tidak sesuai">Warna tidak sesuai</option>
                                    <option value="Ukuran tidak tepat">Ukuran tidak tepat</option>
                                    <option value="Cetakan buram">Cetakan buram</option>
                                    <option value="Material cacat">Material cacat</option>
                                    <option value="Desain salah">Desain salah</option>
                                    <option value="Finishing tidak rapi">Finishing tidak rapi</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>

                                <label class="form-label small mb-1"><strong>Severity: *</strong></label>
                                <select name="severity" class="form-select form-select-sm mb-2">
                                    <option value="low">Rendah - Minor issue</option>
                                    <option value="medium" selected>Sedang - Perlu perbaikan</option>
                                    <option value="high">Tinggi - Harus diperbaiki</option>
                                    <option value="critical">Kritis - Ulang dari awal</option>
                                </select>

                                <label class="form-label small mb-1">Catatan Detail:</label>
                                <textarea name="rejection_notes" class="form-control form-control-sm" rows="2" 
                                          placeholder="Detail masalah yang ditemukan..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger btn-sm">
                                            Kirim Reject
                                        </button>
                        </div>
                    </div>
                </form>

                <!-- MOVE TO STAGE -->
                @if($order->stage_status === 'approved' || $order->stage_status === 'rejected')
                <form method="POST" action="{{ route('qc.move', $order->id) }}">
                    @csrf
                    <label class="form-label small"><strong>Pindahkan ke Stage:</strong></label>
                    <div class="input-group">
                        <select name="next_stage" class="form-select form-select-sm" required>
                            <option value="">Pilih Stage</option>
                            <option value="desain">← Desain (Revisi)</option>
                            <option value="printing">← Printing (Revisi)</option>
                            <option value="press">← Press (Revisi)</option>
                            <option value="pengiriman">→ Pengiriman</option>
                            <option value="selesai">✓ Selesai</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="fas fa-arrow-right"></i> Pindahkan
                        </button>
                    </div>
                </form>
                @else
                <div class="alert alert-warning mb-0 small">
                    <i class="fas fa-info-circle"></i> Status harus <strong>"Diupdate"</strong> untuk memindahkan ke stage lain
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
            <h5>Tidak ada order di stage QC</h5>
            <p class="mb-0">Belum ada order yang masuk ke proses QC saat ini.</p>
        </div>
    </div>
    @endforelse
</div>

<div class="d-flex justify-content-center">
    {{ $orders->links() }}
</div>

@push('scripts')
<script>
function toggleRejectionForm(orderId) {

    const statusInput = document.getElementById('qcStatusInput' + orderId);
    const form = document.getElementById('rejectionForm' + orderId);

    if (!statusInput || !form) return;

    if (statusInput.value === 'rejected') {

        form.style.display = 'block';

        // Make fields required
        form.querySelectorAll('select[name="rejection_reason"], select[name="severity"]').forEach(el => {
            el.required = true;
        });

    } else {

        form.style.display = 'none';

        // Remove required
        form.querySelectorAll('select[name="rejection_reason"], select[name="severity"]').forEach(el => {
            el.required = false;
        });
    }
}
function setRejected(orderId) {

    const input = document.getElementById('qcStatusInput' + orderId);

    if (!input) return;

    input.value = 'rejected';

    toggleRejectionForm(orderId);
}
function setQCStatus(orderId, status) {

    const input = document.getElementById('qcStatusInput' + orderId);
    const form = document.getElementById('rejectionForm' + orderId);

    input.value = status;

    if (status === 'rejected') {
        form.style.display = 'block';

        form.querySelectorAll('select').forEach(el => {
            el.required = true;
        });

    } else {
        form.style.display = 'none';
    }
}
</script>
@endpush
@endsection