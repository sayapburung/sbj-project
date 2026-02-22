<?php
namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\OrderHistory;
use Illuminate\Http\Request;

class PengirimanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['images', 'stageInputs','histories.user'])
            ->where('current_stage', 'pengiriman')
            ->where('active', 1);

        $this->applyFilters($query, $request);

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('stages.pengiriman', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = PurchaseOrder::findOrFail($id);
        
        $validated = $request->validate([
            'stage_status' => 'required|in:pending,ready,shipped',
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

        return redirect()->back()->with('success', 'Status berhasil diupdate');
    }

    public function moveToStage(Request $request, $id)
    {
        $order = PurchaseOrder::findOrFail($id);

        $validated = $request->validate([
            'next_stage' => 'required|in:selesai',
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
            'current_stage' => 'selesai',
            'stage_status' => 'selesai',
            'active' => 2,
        ]);

        return redirect()->back()->with('success', 'Order telah selesai');
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