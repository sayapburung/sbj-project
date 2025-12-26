<!-- resources/views/completed-orders/pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Completed Order - {{ $order->po_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th, table td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        table th { background-color: #f5f5f5; font-weight: bold; }
        .section-title { background-color: #333; color: white; padding: 5px 10px; margin-top: 20px; margin-bottom: 10px; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 10px; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: black; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-info { background-color: #17a2b8; color: white; }
        .timeline { margin-left: 20px; }
        .timeline-item { margin-bottom: 10px; padding-left: 15px; border-left: 2px solid #ddd; }
        .small { font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h2>COMPLETED ORDER REPORT</h2>
        <p><strong>PO Number: {{ $order->po_number }}</strong></p>
        <p>Generated: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <!-- Order Information -->
    <div class="section-title">ORDER INFORMATION</div>
    <table>
        <tr>
            <th width="30%">Customer</th>
            <td>{{ $order->nama_konsumen }}</td>
            <th width="30%">Created By</th>
            <td>{{ $order->creator->name }}</td>
        </tr>
        <tr>
            <th>Jenis PO</th>
            <td>{{ $order->jenis_po }}</td>
            <th>Jenis Bahan</th>
            <td>{{ $order->jenis_bahan }}</td>
        </tr>
        <tr>
            <th>Jumlah</th>
            <td>{{ $order->jumlah }}</td>
            <th>Meteran</th>
            <td>{{ $order->meteran }} m</td>
        </tr>
        <tr>
            <th>Tanggal Order</th>
            <td>{{ $order->tanggal_order->format('d F Y') }}</td>
            <th>Deadline</th>
            <td>{{ $order->deadline->format('d F Y') }}</td>
        </tr>
        <tr>
            <th>Created At</th>
            <td>{{ $order->created_at->format('d F Y H:i') }}</td>
            <th>Completed At</th>
            <td>{{ $order->updated_at->format('d F Y H:i') }}</td>
        </tr>
        <tr>
            <th>Total Duration</th>
            <td colspan="3"><strong>{{ $order->created_at->diffInDays($order->updated_at) }} days</strong></td>
        </tr>
    </table>

    <!-- Production Data -->
    @if($order->stageInputs)
    <div class="section-title">PRODUCTION DATA</div>
    <table>
        @if($order->stageInputs->meteran_desain)
        <tr>
            <th width="30%">Meteran Desain</th>
            <td>{{ $order->stageInputs->meteran_desain }} m</td>
        </tr>
        @endif
        @if($order->stageInputs->meteran_printing)
        <tr>
            <th>Meteran Printing</th>
            <td>{{ $order->stageInputs->meteran_printing }} m</td>
        </tr>
        @endif
        @if($order->stageInputs->kiloan)
        <tr>
            <th>Kiloan</th>
            <td>{{ $order->stageInputs->kiloan }} kg</td>
        </tr>
        @endif
        @if($order->stageInputs->meteran_press)
        <tr>
            <th>Meteran Press</th>
            <td>{{ $order->stageInputs->meteran_press }} m</td>
        </tr>
        @endif
    </table>
    @endif

    <!-- Stage Duration -->
    @if(count($stageDurations) > 0)
    <div class="section-title">STAGE DURATION</div>
    <table>
        <thead>
            <tr>
                <th>Stage</th>
                <th>Duration (Days)</th>
                <th>Duration (Hours)</th>
                <th>Entered At</th>
                <th>Exited At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stageDurations as $stage => $duration)
            <tr>
                <td>{{ ucfirst(str_replace('_', ' ', $stage)) }}</td>
                <td><strong>{{ $duration['days'] }} days</strong></td>
                <td>{{ $duration['hours'] }} hours</td>
                <td>{{ $duration['entered_at']->format('d/m/Y H:i') }}</td>
                <td>{{ $duration['exited_at']->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- QC Rejections -->
    @if($order->qcRejections->count() > 0)
    <div class="section-title">QC REJECTION HISTORY</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>From Stage</th>
                <th>Reason</th>
                <th>Severity</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->qcRejections as $rejection)
            <tr>
                <td>{{ $rejection->rejected_at->format('d/m/Y H:i') }}</td>
                <td>{{ $rejection->stage_label }}</td>
                <td>{{ $rejection->rejection_reason }}</td>
                <td>{{ $rejection->severity_label }}</td>
                <td>{{ $rejection->is_resolved ? 'Resolved' : 'Unresolved' }}</td>
            </tr>
            @if($rejection->rejection_notes)
            <tr>
                <td colspan="5" style="background-color: #f9f9f9;">
                    <strong>Notes:</strong> {{ $rejection->rejection_notes }}
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Images Summary -->
    @if($order->images->count() > 0)
    <div class="section-title">IMAGES SUMMARY</div>
    <table>
        <thead>
            <tr>
                <th>Filename</th>
                <th>Uploaded From</th>
                <th>Uploaded By</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->images as $image)
            <tr>
                <td>{{ $image->original_name }}</td>
                <td>{{ $image->getStageLabel() }}</td>
                <td>{{ $image->uploader ? $image->uploader->name : '-' }}</td>
                <td>{{ $image->description ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- History Timeline -->
    <div class="section-title">HISTORY TIMELINE</div>
    <div class="timeline">
        @foreach($order->histories as $history)
        <div class="timeline-item">
            <strong>{{ $history->created_at->format('d/m/Y H:i') }}</strong> - 
            @if($history->from_stage !== $history->to_stage)
                Stage moved from <strong>{{ $history->from_stage_label }}</strong> to <strong>{{ $history->to_stage_label }}</strong>
            @else
                Status changed from <strong>{{ $history->from_status }}</strong> to <strong>{{ $history->to_status }}</strong>
            @endif
            <br>
            <span class="small">by {{ $history->user->name }}</span>
            @if($history->notes)
            <br><span class="small">{{ $history->notes }}</span>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Footer -->
    <div style="margin-top: 30px; padding-top: 10px; border-top: 1px solid #ddd; text-align: center; font-size: 10px; color: #666;">
        <p>This is a computer generated document. No signature required.</p>
        <p>Generated from Workflow Management System</p>
    </div>
</body>
</html>