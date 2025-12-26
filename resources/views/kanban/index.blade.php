@extends('layouts.app')

@section('title', 'Kanban Board')

@push('styles')
<style>
    .kanban-container {
        display: flex;
        gap: 15px;
        overflow-x: auto;
        padding-bottom: 20px;
    }
    .kanban-column {
        min-width: 300px;
        flex: 1;
    }
    .kanban-header {
        font-weight: bold;
        padding: 12px;
        border-radius: 8px 8px 0 0;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .kanban-body {
        min-height: 400px;
        max-height: 70vh;
        overflow-y: auto;
        padding: 10px;
    }
    .kanban-card {
        background: white;
        border-radius: 6px;
        padding: 12px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.2s;
        border-left: 3px solid;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .kanban-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }
    .kanban-card-title {
        font-weight: bold;
        margin-bottom: 5px;
        font-size: 14px;
    }
    .kanban-card-meta {
        font-size: 12px;
        color: #666;
        margin-bottom: 5px;
    }
    .kanban-images {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
        margin-top: 8px;
    }
    .kanban-images img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 4px;
    }
    .modal-images {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .modal-images img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 4px;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-columns"></i> Kanban Board</h2>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('kanban.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="nama_konsumen" class="form-control" placeholder="Nama Konsumen" value="{{ request('nama_konsumen') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="jenis_po" class="form-control" placeholder="Jenis PO" value="{{ request('jenis_po') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="deadline_from" class="form-control" value="{{ request('deadline_from') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="deadline_to" class="form-control" value="{{ request('deadline_to') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="kanban-container">
    <!-- Waiting List -->
    <div class="kanban-column">
        <div class="kanban-header" style="background-color: #6c757d;">
            <span>Waiting List</span>
            <span class="badge bg-light text-dark">{{ $kanbanData['waiting_list']->count() }}</span>
        </div>
        <div class="kanban-column kanban-body">
            @foreach($kanbanData['waiting_list'] as $order)
                <div class="kanban-card" style="border-left-color: #6c757d;" data-id="{{ $order->id }}" onclick="showDetail({{ $order->id }})">
                    <div class="kanban-card-title">{{ $order->po_number }}</div>
                    <div class="kanban-card-meta">
                        <i class="fas fa-user"></i> {{ $order->nama_konsumen }}
                    </div>
                    <div class="kanban-card-meta">
                        <i class="fas fa-calendar"></i> {{ $order->deadline->format('d/m/Y') }}
                    </div>
                    <div class="kanban-card-meta">
                        <i class="fas fa-tag"></i> {{ $order->jenis_po }}
                    </div>
                    @if($order->images->count() > 0)
                    <div class="kanban-images">
                        @foreach($order->images->take(3) as $image)
                        <img src="{{ asset('storage/'.$image->image_path) }}" alt="Img">
                        @endforeach
                        @if($order->images->count() > 3)
                        <div style="width: 40px; height: 40px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 4px; font-size: 11px;">
                            +{{ $order->images->count() - 3 }}
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Desain -->
    <div class="kanban-column">
        <div class="kanban-header" style="background-color: #17a2b8;">
            <span>Desain</span>
            <span class="badge bg-light text-dark">{{ $kanbanData['desain']->count() }}</span>
        </div>
        <div class="kanban-column kanban-body">
            @foreach($kanbanData['desain'] as $order)
                <div class="kanban-card" style="border-left-color: #17a2b8;" data-id="{{ $order->id }}" onclick="showDetail({{ $order->id }})">
                    <div class="kanban-card-title">{{ $order->po_number }}</div>
                    <div class="kanban-card-meta">
                        <i class="fas fa-user"></i> {{ $order->nama_konsumen }}
                    </div>
                    <div class="kanban-card-meta">
                        <i class="fas fa-calendar"></i> {{ $order->deadline->format('d/m/Y') }}
                    </div>
                    <span class="badge" style="background-color: {{ $order->stage_status == 'selesai' ? '#28a745' : ($order->stage_status == 'progress' ? '#ffc107' : '#6c757d') }}">
                        {{ ucfirst($order->stage_status) }}
                    </span>
                    @if($order->images->count() > 0)
                    <div class="kanban-images">
                        @foreach($order->images->take(3) as $image)
                        <img src="{{ asset('storage/'.$image->image_path) }}" alt="Img">
                        @endforeach
                        @if($order->images->count() > 3)
                        <div style="width: 40px; height: 40px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 4px; font-size: 11px;">
                            +{{ $order->images->count() - 3 }}
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Printing -->
    <div class="kanban-column">
        <div class="kanban-header" style="background-color: #007bff;">
            <span>Printing</span>
            <span class="badge bg-light text-dark">{{ $kanbanData['printing']->count() }}</span>
        </div>
        <div class="kanban-column kanban-body">
            @foreach($kanbanData['printing'] as $order)
                <div class="kanban-card" style="border-left-color: #007bff;" data-id="{{ $order->id }}" onclick="showDetail({{ $order->id }})">
                    <div class="kanban-card-title">{{ $order->po_number }}</div>
                    <div class="kanban-card-meta">
                        <i class="fas fa-user"></i> {{ $order->nama_konsumen }}
                    </div>
                    <div class="kanban-card-meta">
                        <i class="fas fa-calendar"></i> {{ $order->deadline->format('d/m/Y') }}
                    </div>
                    <span class="badge" style="background-color: {{ $order->stage_status == 'selesai' ? '#28a745' : ($order->stage_status == 'progress' ? '#ffc107' : '#6c757d') }}">
                        {{ ucfirst($order->stage_status) }}
                    </span>
                    @if($order->images->count() > 0)
                    <div class="kanban-images">
                        @foreach($order->images->take(3) as $image)
                        <img src="{{ asset('storage/'.$image->image_path) }}" alt="Img">
                        @endforeach
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Press -->
    <div class="kanban-column">
        <div class="kanban-header" style="background-color: #ffc107; color: #000;">
            <span>Press</span>
            <span class="badge bg-dark">{{ $kanbanData['press']->count() }}</span>
        </div>
        <div class="kanban-column kanban-body">
            @foreach($kanbanData['press'] as $order)
                <div class="kanban-card" style="border-left-color: #ffc107;" data-id="{{ $order->id }}" onclick="showDetail({{ $order->id }})">
                    <div class="kanban-card-title">{{ $order->po_number }}</div>
                    <div class="kanban-card-meta">
                        <i class="fas fa-user"></i> {{ $order->nama_konsumen }}
                    </div>
                    <div class="kanban-card-meta">
                        <i class="fas fa-calendar"></i> {{ $order->deadline->format('d/m/Y') }}
                    </div>
                    <span class="badge" style="background-color: {{ $order->stage_status == 'selesai' ? '#28a745' : ($order->stage_status == 'progress' ? '#ffc107' : '#6c757d') }}">
                        {{ ucfirst($order->stage_status) }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- QC -->
    <div class="kanban-column">
        <div class="kanban-header" style="background-color: #343a40;">
            <span>QC</span>
            <span class="badge bg-light text-dark">{{ $kanbanData['qc']->count() }}</span>
        </div>
        <div class="kanban-column kanban-body">
            @foreach($kanbanData['qc'] as $order)
                <div class="kanban-card" style="border-left-color: #343a40;" data-id="{{ $order->id }}" onclick="showDetail({{ $order->id }})">
                    <div class="kanban-card-title">{{ $order->po_number }}</div>
                    <div class="kanban-card-meta">
                        <i class="fas fa-user"></i> {{ $order->nama_konsumen }}
                    </div>
                    <div class="kanban-card-meta">
                        <i class="fas fa-calendar"></i> {{ $order->deadline->format('d/m/Y') }}
                    </div>
                    <span class="badge" style="background-color: {{ $order->stage_status == 'approved' ? '#28a745' : ($order->stage_status == 'rejected' ? '#dc3545' : '#6c757d') }}">
                        {{ ucfirst($order->stage_status) }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Pengiriman -->
    <div class="kanban-column">
        <div class="kanban-header" style="background-color: #6f42c1;">
            <span>Pengiriman</span>
            <span class="badge bg-light text-dark">{{ $kanbanData['pengiriman']->count() }}</span>
        </div>
        <div class="kanban-column kanban-body">
            @foreach($kanbanData['pengiriman'] as $order)
                <div class="kanban-card" style="border-left-color: #6f42c1;" data-id="{{ $order->id }}" onclick="showDetail({{ $order->id }})">
                    <div class="kanban-card-title">{{ $order->po_number }}</div>
                    <div class="kanban-card-meta">
                        <i class="fas fa-user"></i> {{ $order->nama_konsumen }}
                    </div>
                    <div class="kanban-card-meta">
                        <i class="fas fa-calendar"></i> {{ $order->deadline->format('d/m/Y') }}
                    </div>
                    <span class="badge" style="background-color: {{ $order->stage_status == 'shipped' ? '#28a745' : ($order->stage_status == 'ready' ? '#ffc107' : '#6c757d') }}">
                        {{ ucfirst($order->stage_status) }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showDetail(id) {
    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    modal.show();
    
    fetch(`/kanban/${id}`)
        .then(res => res.json())
        .then(data => {
            let html = `
                <ul class="nav nav-tabs" id="detailTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button">
                            <i class="fas fa-info-circle"></i> Info Order
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button">
                            <i class="fas fa-history"></i> History (${data.histories.length})
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content mt-3" id="detailTabsContent">
                    <!-- Tab Info -->
                    <div class="tab-pane fade show active" id="info" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><strong>No PO:</strong> ${data.po_number}</h6>
                                <p><strong>Konsumen:</strong> ${data.nama_konsumen}</p>
                                <p><strong>Jenis PO:</strong> ${data.jenis_po}</p>
                                <p><strong>Jenis Bahan:</strong> ${data.jenis_bahan}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Jumlah:</strong> ${data.jumlah}</p>
                                <p><strong>Meteran:</strong> ${data.meteran}</p>
                                <p><strong>Tanggal Order:</strong> ${new Date(data.tanggal_order).toLocaleDateString('id-ID')}</p>
                                <p><strong>Deadline:</strong> ${new Date(data.deadline).toLocaleDateString('id-ID')}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <p><strong>Status:</strong> 
                                    <span class="badge bg-primary">${data.current_stage}</span> 
                                    <span class="badge bg-info">${data.stage_status}</span>
                                </p>
                            </div>
                        </div>
            `;
            
            if (data.stage_inputs) {
                html += '<hr><h6>Data Input Stage:</h6>';
                if (data.stage_inputs.kiloan) html += `<p><strong>Kiloan:</strong> ${data.stage_inputs.kiloan}</p>`;
                if (data.stage_inputs.meteran_press) html += `<p><strong>Meteran Press:</strong> ${data.stage_inputs.meteran_press}</p>`;
                if (data.stage_inputs.meteran_printing) html += `<p><strong>Meteran Printing:</strong> ${data.stage_inputs.meteran_printing}</p>`;
            }
            
            if (data.images && data.images.length > 0) {
                html += '<hr><h6>Gambar Desain:</h6><div class="modal-images">';
                data.images.forEach(img => {
                    html += `<a href="/storage/${img.image_path}" target="_blank"><img src="/storage/${img.image_path}" alt="${img.original_name}"></a>`;
                });
                html += '</div>';
            }
            
            if (data.file) {
                html += `<hr><p><strong>File:</strong> <a href="/storage/${data.file}" target="_blank" class="btn btn-sm btn-primary"><i class="fas fa-download"></i> Download File</a></p>`;
            }
            
            html += `</div><!-- End Tab Info -->
                    
                    <!-- Tab History -->
                    <div class="tab-pane fade" id="history" role="tabpanel">
                        <div class="order-history-timeline">`;
            
            if (data.histories && data.histories.length > 0) {
                data.histories.forEach(h => {
                    const stageColors = {
                        'waiting_list': 'secondary',
                        'desain': 'info',
                        'printing': 'primary',
                        'press': 'warning',
                        'qc': 'dark',
                        'pengiriman': 'purple',
                        'selesai': 'success'
                    };
                    const color = stageColors[h.to_stage] || 'secondary';
                    
                    html += `<div class="timeline-item">
                        <div class="timeline-marker bg-${color}"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>`;
                    
                    if (h.from_stage && h.from_stage !== h.to_stage) {
                        html += `<strong class="text-primary"><i class="fas fa-arrow-right"></i> Pindah Stage</strong>
                                <p class="mb-1">
                                    <span class="badge bg-secondary">${h.from_stage}</span>
                                    <i class="fas fa-arrow-right mx-1"></i>
                                    <span class="badge bg-${color}">${h.to_stage}</span>
                                </p>`;
                    } else {
                        html += `<strong class="text-info"><i class="fas fa-sync-alt"></i> Update Status</strong>
                                <p class="mb-1">
                                    <span class="badge bg-light text-dark">${h.from_status}</span>
                                    <i class="fas fa-arrow-right mx-1"></i>
                                    <span class="badge bg-primary">${h.to_status}</span>
                                    di stage <strong>${h.to_stage}</strong>
                                </p>`;
                    }
                    
                    if (h.notes) {
                        html += `<p class="text-muted small mb-0"><i class="fas fa-comment"></i> ${h.notes}</p>`;
                    }
                    
                    const createdDate = new Date(h.created_at);
                    html += `</div>
                                <small class="text-muted">${createdDate.toLocaleString('id-ID')}</small>
                            </div>
                            <small class="text-muted"><i class="fas fa-user"></i> ${h.user.name}</small>
                        </div>
                    </div>`;
                });
            } else {
                html += `<div class="alert alert-info"><i class="fas fa-info-circle"></i> Belum ada history</div>`;
            }
            
            html += `</div></div><!-- End Tab History -->
                </div><!-- End Tab Content -->`;
            
            document.getElementById('modalContent').innerHTML = html;
        })
        .catch(err => {
            document.getElementById('modalContent').innerHTML = '<div class="alert alert-danger">Gagal memuat data</div>';
        });
}
</script>

@endpush