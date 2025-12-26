<!-- resources/views/completed-orders/index.blade.php -->
@extends('layouts.app')

@section('title', 'Completed Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-check-double"></i> Completed Orders</h2>
    <span class="badge bg-success fs-6">{{ $orders->total() }} Orders</span>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="card-title"><i class="fas fa-check-circle"></i> Total Completed</h6>
                <h2 class="mb-0">{{ $stats['total_orders'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h6 class="card-title"><i class="fas fa-exclamation-triangle"></i> With Rejection</h6>
                <h2 class="mb-0">{{ $stats['total_with_rejection'] }}</h2>
                <small>{{ $stats['rejection_rate'] }}% rejection rate</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6 class="card-title"><i class="fas fa-thumbs-up"></i> No Rejection</h6>
                <h2 class="mb-0">{{ $stats['total_without_rejection'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6 class="card-title"><i class="fas fa-clock"></i> Avg Duration</h6>
                <h2 class="mb-0">{{ $stats['avg_duration'] }}</h2>
                <small>days</small>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Sort -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('completed-orders.index') }}">
            <div class="row g-3">
                <div class="col-md-2">
                    <input type="text" name="po_number" class="form-control form-control-sm" 
                           placeholder="No PO" value="{{ request('po_number') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="nama_konsumen" class="form-control form-control-sm" 
                           placeholder="Konsumen" value="{{ request('nama_konsumen') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="jenis_po" class="form-control form-control-sm" 
                           placeholder="Jenis PO" value="{{ request('jenis_po') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control form-control-sm" 
                           placeholder="From" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control form-control-sm" 
                           placeholder="To" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <select name="has_rejection" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="yes" {{ request('has_rejection') == 'yes' ? 'selected' : '' }}>With Rejection</option>
                        <option value="no" {{ request('has_rejection') == 'no' ? 'selected' : '' }}>No Rejection</option>
                    </select>
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-3">
                    <select name="sort_by" class="form-select form-select-sm">
                        <option value="completed_date" {{ request('sort_by') == 'completed_date' ? 'selected' : '' }}>Sort: Completed Date</option>
                        <option value="po_number" {{ request('sort_by') == 'po_number' ? 'selected' : '' }}>Sort: PO Number</option>
                        <option value="customer" {{ request('sort_by') == 'customer' ? 'selected' : '' }}>Sort: Customer</option>
                        <option value="created_date" {{ request('sort_by') == 'created_date' ? 'selected' : '' }}>Sort: Created Date</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort_order" class="form-select form-select-sm">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Newest First</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Oldest First</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('completed-orders.index') }}" class="btn btn-secondary btn-sm w-100">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>PO Number</th>
                        <th>Customer</th>
                        <th>Jenis PO</th>
                        <th>Created</th>
                        <th>Completed</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->po_number }}</strong></td>
                        <td>{{ $order->nama_konsumen }}</td>
                        <td>{{ $order->jenis_po }}</td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        <td>{{ $order->updated_at->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge bg-primary">
                                {{ $order->created_at->diffInDays($order->updated_at) }} days
                            </span>
                        </td>
                        <td>
                            @if($order->qcRejections->count() > 0)
                                <span class="badge bg-warning text-dark" title="Rejection: {{ $order->qcRejections->count() }}x">
                                    <i class="fas fa-exclamation-triangle"></i> {{ $order->qcRejections->count() }}x
                                </span>
                            @else
                                <span class="badge bg-success">
                                    <i class="fas fa-check"></i> Perfect
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('completed-orders.show', $order->id) }}" 
                                   class="btn btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('completed-orders.pdf', $order->id) }}" 
                                   class="btn btn-danger" title="Export PDF" target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">No completed orders found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    </div>
</div>

<!-- Top Customers -->
@if($stats['top_customers']->count() > 0)
<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-trophy"></i> Top 5 Customers</h5>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($stats['top_customers'] as $index => $customer)
            <div class="col-md-4 mb-3">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <h2 class="text-primary mb-0">#{{ $index + 1 }}</h2>
                    </div>
                    <div>
                        <strong>{{ $customer->nama_konsumen }}</strong>
                        <br><small class="text-muted">{{ $customer->total }} orders completed</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Completion Trend -->
@if($stats['completion_by_month']->count() > 0)
<div class="card mt-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-chart-line"></i> Completion Trend (Last 6 Months)</h5>
    </div>
    <div class="card-body">
        <div class="row text-center">
            @foreach($stats['completion_by_month'] as $month)
            <div class="col-md-2 mb-3">
                <h3 class="text-success mb-1">{{ $month->total }}</h3>
                <small class="text-muted">{{ \Carbon\Carbon::parse($month->month . '-01')->format('M Y') }}</small>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection