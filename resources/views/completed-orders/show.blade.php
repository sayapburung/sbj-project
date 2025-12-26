<!-- resources/views/completed-orders/show.blade.php -->
@extends('layouts.app')

@section('title', 'Order Detail - ' . $order->po_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-file-alt"></i> Order Detail</h2>
        <p class="text-muted mb-0">{{ $order->po_number }}</p>
    </div>
    <div>
        <a href="{{ route('completed-orders.pdf', $order->id) }}" class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
        <a href="{{ route('completed-orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <small class="text-muted d-block">Total Duration</small>
                <h2 class="mb-0">{{ $summary['total_duration'] }}</h2>
                <small class="text-muted">days</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <small class="text-muted d-block">Total Rejections</small>
                <h2 class="mb-0 {{ $summary['total_rejections'] > 0 ? 'text-warning' : 'text-success' }}">
                    {{ $summary['total_rejections'] }}
                </h2>
                <small class="text-muted">times</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <small class="text-muted d-block">Total Images</small>
                <h2 class="mb-0">{{ $summary['total_images'] }}</h2>
                <small class="text-muted">files</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <small class="text-muted d-block">History Records</small>
                <h2 class="mb-0">{{ $summary['total_history'] }}</h2>
                <small class="text-muted">events</small>
            </div>
        </div>
    </div>
</div>

<!-- Order Information & Production Data -->
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Order Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th width="40%">PO Number</th>
                        <td><strong>{{ $order->po_number }}</strong></td>
                    </tr>
                    <tr>
                        <th>Customer</th>
                        <td>{{ $order->nama_konsumen }}</td>
                    </tr>
                    <tr>
                        <th>Jenis PO</th>
                        <td>{{ $order->jenis_po }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Bahan</th>
                        <td>{{ $order->jenis_bahan }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah</th>
                        <td>{{ $order->jumlah }}</td>
                    </tr>
                    <tr>
                        <th>Meteran Order</th>
                        <td>{{ $order->meteran }} m</td>
                    </tr>
                    <tr>
                        <th>Tanggal Order</th>
                        <td>{{ $order->tanggal_order->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Deadline</th>
                        <td>{{ $order->deadline->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Created By</th>
                        <td>{{ $order->creator->name }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $order->created_at->format('d F Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Completed At</th>
                        <td><strong class="text-success">{{ $order->updated_at->format('d F Y H:i') }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <!-- Production Data -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-industry"></i> Production Data</h5>
            </div>
            <div class="card-body">
                @if($order->stageInputs)
                <table class="table table-sm table-borderless">
                    @if($order->stageInputs->meteran_desain)
                    <tr>
                        <th width="50%">Meteran Desain</th>
                        <td><strong>{{ $order->stageInputs->meteran_desain }} m</strong></td>
                    </tr>
                    @endif
                    @if($order->stageInputs->meteran_printing)
                    <tr>
                        <th>Meteran Printing</th>
                        <td><strong>{{ $order->stageInputs->meteran_printing }} m</strong></td>
                    </tr>
                    @endif
                    @if($order->stageInputs->kiloan)
                    <tr>
                        <th>Kiloan</th>
                        <td><strong>{{ $order->stageInputs->kiloan }} kg</strong></td>
                    </tr>
                    @endif
                    @if($order->stageInputs->meteran_press)
                    <tr>
                        <th>Meteran Press</th>
                        <td><strong>{{ $order->stageInputs->meteran_press }} m</strong></td>
                    </tr>
                    @endif
                </table>
                @else
                <p class="text-muted mb-0 text-center py-3">
                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                    No production data recorded
                </p>
                @endif
            </div>
        </div>

        <!-- Stage Duration -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-clock"></i> Stage Duration</h5>
            </div>
            <div class="card-body">
                @if(count($stageDurations) > 0)
                <table class="table table-sm table-borderless">
                    @foreach($stageDurations as $stage => $duration)
                    <tr>
                        <th width="50%">{{ ucfirst(str_replace('_', ' ', $stage)) }}</th>
                        <td>
                            <strong>{{ $duration['days'] }} days</strong>
                            <br><small class="text-muted">({{ $duration['hours'] }} hours)</small>
                        </td>
                    </tr>
                    @endforeach
                </table>
                @else
                <p class="text-muted mb-0 text-center py-3">
                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                    Duration data not available
                </p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- QC Rejections -->
@if($order->qcRejections->count() > 0)
<div class="card mb-4">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0">
            <i class="fas fa-exclamation-triangle"></i> QC Rejection History 
            <span class="badge bg-dark">{{ $order->qcRejections->count() }}</span>
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>From Stage</th>
                        <th>Reason</th>
                        <th>Severity</th>
                        <th>Rejected By</th>
                        <th>Status</th>
                        <th>Duration</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->qcRejections as $rejection)
                    <tr>
                        <td>{{ $rejection->rejected_at->format('d/m/Y H:i') }}</td>
                        <td><span class="badge bg-secondary">{{ $rejection->stage_label }}</span></td>
                        <td><strong>{{ $rejection->rejection_reason }}</strong></td>
                        <td><span class="badge bg-{{ $rejection->severity_color }}">{{ $rejection->severity_label }}</span></td>
                        <td>{{ $rejection->rejectedBy->name }}</td>
                        <td>
                            @if($rejection->is_resolved)
                            <span class="badge bg-success">✓ Resolved</span>
                            @else
                            <span class="badge bg-danger">Unresolved</span>
                            @endif
                        </td>
                        <td>{{ $rejection->duration }}</td>
                    </tr>
                    @if($rejection->rejection_notes)
                    <tr>
                        <td colspan="7" class="bg-light">
                            <small><strong>Notes:</strong> {{ $rejection->rejection_notes }}</small>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Images -->
@if($order->images->count() > 0)
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-images"></i> Images 
            <span class="badge bg-light text-dark">{{ $order->images->count() }}</span>
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @foreach($order->images as $image)
            <div class="col-md-3">
                <div class="card h-100">
                    <a href="{{ asset('storage/'.$image->image_path) }}" target="_blank">
                        <img src="{{ asset('storage/'.$image->image_path) }}" class="card-img-top" 
                             style="height: 200px; object-fit: cover;">
                    </a>
                    <div class="card-body p-2">
                        <small class="d-block text-truncate" title="{{ $image->original_name }}">
                            <strong>{{ $image->original_name }}</strong>
                        </small>
                        <span class="badge bg-{{ $image->getBadgeColor() }} mt-1">
                            {{ $image->getStageLabel() }}
                        </span>
                        @if($image->uploader)
                        <small class="d-block text-muted mt-1">
                            <i class="fas fa-user"></i> {{ $image->uploader->name }}
                        </small>
                        @endif
                        @if($image->description)
                        <small class="d-block text-muted mt-1 fst-italic">
                            "{{ $image->description }}"
                        </small>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Full History Timeline -->
<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">
            <i class="fas fa-history"></i> Complete History Timeline 
            <span class="badge bg-light text-dark">{{ $order->histories->count() }}</span>
        </h5>
    </div>
    <div class="card-body">
        <x-order-history-timeline :histories="$order->histories" />
    </div>
</div>
@endsection