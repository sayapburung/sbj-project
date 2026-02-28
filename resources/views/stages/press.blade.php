@extends('layouts.app')

@section('title', 'Press')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-compress"></i> Press</h2>
    <span class="badge bg-warning text-dark fs-6">{{ $orders->total() }} Orders</span>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('press.index') }}">
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
        <div class="card stage-card" style="border-left-color: #ffc107;">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $order->po_number }}</strong>
                    <br><small>{{ $order->nama_konsumen }}</small>
                </div>
                @if($order->stage_status == 'selesai')
                    <span class="badge bg-success">Selesai</span>
                @elseif($order->stage_status == 'progress')
                    <span class="badge bg-primary">Progress</span>
                @elseif($order->stage_status == 'start')
                    <span class="badge bg-info">Start</span>
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

                @if($order->stageInputs)
                <div class="alert alert-info mb-3">
                    @if($order->stageInputs->kiloan)
                    <div><i class="fas fa-weight"></i> <strong>Kiloan:</strong> {{ $order->stageInputs->kiloan }} kg</div>
                    @endif
                    @if($order->stageInputs->meteran_press)
                    <div><i class="fas fa-ruler"></i> <strong>Meteran Press:</strong> {{ $order->stageInputs->meteran_press }} m</div>
                    @endif
                </div>
                @endif

                @if($order->images->count() > 0)
                <div class="mb-3">
                    <small class="text-muted">Gambar Desain ({{ $order->images->count() }})</small>
                    <div class="d-flex gap-2 flex-wrap mt-2">
                        @foreach($order->images->take(4) as $image)
                        <a href="{{ asset('storage/'.$image->image_path) }}" target="_blank">
                            <img src="{{ asset('storage/'.$image->image_path) }}" alt="Gambar" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 2px solid #ddd;">
                        </a>
                        @endforeach
                        @if($order->images->count() > 4)
                        <div class="d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: #f0f0f0; border-radius: 4px; font-size: 12px;">
                            +{{ $order->images->count() - 4 }}
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <hr>
@if($order->histories->count() > 0)
<div class="mb-3">
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
                    <strong>Status:</strong> {{ $history->from_status }} → {{ $history->to_status }}
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
                <!-- Update Status & Input -->
                <form method="POST" action="{{ route('press.update-status', $order->id) }}" class="mb-3">
                    @csrf
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label class="form-label small"><strong>Kiloan (kg):</strong></label>
                            <input type="number" step="0.01" name="kiloan" class="form-control form-control-sm" 
                                   value="{{ $order->stageInputs->kiloan ?? '' }}" 
                                   placeholder="Masukkan kiloan">
                        </div>
                        <!-- <div class="col-md-6">
                            <label class="form-label small"><strong>Meteran Press (m):</strong></label>
                            <input type="number" step="0.01" name="meteran_press" class="form-control form-control-sm" 
                                   value="{{ $order->stageInputs->meteran_press ?? '' }}" 
                                   placeholder="Masukkan meteran">
                        </div> -->
                    </div>
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
                    </div>
                </form>

                <!-- Move to Stage -->
                @if($order->stage_status === 'selesai')
                <form method="POST" action="{{ route('press.move', $order->id) }}">
                    @csrf
                    <label class="form-label small"><strong>Pindahkan ke Stage:</strong></label>
                    <div class="input-group">
                        <select name="next_stage" class="form-select form-select-sm" required>
                            <option value="">Pilih Stage</option>
                            <option value="desain">Desain (Revisi)</option>
                            <option value="printing">Printing (Revisi)</option>
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
            <h5>Tidak ada order di stage Press</h5>
            <p class="mb-0">Belum ada order yang masuk ke proses press saat ini.</p>
        </div>
    </div>
    @endforelse
</div>

<div class="d-flex justify-content-center">
    {{ $orders->links() }}
</div>
@endsection