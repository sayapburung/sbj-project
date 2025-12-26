@extends('layouts.app')

@section('title', 'Purchase Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Purchase Orders</h2>
    <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah PO
    </a>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('purchase-orders.index') }}">
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

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No PO</th>
                        <th>Konsumen</th>
                        <th>Jenis PO</th>
                        <th>Jumlah Pcs</th>
                        <th>Meteran Admin</th>
                        <th>Deadline</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->po_number }}</strong></td>
                        <td>{{ $order->nama_konsumen }}</td>
                        <td>{{ $order->jenis_po }}</td>
                        <td>{{ $order->jumlah }}</td>
                        <td>{{ $order->meteran }}</td>
                        <td class="{{ $order->deadline < now() ? 'text-danger fw-bold' : '' }}">
                            {{ $order->deadline->format('d/m/Y') }}
                        </td>
                        @php
                            $statusClass = [
                                'selesai' => 'bg-success',
                                'progress' => 'bg-warning',
                                'pending' => 'bg-danger',
                                'start' => 'bg-info',
                            ][$order->stage_status] ?? 'bg-info';
                        @endphp

                        <td>
                            <span class="badge {{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $order->current_stage)) }} - 
                                {{ ucfirst(str_replace('_', ' ', $order->stage_status)) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('purchase-orders.edit', $order->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('purchase-orders.print-spk', $order->id) }}" class="btn btn-success" target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                                @if($order->current_stage == 'waiting_list')
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#moveModal{{ $order->id }}">
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Move to Stage -->
                    <div class="modal fade" id="moveModal{{ $order->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Pindahkan Order</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('purchase-orders.move', $order->id) }}">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Pindahkan ke Stage:</label>
                                            <select name="next_stage" class="form-select" required>
                                                <option value="">Pilih Stage</option>
                                                <option value="desain">Desain</option>
                                                <option value="printing">Printing</option>
                                                <option value="press">Press</option>
                                                <option value="qc">QC</option>
                                                <option value="pengiriman">Pengiriman</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Pindahkan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $orders->links() }}
    </div>
</div>
@endsection
