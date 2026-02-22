<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\StageInput;
use App\Models\OrderHistory;
use Illuminate\Http\Request;

class PrintingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['images', 'stageInputs','histories.user'])
            ->where('current_stage', 'printing')
            ->where('active', 1);

        $this->applyFilters($query, $request);

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('stages.printing', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = PurchaseOrder::findOrFail($id);
        
        $validated = $request->validate([
            'stage_status' => 'required|in:start,progress,selesai',
            'meteran_printing' => 'nullable|numeric|min:0',
        ]);

        OrderHistory::recordTransition(
    $order->id,
    $order->current_stage,
    $order->current_stage, // tetap di stage yang sama
    $order->stage_status,
    $validated['stage_status'],
    'Status diubah menjadi ' . $validated['stage_status']
);

        // Record history
OrderHistory::recordStatusChange($order, $validated['stage_status']);
        $order->update(['stage_status' => $validated['stage_status']]);

        if (isset($validated['meteran_printing'])) {
            StageInput::updateOrCreate(
                ['po_id' => $order->id, 'stage_name' => 'printing'],
                ['meteran_printing' => $validated['meteran_printing']]
            );
        }

        return redirect()->back()->with('success', 'Status berhasil diupdate');
    }

    public function moveToStage(Request $request, $id)
    {
        $order = PurchaseOrder::findOrFail($id);

        if ($order->stage_status !== 'selesai') {
            return redirect()->back()->with('error', 'Status harus selesai sebelum dipindahkan');
        }

        $validated = $request->validate([
            'next_stage' => 'required|in:desain,press,qc,pengiriman,selesai',
        ]);

//         OrderHistory::recordTransition(
//     $order->id,
//     $order->current_stage,
//     $validated['next_stage'],
//     $order->stage_status,
//     $validated['next_stage'] === 'selesai' ? 'selesai' : 'pending',
//     'Order dipindahkan dari ' . ucfirst($order->current_stage)
// );
        // Record history
OrderHistory::recordStageMove($order, $validated['next_stage']);
        $order->update([
            'current_stage' => $validated['next_stage'],
            'stage_status' => $validated['next_stage'] === 'selesai' ? 'selesai' : 'pending',
            'active' => $validated['next_stage'] === 'selesai' ? 2 : 1,
        ]);

        return redirect()->back()->with('success', 'Berhasil dipindahkan ke ' . $validated['next_stage']);
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