<?php

// app/Http/Controllers/QCController.php - LENGKAP
namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\OrderHistory;
use App\Models\QcRejection;
use Illuminate\Http\Request;

class QCController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['images', 'stageInputs', 'histories.user', 'qcRejections'])
            ->where('current_stage', 'qc')
            ->where('active', 1);

        $this->applyFilters($query, $request);

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('stages.qc', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = PurchaseOrder::findOrFail($id);
        
        $validated = $request->validate([
            'stage_status' => 'required|in:pending,approved,rejected',
            'rejection_reason' => 'required_if:stage_status,rejected|nullable|string|max:500',
            'rejection_notes' => 'nullable|string|max:1000',
            'severity' => 'required_if:stage_status,rejected|nullable|in:low,medium,high,critical',
        ]);

        // Record history - status change
        OrderHistory::recordStatusChange($order, $validated['stage_status']);

        // Update status
        $order->update(['stage_status' => $validated['stage_status']]);

        // Jika rejected, record ke tabel qc_rejections
        if ($validated['stage_status'] === 'rejected') {
            // Tentukan dari stage mana (berdasarkan stage terakhir sebelum QC)
            $lastStage = $this->getLastStageBeforeQC($order);
            
            QcRejection::recordRejection(
                $order->id,
                $lastStage,
                $validated['rejection_reason'],
                $validated['rejection_notes'] ?? null,
                $validated['severity'] ?? 'medium'
            );

            // Record history dengan detail rejection
            $severityLabel = [
                'low' => 'Rendah',
                'medium' => 'Sedang', 
                'high' => 'Tinggi',
                'critical' => 'Kritis'
            ];
            
            OrderHistory::recordStatusChange(
                $order,
                'rejected',
                "QC Reject: {$validated['rejection_reason']} (Severity: {$severityLabel[$validated['severity']]})"
            );
        }

        $message = $validated['stage_status'] === 'rejected' 
            ? 'Order telah direject dan tercatat untuk monitoring' 
            : 'Status QC berhasil diupdate';

        return redirect()->back()->with('success', $message);
    }

    public function moveToStage(Request $request, $id)
    {
        $order = PurchaseOrder::findOrFail($id);

        $validated = $request->validate([
            'next_stage' => 'required|in:desain,printing,press,pengiriman,selesai',
        ]);

        // Jika pindah dari rejected ke stage lain (untuk revisi), 
        // mark rejection terakhir sebagai resolved
        if ($order->stage_status === 'rejected') {
            $lastRejection = $order->unresolvedRejections()->latest('rejected_at')->first();
            if ($lastRejection) {
                $lastRejection->markAsResolved();
                
                // Record history untuk resolved
                OrderHistory::recordStatusChange(
                    $order,
                    $order->stage_status,
                    "Rejection resolved - dipindahkan ke {$validated['next_stage']} untuk perbaikan"
                );
            }
        }

        // Record history - stage move
        $notes = 'Order dipindahkan dari QC';
        if (in_array($validated['next_stage'], ['desain', 'printing', 'press'])) {
            $notes .= ' untuk revisi';
        }
        OrderHistory::recordStageMove($order, $validated['next_stage'], $notes);

        $order->update([
            'current_stage' => $validated['next_stage'],
            'stage_status' => $validated['next_stage'] === 'selesai' ? 'selesai' : 'pending',
            'active' => $validated['next_stage'] === 'selesai' ? 2 : 1,
        ]);

        return redirect()->back()->with('success', 'Berhasil dipindahkan ke ' . $validated['next_stage']);
    }

    /**
     * Get stage terakhir sebelum masuk ke QC
     */
    private function getLastStageBeforeQC($order)
    {
        // Cek history untuk tahu dari stage mana order masuk ke QC
        $lastHistory = $order->histories()
            ->where('to_stage', 'qc')
            ->latest('created_at')
            ->first();

        if ($lastHistory && $lastHistory->from_stage) {
            return $lastHistory->from_stage;
        }

        // Default ke press jika tidak ketemu
        return 'press';
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_konsumen', 'like', "%{$search}%")
                ->orWhere('jenis_po', 'like', "%{$search}%")
                ->orWhereDate('deadline', $search)
                ->orWhere('po_number', 'like', "%{$search}%"); // optional kalau ada
            });
        }
    }
}