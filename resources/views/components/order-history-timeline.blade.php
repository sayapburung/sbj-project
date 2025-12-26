@props(['histories'])

<div class="order-history-timeline">
    @if($histories && $histories->count() > 0)
        @foreach($histories as $history)
        <div class="timeline-item">
            <div class="timeline-marker bg-{{ $history->stage_color }}"></div>
            <div class="timeline-content">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        @if($history->from_stage && $history->from_stage !== $history->to_stage)
                            <!-- Transisi Stage -->
                            <strong class="text-primary">
                                <i class="fas fa-arrow-right"></i> Pindah Stage
                            </strong>
                            <p class="mb-1">
                                <span class="badge bg-secondary">{{ $history->from_stage_label }}</span>
                                <i class="fas fa-arrow-right mx-1"></i>
                                <span class="badge bg-{{ $history->stage_color }}">{{ $history->to_stage_label }}</span>
                            </p>
                        @else
                            <!-- Update Status -->
                            <strong class="text-info">
                                <i class="fas fa-sync-alt"></i> Update Status
                            </strong>
                            <p class="mb-1">
                                <span class="badge bg-light text-dark">{{ $history->from_status }}</span>
                                <i class="fas fa-arrow-right mx-1"></i>
                                <span class="badge bg-primary">{{ $history->to_status }}</span>
                                di stage <strong>{{ $history->to_stage_label }}</strong>
                            </p>
                        @endif
                        
                        @if($history->notes)
                        <p class="text-muted small mb-0">
                            <i class="fas fa-comment"></i> {{ $history->notes }}
                        </p>
                        @endif
                    </div>
                    <small class="text-muted">
                        {{ $history->created_at->diffForHumans() }}
                    </small>
                </div>
                <small class="text-muted">
                    <i class="fas fa-user"></i> {{ $history->user->name }}
                </small>
            </div>
        </div>
        @endforeach
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Belum ada history untuk order ini
        </div>
    @endif
</div>

<style>
.order-history-timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
    border-left: 2px solid #dee2e6;
}

.timeline-item:last-child {
    border-left: none;
}

.timeline-marker {
    position: absolute;
    left: -31px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    padding: 10px;
    background: #f8f9fa;
    border-radius: 6px;
    margin-left: 10px;
}

.bg-purple {
    background-color: #6f42c1 !important;
}
</style>
