@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-4">Analytics & Summary</h2>

    {{-- ======== FILTER SECTION ======== --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-dark text-white">
            <strong><i class="fas fa-filter"></i> Filter Data</strong>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('analytics.index') }}" id="filterForm">
                
                {{-- DATE MODE SELECTION - PENTING --}}
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-info mb-0">
                            <strong>Mode Tanggal:</strong>
                            <div class="form-check form-check-inline ms-3">
                                <input class="form-check-input" type="radio" name="date_mode" id="mode_created" 
                                       value="created" {{ $dateMode == 'created' ? 'checked' : '' }}>
                                <label class="form-check-label" for="mode_created">
                                    <strong>Berdasarkan PO Dibuat</strong> (tanggal_order)
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="date_mode" id="mode_completed" 
                                       value="completed" {{ $dateMode == 'completed' ? 'checked' : '' }}>
                                <label class="form-check-label" for="mode_completed">
                                    <strong>Berdasarkan Order Selesai</strong> (order_histories to_stage = selesai)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Filter Type --}}
                    <div class="col-md-3 mb-3">
                        <label for="filter_type" class="form-label">Periode</label>
                        <select name="filter_type" id="filter_type" class="form-select">
                            <option value="all" {{ $filterType == 'all' ? 'selected' : '' }}>Semua Data</option>
                            <option value="today" {{ $filterType == 'today' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="week" {{ $filterType == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                            <option value="month" {{ $filterType == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                            <option value="year" {{ $filterType == 'year' ? 'selected' : '' }}>Tahun Ini</option>
                            <option value="custom" {{ $filterType == 'custom' ? 'selected' : '' }}>Custom Range</option>
                        </select>
                    </div>

                    {{-- Start Date --}}
                    <div class="col-md-3 mb-3" id="startDateDiv">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" 
                               value="{{ $startDate }}">
                    </div>

                    {{-- End Date --}}
                    <div class="col-md-3 mb-3" id="endDateDiv">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" 
                               value="{{ $endDate }}">
                    </div>

                    {{-- Customer Filter --}}
                    <div class="col-md-3 mb-3">
                        <label for="customer" class="form-label">Customer</label>
                        <select name="customer" id="customer" class="form-select">
                            <option value="">-- Semua Customer --</option>
                            @foreach($allCustomers as $cust)
                                <option value="{{ $cust }}" {{ $customer == $cust ? 'selected' : '' }}>
                                    {{ $cust }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Stage Filter --}}
                    <div class="col-md-3 mb-3">
                        <label for="stage" class="form-label">Stage</label>
                        <select name="stage" id="stage" class="form-select">
                            <option value="">-- Semua Stage --</option>
                            @foreach($allStages as $stg)
                                <option value="{{ $stg }}" {{ $stage == $stg ? 'selected' : '' }}>
                                    {{ $stg }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Terapkan Filter
                        </button>
                        <a href="{{ route('analytics.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                        <button type="button" class="btn btn-success" onclick="exportData()">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Filter Info --}}
    @if($startDate || $endDate || $customer || $stage || $dateMode)
    <div class="alert alert-info">
        <strong>Filter Aktif:</strong>
        <span class="badge bg-primary">{{ $dateMode == 'created' ? 'Mode: PO Dibuat' : 'Mode: Order Selesai' }}</span>
        @if($startDate && $endDate)
            | Periode: {{ Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
        @elseif($startDate)
            | Dari: {{ Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
        @elseif($endDate)
            | Sampai: {{ Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
        @endif
        @if($customer)
            | Customer: <strong>{{ $customer }}</strong>
        @endif
        @if($stage)
            | Stage: <strong>{{ $stage }}</strong>
        @endif
        | Total PO: <strong>{{ $totalPoFiltered }}</strong>
    </div>
    @endif

    {{-- ======== SUMMARY CARDS ROW 1 ======== --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-left-primary p-3">
                <h6 class="text-muted">Total PO Bulan Ini</h6>
                <h3 class="mb-0">{{ $totalPoThisMonth }}</h3>
                <small class="text-muted">{{ now()->format('F Y') }}</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-left-warning p-3">
                <h6 class="text-muted">PO Aktif</h6>
                <h3 class="mb-0">{{ $totalPoActive }}</h3>
                <small class="text-muted">Sedang proses</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-left-success p-3">
                <h6 class="text-muted">PO Selesai</h6>
                <h3 class="mb-0">{{ $totalPoFinished }}</h3>
                <small class="text-muted">Status selesai</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-left-info p-3">
                <h6 class="text-muted">Total Customer</h6>
                <h3 class="mb-0">{{ $totalCustomers }}</h3>
                <small class="text-muted">Dalam filter</small>
            </div>
        </div>
    </div>

    {{-- ======== SUMMARY CARDS ROW 2 - KPI BARU ======== --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-left-primary p-3">
                <h6 class="text-muted">Avg Lead Time</h6>
                <h3 class="mb-0">
                    @if($avgLeadTime)
                        {{ number_format($avgLeadTime, 1) }} <small>hari</small>
                    @else
                        <small class="text-muted">N/A</small>
                    @endif
                </h3>
                <small class="text-muted">Rata-rata waktu selesai</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-left-success p-3">
                <h6 class="text-muted">PO On Time</h6>
                <h3 class="mb-0 text-success">{{ $poOnTime }}</h3>
                <small class="text-muted">Tepat waktu / lebih cepat</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-left-danger p-3">
                <h6 class="text-muted">PO Late</h6>
                <h3 class="mb-0 text-danger">{{ $poLate }}</h3>
                <small class="text-muted">Terlambat dari deadline</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-left-warning p-3">
                <h6 class="text-muted">On-Time Rate</h6>
                <h3 class="mb-0">
                    @if(($poOnTime + $poLate) > 0)
                        {{ number_format(($poOnTime / ($poOnTime + $poLate)) * 100, 1) }}%
                    @else
                        <small class="text-muted">N/A</small>
                    @endif
                </h3>
                <small class="text-muted">Persentase tepat waktu</small>
            </div>
        </div>
    </div>

    {{-- ======== SUMMARY CARDS ROW 3 ======== --}}
    @if($totalRevenue > 0)
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-left-success p-3">
                <h6 class="text-muted">Total Revenue</h6>
                <h3 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                <small class="text-muted">Dalam periode filter</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-left-info p-3">
                <h6 class="text-muted">Avg Revenue per PO</h6>
                <h3 class="mb-0">
                    @if($totalPoFiltered > 0)
                        Rp {{ number_format($totalRevenue / $totalPoFiltered, 0, ',', '.') }}
                    @else
                        <small class="text-muted">N/A</small>
                    @endif
                </h3>
                <small class="text-muted">Rata-rata nilai order</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-left-warning p-3">
                <h6 class="text-muted">Completion Rate</h6>
                <h3 class="mb-0">{{ $completionRate }}%</h3>
                <small class="text-muted">{{ $totalPoFinished }} dari {{ $totalPoFiltered }} PO</small>
            </div>
        </div>
    </div>
    @endif

    {{-- ======== SUMMARY BY STAGE ======== --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <strong>Ringkasan Berdasarkan Stage</strong>
        </div>
        <div class="card-body">
            @forelse ($stageStats as $s)
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <span><strong>{{ $s->current_stage ?? 'Tidak Ada' }}</strong></span>
                    <span class="badge bg-primary rounded-pill">{{ $s->total }} PO</span>
                </div>
            @empty
                <p class="text-muted mb-0">Tidak ada data.</p>
            @endforelse
        </div>
    </div>

    {{-- ======== TOP MATERIALS ======== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-layer-group"></i> Bahan Paling Banyak Dipakai</h5>
        </div>

        <div class="card-body">
            @if(isset($bahanUsage) && count($bahanUsage) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Jenis Bahan</th>
                                <th class="text-end">Meteran Desain</th>
                                <th class="text-end">Meteran Printing</th>
                                <th class="text-end">Meteran Press</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($bahanUsage as $row)
                                @php
                                    $total = $row->total_desain + $row->total_printing + $row->total_press;
                                @endphp
                                <tr>
                                    <td><strong>{{ $row->jenis_bahan ?? '-' }}</strong></td>
                                    <td class="text-end">{{ number_format($row->total_desain, 2) }} m</td>
                                    <td class="text-end">{{ number_format($row->total_printing, 2) }} m</td>
                                    <td class="text-end">{{ number_format($row->total_press, 2) }} m</td>
                                    <td class="text-end"><strong class="text-primary">{{ number_format($total, 2) }} m</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-exclamation-triangle"></i> Tidak ada data penggunaan bahan.
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        {{-- ======== TOP CUSTOMERS ======== --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <strong><i class="fas fa-users"></i> Customer Terbanyak Order</strong>
                </div>
                <div class="card-body">
                    @forelse ($topCustomers as $c)
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <span><strong>{{ $c->nama_konsumen }}</strong></span>
                            <span class="badge bg-success rounded-pill">{{ $c->total }} PO</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Tidak ada data.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ======== USER PERFORMANCE ======== --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <strong><i class="fas fa-user-check"></i> Produktivitas User</strong>
                </div>
                <div class="card-body">
                    @forelse ($userStats as $u)
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <span><strong>{{ $u['name'] }}</strong></span>
                            <span class="badge bg-info rounded-pill">{{ $u['total'] }} PO</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Tidak ada data.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    // Toggle custom date visibility
    document.getElementById('filter_type').addEventListener('change', function() {
        const customDateDivs = document.querySelectorAll('#startDateDiv, #endDateDiv');
        if (this.value === 'custom') {
            customDateDivs.forEach(div => div.style.display = 'block');
        } else {
            customDateDivs.forEach(div => div.style.display = 'none');
        }
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const filterType = document.getElementById('filter_type').value;
        const customDateDivs = document.querySelectorAll('#startDateDiv, #endDateDiv');
        
        if (filterType !== 'custom') {
            customDateDivs.forEach(div => div.style.display = 'none');
        }
    });

    // Export function (placeholder - implement based on your needs)
    function exportData() {
        const params = new URLSearchParams(window.location.search);
        alert('Export akan diimplementasi dengan parameter:\n' + 
              'Mode: ' + (params.get('date_mode') || 'created') + '\n' +
              'Start: ' + (params.get('start_date') || 'N/A') + '\n' +
              'End: ' + (params.get('end_date') || 'N/A'));
    }
</script>
@endpush

<style>
.border-left-primary {
    border-left: 4px solid #007bff !important;
}
.border-left-success {
    border-left: 4px solid #28a745 !important;
}
.border-left-danger {
    border-left: 4px solid #dc3545 !important;
}
.border-left-warning {
    border-left: 4px solid #ffc107 !important;
}
.border-left-info {
    border-left: 4px solid #17a2b8 !important;
}
</style>
@endsection