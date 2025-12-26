<?php
namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\OrderHistory;
use Illuminate\Http\Request;

class KanbanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['images', 'files', 'stageInputs'])->where('active', 1);

        if ($request->filled('nama_konsumen')) {
            $query->where('nama_konsumen', 'like', '%' . $request->nama_konsumen . '%');
        }

        if ($request->filled('jenis_po')) {
            $query->where('jenis_po', 'like', '%' . $request->jenis_po . '%');
        }

        if ($request->filled('deadline_from') && $request->filled('deadline_to')) {
            $query->whereBetween('deadline', [$request->deadline_from, $request->deadline_to]);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $kanbanData = [
            'waiting_list' => $orders->where('current_stage', 'waiting_list'),
            'desain' => $orders->where('current_stage', 'desain'),
            'printing' => $orders->where('current_stage', 'printing'),
            'press' => $orders->where('current_stage', 'press'),
            'qc' => $orders->where('current_stage', 'qc'),
            'pengiriman' => $orders->where('current_stage', 'pengiriman'),
        ];

        return view('kanban.index', compact('kanbanData'));
    }

   public function show($id)
{
    $order = PurchaseOrder::with(['images', 'files', 'stageInputs', 'histories.user'])
        ->findOrFail($id);
    return response()->json($order);
}
}