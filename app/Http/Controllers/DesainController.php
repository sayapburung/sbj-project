<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\StageInput;
use App\Models\OrderHistory;
use App\Models\PoImage;
use Illuminate\Http\Request;

class DesainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['images', 'stageInputs','histories.user'])
            ->where('current_stage', 'desain')
            ->where('active', 1);

        $this->applyFilters($query, $request);

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('stages.desain', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = PurchaseOrder::findOrFail($id);
        
        $validated = $request->validate([
            'stage_status' => 'required|in:start,progress,selesai',
            'meteran_desain' => 'nullable|numeric|min:0',
            'images.*' => 'nullable|image|max:5120',
            'description' => 'nullable|string|max:500',
        ]);

        // Record history - status change
        OrderHistory::recordStatusChange($order, $validated['stage_status']);

        // Update status
        $order->update(['stage_status' => $validated['stage_status']]);

        // Save meteran desain
        if (isset($validated['meteran_desain'])) {
            StageInput::updateOrCreate(
                ['po_id' => $order->id, 'stage_name' => 'desain'],
                ['meteran_desain' => $validated['meteran_desain']]
            );
        }

        // Upload images dari desain
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('po-images', 'public');
                PoImage::create([
                    'po_id' => $order->id,
                    'image_path' => $path,
                    'original_name' => $image->getClientOriginalName(),
                    'uploaded_from_stage' => 'desain',
                    'uploaded_by' => auth()->id(),
                    'description' => $validated['description'] ?? null,
                ]);
            }

            // Record history untuk upload gambar
            $imageCount = count($request->file('images'));
            OrderHistory::recordStatusChange(
                $order, 
                $order->stage_status, 
                "Upload {$imageCount} gambar desain"
            );
        }

        return redirect()->back()->with('success', 'Status dan data berhasil diupdate');
    }

    public function moveToStage(Request $request, $id)
    {
        $order = PurchaseOrder::findOrFail($id);

        if ($order->stage_status !== 'selesai') {
            return redirect()->back()->with('error', 'Status harus selesai sebelum dipindahkan');
        }

        $validated = $request->validate([
            'next_stage' => 'required|in:printing,press,qc,pengiriman,selesai',
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

    public function deleteImage($id)
    {
        $image = PoImage::findOrFail($id);
        
        // Cek permission
        if ($image->uploaded_by !== auth()->id() && !auth()->user()->hasPermission('purchase_order')) {
            return redirect()->back()->with('error', 'Anda tidak berhak menghapus gambar ini');
        }

        // Hapus file dari storage
        Storage::disk('public')->delete($image->image_path);
        
        // Hapus record dari database
        $image->delete();

        return redirect()->back()->with('success', 'Gambar berhasil dihapus');
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
