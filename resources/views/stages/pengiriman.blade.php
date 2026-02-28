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
                        <input type="hidden" 
       name="stage_status" 
       id="shippingStatusInput{{ $order->id }}">

<div class="d-flex gap-2 flex-wrap">

    <!-- Pending -->
    <button type="submit"
            onclick="setShippingStatus({{ $order->id }}, 'pending')"
            class="btn btn-sm 
                {{ $order->stage_status == 'pending' ? 'btn-secondary' : 'btn-outline-secondary' }}">
        ⏳ Pending
    </button>

    <!-- Ready -->
    <button type="submit"
            onclick="setShippingStatus({{ $order->id }}, 'ready')"
            class="btn btn-sm 
                {{ $order->stage_status == 'ready' ? 'btn-info' : 'btn-outline-info' }}">
        📦 Ready
    </button>

    <!-- Shipped -->
    <button type="submit"
            onclick="setShippingStatus({{ $order->id }}, 'shipped')"
            class="btn btn-sm 
                {{ $order->stage_status == 'shipped' ? 'btn-success' : 'btn-outline-success' }}">
        🚚 Shipped
    </button>

</div>

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
<script>
function setShippingStatus(orderId, status) {

    const input = document.getElementById('shippingStatusInput' + orderId);

    if (!input) return;

    input.value = status;
}
</script>
@endsection