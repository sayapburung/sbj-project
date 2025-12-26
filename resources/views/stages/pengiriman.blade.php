@extends('layouts.app')

@section('title', 'Pengiriman')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-truck"></i> Pengiriman</h2>
    <span class="badge fs-6" style="background-color: #6f42c1;">{{ $orders->total() }} Orders</span>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('pengiriman.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="nama_konsumen" class="form-control" placeholder="Nama Konsumen" value="{{ request('nama_konsumen') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="jenis_po" class="form-control" placeholder="Jenis PO" value="{{ request('jenis_po') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="deadline_from" class="form-control" placeholder="Dari" value="{{ request('deadline_from') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="deadline_to" class="form-control" placeholder="Sampai" value="{{ request('deadline_to') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    @forelse($orders as $order)
    <div class="col-md-6 mb-4">
        <div class="card stage-card" style="border-left-color: #6f42c1;">
            <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #6f42c1;">
                <div>
                    <strong>{{ $order->po_number }}</strong>
                    <br><small>{{ $order->nama_konsumen }}</small>
                </div>
                @if($order->stage_status == 'shipped')
                    <span class="badge bg-success">Shipped</span>
                @elseif($order->stage_status == 'ready')
                    <span class="badge bg-info">Ready to Ship</span>
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
                        <small class="text-muted">Meteran</small>
                        <p class="mb-0"><strong>{{ $order->meteran }}</strong></p>
                    </div>
                </div>

                <div class="alert alert-light border mb-3">
                    <small class="text-muted">Alamat Konsumen</small>
                    <p class="mb-0"><strong>{{ $order->nama_konsumen }}</strong></p>
                    <small class="text-muted">Hubungi konsumen untuk konfirmasi pengiriman</small>
                </div>

                @if($order->images->count() > 0)
                <div class="mb-3">
                    <small class="text-muted">Preview Produk ({{ $order->images->count() }})</small>
                    <div class="d-flex gap-2 flex-wrap mt-2">
                        @foreach($order->images->take(4) as $image)
                        <a href="{{ asset('storage/'.$image->image_path) }}" target="_blank">
                            <img src="{{ asset('storage/'.$image->image_path) }}" alt="Gambar" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 2px solid #6f42c1;">
                        </a>
                        @endforeach
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
                <!-- Update Status Pengiriman -->
                <form method="POST" action="{{ route('pengiriman.update-status', $order->id) }}" class="mb-3">
                    @csrf
                    <label class="form-label small"><strong>Status Pengiriman:</strong></label>
                    <div class="input-group">
                        <select name="stage_status" class="form-select form-select-sm" required>
                            <option value="">Pilih Status</option>
                            <option value="pending" {{ $order->stage_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="ready" {{ $order->stage_status == 'ready' ? 'selected' : '' }}>Ready to Ship</option>
                            <option value="shipped" {{ $order->stage_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                    </div>
                </form>

                <!-- Tandai Selesai -->
                <form method="POST" action="{{ route('pengiriman.move', $order->id) }}" onsubmit="return confirm('Yakin order ini sudah selesai dikirim?')">
                    @csrf
                    <input type="hidden" name="next_stage" value="selesai">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-check-double"></i> Tandai Selesai (Barang Sudah Diterima)
                    </button>
                </form>
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
            <h5>Tidak ada order di stage Pengiriman</h5>
            <p class="mb-0">Belum ada order yang siap untuk dikirim saat ini.</p>
        </div>
    </div>
    @endforelse
</div>

<div class="d-flex justify-content-center">
    {{ $orders->links() }}
</div>
@endsection