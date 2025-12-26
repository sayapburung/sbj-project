@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Dashboard</h2>
    <div>
        <span class="text-muted">Welcome, <strong>{{ auth()->user()->name }}</strong></span>
        @if(auth()->user()->role)
        <span class="badge bg-primary ms-2">{{ auth()->user()->role->name }}</span>
        @endif
    </div>
</div>

<div class="row g-4">
    {{-- Waiting List --}}
    <div class="col-md-4">
        <div class="card text-white bg-secondary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="card-title mb-0"><i class="fas fa-clock"></i> Waiting List</h6>
                        <h2 class="mb-0 mt-2">{{ $counts['waiting_list'] }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-clock fa-3x opacity-50"></i>
                    </div>
                </div>
                @if($counts['waiting_list'] > 0)
                <div class="mt-3 pt-2 border-top border-light">
                    <div class="row g-2 text-center text-sm">
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-hourglass-start"></i>
                                <div class="fw-bold">{{ $statusBreakdown['waiting_list']['pending'] }}</div>
                                <small>Pending</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-play-circle"></i>
                                <div class="fw-bold">{{ $statusBreakdown['waiting_list']['start'] }}</div>
                                <small>Start</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-spinner"></i>
                                <div class="fw-bold">{{ $statusBreakdown['waiting_list']['progress'] }}</div>
                                <small>Progress</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-check-circle"></i>
                                <div class="fw-bold">{{ $statusBreakdown['waiting_list']['selesai'] }}</div>
                                <small>Selesai</small>
                            </div>
                        </div>
                    </div>
                    <div class="progress mt-2" style="height: 8px;">
                        @php
                            $total = $counts['waiting_list'];
                            $pendingPct = $total > 0 ? ($statusBreakdown['waiting_list']['pending'] / $total * 100) : 0;
                            $startPct = $total > 0 ? ($statusBreakdown['waiting_list']['start'] / $total * 100) : 0;
                            $progressPct = $total > 0 ? ($statusBreakdown['waiting_list']['progress'] / $total * 100) : 0;
                            $selesaiPct = $total > 0 ? ($statusBreakdown['waiting_list']['selesai'] / $total * 100) : 0;
                        @endphp
                        <div class="progress-bar bg-secondary" style="width: {{ $pendingPct }}%" title="Pending: {{ $statusBreakdown['waiting_list']['pending'] }}"></div>
                        <div class="progress-bar bg-info" style="width: {{ $startPct }}%" title="Start: {{ $statusBreakdown['waiting_list']['start'] }}"></div>
                        <div class="progress-bar bg-warning" style="width: {{ $progressPct }}%" title="Progress: {{ $statusBreakdown['waiting_list']['progress'] }}"></div>
                        <div class="progress-bar bg-success" style="width: {{ $selesaiPct }}%" title="Selesai: {{ $statusBreakdown['waiting_list']['selesai'] }}"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Desain --}}
    <div class="col-md-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="card-title mb-0"><i class="fas fa-paint-brush"></i> Desain</h6>
                        <h2 class="mb-0 mt-2">{{ $counts['desain'] }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-paint-brush fa-3x opacity-50"></i>
                    </div>
                </div>
                @if($counts['desain'] > 0)
                <div class="mt-3 pt-2 border-top border-light">
                    <div class="row g-2 text-center text-sm">
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-hourglass-start"></i>
                                <div class="fw-bold">{{ $statusBreakdown['desain']['pending'] }}</div>
                                <small>Pending</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-play-circle"></i>
                                <div class="fw-bold">{{ $statusBreakdown['desain']['start'] }}</div>
                                <small>Start</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-spinner"></i>
                                <div class="fw-bold">{{ $statusBreakdown['desain']['progress'] }}</div>
                                <small>Progress</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-check-circle"></i>
                                <div class="fw-bold">{{ $statusBreakdown['desain']['selesai'] }}</div>
                                <small>Selesai</small>
                            </div>
                        </div>
                    </div>
                    <div class="progress mt-2" style="height: 8px;">
                        @php
                            $total = $counts['desain'];
                            $pendingPct = $total > 0 ? ($statusBreakdown['desain']['pending'] / $total * 100) : 0;
                            $startPct = $total > 0 ? ($statusBreakdown['desain']['start'] / $total * 100) : 0;
                            $progressPct = $total > 0 ? ($statusBreakdown['desain']['progress'] / $total * 100) : 0;
                            $selesaiPct = $total > 0 ? ($statusBreakdown['desain']['selesai'] / $total * 100) : 0;
                        @endphp
                        <div class="progress-bar bg-secondary" style="width: {{ $pendingPct }}%"></div>
                        <div class="progress-bar bg-info" style="width: {{ $startPct }}%"></div>
                        <div class="progress-bar bg-warning" style="width: {{ $progressPct }}%"></div>
                        <div class="progress-bar bg-success" style="width: {{ $selesaiPct }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Printing --}}
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="card-title mb-0"><i class="fas fa-print"></i> Printing</h6>
                        <h2 class="mb-0 mt-2">{{ $counts['printing'] }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-print fa-3x opacity-50"></i>
                    </div>
                </div>
                @if($counts['printing'] > 0)
                <div class="mt-3 pt-2 border-top border-light">
                    <div class="row g-2 text-center text-sm">
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-hourglass-start"></i>
                                <div class="fw-bold">{{ $statusBreakdown['printing']['pending'] }}</div>
                                <small>Pending</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-play-circle"></i>
                                <div class="fw-bold">{{ $statusBreakdown['printing']['start'] }}</div>
                                <small>Start</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-spinner"></i>
                                <div class="fw-bold">{{ $statusBreakdown['printing']['progress'] }}</div>
                                <small>Progress</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-check-circle"></i>
                                <div class="fw-bold">{{ $statusBreakdown['printing']['selesai'] }}</div>
                                <small>Selesai</small>
                            </div>
                        </div>
                    </div>
                    <div class="progress mt-2" style="height: 8px;">
                        @php
                            $total = $counts['printing'];
                            $pendingPct = $total > 0 ? ($statusBreakdown['printing']['pending'] / $total * 100) : 0;
                            $startPct = $total > 0 ? ($statusBreakdown['printing']['start'] / $total * 100) : 0;
                            $progressPct = $total > 0 ? ($statusBreakdown['printing']['progress'] / $total * 100) : 0;
                            $selesaiPct = $total > 0 ? ($statusBreakdown['printing']['selesai'] / $total * 100) : 0;
                        @endphp
                        <div class="progress-bar bg-secondary" style="width: {{ $pendingPct }}%"></div>
                        <div class="progress-bar bg-info" style="width: {{ $startPct }}%"></div>
                        <div class="progress-bar bg-warning" style="width: {{ $progressPct }}%"></div>
                        <div class="progress-bar bg-success" style="width: {{ $selesaiPct }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Press --}}
    <div class="col-md-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="card-title mb-0"><i class="fas fa-compress"></i> Press</h6>
                        <h2 class="mb-0 mt-2">{{ $counts['press'] }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-compress fa-3x opacity-50"></i>
                    </div>
                </div>
                @if($counts['press'] > 0)
                <div class="mt-3 pt-2 border-top" style="border-color: rgba(255,255,255,0.3) !important;">
                    <div class="row g-2 text-center text-sm">
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-hourglass-start"></i>
                                <div class="fw-bold">{{ $statusBreakdown['press']['pending'] }}</div>
                                <small>Pending</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-play-circle"></i>
                                <div class="fw-bold">{{ $statusBreakdown['press']['start'] }}</div>
                                <small>Start</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-spinner"></i>
                                <div class="fw-bold">{{ $statusBreakdown['press']['progress'] }}</div>
                                <small>Progress</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-check-circle"></i>
                                <div class="fw-bold">{{ $statusBreakdown['press']['selesai'] }}</div>
                                <small>Selesai</small>
                            </div>
                        </div>
                    </div>
                    <div class="progress mt-2" style="height: 8px;">
                        @php
                            $total = $counts['press'];
                            $pendingPct = $total > 0 ? ($statusBreakdown['press']['pending'] / $total * 100) : 0;
                            $startPct = $total > 0 ? ($statusBreakdown['press']['start'] / $total * 100) : 0;
                            $progressPct = $total > 0 ? ($statusBreakdown['press']['progress'] / $total * 100) : 0;
                            $selesaiPct = $total > 0 ? ($statusBreakdown['press']['selesai'] / $total * 100) : 0;
                        @endphp
                        <div class="progress-bar bg-secondary" style="width: {{ $pendingPct }}%"></div>
                        <div class="progress-bar bg-info" style="width: {{ $startPct }}%"></div>
                        <div class="progress-bar" style="width: {{ $progressPct }}%; background-color: #ff9800;"></div>
                        <div class="progress-bar bg-success" style="width: {{ $selesaiPct }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- QC --}}
    <div class="col-md-4">
        <div class="card text-white bg-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="card-title mb-0"><i class="fas fa-check-circle"></i> QC</h6>
                        <h2 class="mb-0 mt-2">{{ $counts['qc'] }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                    </div>
                </div>
                @if($counts['qc'] > 0)
                <div class="mt-3 pt-2 border-top border-light">
                    <div class="row g-2 text-center text-sm">
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-hourglass-start"></i>
                                <div class="fw-bold">{{ $statusBreakdown['qc']['pending'] }}</div>
                                <small>Pending</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-play-circle"></i>
                                <div class="fw-bold">{{ $statusBreakdown['qc']['start'] }}</div>
                                <small>Start</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-spinner"></i>
                                <div class="fw-bold">{{ $statusBreakdown['qc']['progress'] }}</div>
                                <small>Progress</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-check-circle"></i>
                                <div class="fw-bold">{{ $statusBreakdown['qc']['selesai'] }}</div>
                                <small>Selesai</small>
                            </div>
                        </div>
                    </div>
                    <div class="progress mt-2" style="height: 8px;">
                        @php
                            $total = $counts['qc'];
                            $pendingPct = $total > 0 ? ($statusBreakdown['qc']['pending'] / $total * 100) : 0;
                            $startPct = $total > 0 ? ($statusBreakdown['qc']['start'] / $total * 100) : 0;
                            $progressPct = $total > 0 ? ($statusBreakdown['qc']['progress'] / $total * 100) : 0;
                            $selesaiPct = $total > 0 ? ($statusBreakdown['qc']['selesai'] / $total * 100) : 0;
                        @endphp
                        <div class="progress-bar bg-secondary" style="width: {{ $pendingPct }}%"></div>
                        <div class="progress-bar bg-info" style="width: {{ $startPct }}%"></div>
                        <div class="progress-bar bg-warning" style="width: {{ $progressPct }}%"></div>
                        <div class="progress-bar bg-success" style="width: {{ $selesaiPct }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Pengiriman --}}
    <div class="col-md-4">
        <div class="card text-white" style="background-color: #6f42c1;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="card-title mb-0"><i class="fas fa-truck"></i> Pengiriman</h6>
                        <h2 class="mb-0 mt-2">{{ $counts['pengiriman'] }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-truck fa-3x opacity-50"></i>
                    </div>
                </div>
                @if($counts['pengiriman'] > 0)
                <div class="mt-3 pt-2 border-top border-light">
                    <div class="row g-2 text-center text-sm">
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-hourglass-start"></i>
                                <div class="fw-bold">{{ $statusBreakdown['pengiriman']['pending'] }}</div>
                                <small>Pending</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-play-circle"></i>
                                <div class="fw-bold">{{ $statusBreakdown['pengiriman']['start'] }}</div>
                                <small>Start</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-spinner"></i>
                                <div class="fw-bold">{{ $statusBreakdown['pengiriman']['progress'] }}</div>
                                <small>Progress</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="status-badge">
                                <i class="fas fa-check-circle"></i>
                                <div class="fw-bold">{{ $statusBreakdown['pengiriman']['selesai'] }}</div>
                                <small>Selesai</small>
                            </div>
                        </div>
                    </div>
                    <div class="progress mt-2" style="height: 8px;">
                        @php
                            $total = $counts['pengiriman'];
                            $pendingPct = $total > 0 ? ($statusBreakdown['pengiriman']['pending'] / $total * 100) : 0;
                            $startPct = $total > 0 ? ($statusBreakdown['pengiriman']['start'] / $total * 100) : 0;
                            $progressPct = $total > 0 ? ($statusBreakdown['pengiriman']['progress'] / $total * 100) : 0;
                            $selesaiPct = $total > 0 ? ($statusBreakdown['pengiriman']['selesai'] / $total * 100) : 0;
                        @endphp
                        <div class="progress-bar bg-secondary" style="width: {{ $pendingPct }}%"></div>
                        <div class="progress-bar bg-info" style="width: {{ $startPct }}%"></div>
                        <div class="progress-bar bg-warning" style="width: {{ $progressPct }}%"></div>
                        <div class="progress-bar bg-success" style="width: {{ $selesaiPct }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Selesai --}}
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0"><i class="fas fa-check-double"></i> Selesai</h6>
                        <h2 class="mb-0 mt-2">{{ $counts['selesai'] }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-check-double fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Quick Info</h5>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Total Active Orders:</strong> {{ $counts['waiting_list'] + $counts['desain'] + $counts['printing'] + $counts['press'] + $counts['qc'] + $counts['pengiriman'] }}</p>
                <p class="mb-0"><strong>Completed Orders:</strong> {{ $counts['selesai'] }}</p>
                @if(auth()->user()->hasPermission('kanban'))
                <hr>
                <a href="{{ route('kanban.index') }}" class="btn btn-primary">
                    <i class="fas fa-columns"></i> View Kanban Board
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.text-sm {
    font-size: 0.8rem;
}
.status-badge {
    padding: 0.25rem;
}
.status-badge i {
    font-size: 1rem;
    margin-bottom: 0.25rem;
}
.status-badge small {
    display: block;
    font-size: 0.7rem;
    margin-top: 0.1rem;
}
</style>
@endsection