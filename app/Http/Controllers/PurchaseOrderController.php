<?php
namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PoImage;
use App\Models\PoFile;
use App\Models\OrderHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;


class PurchaseOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['images', 'files'])->where('active', 1);

        if ($request->filled('nama_konsumen')) {
            $query->where('nama_konsumen', 'like', '%' . $request->nama_konsumen . '%');
        }

        if ($request->filled('jenis_po')) {
            $query->where('jenis_po', 'like', '%' . $request->jenis_po . '%');
        }

        if ($request->filled('deadline_from') && $request->filled('deadline_to')) {
            $query->whereBetween('deadline', [$request->deadline_from, $request->deadline_to]);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(100);

        return view('purchase-orders.index', compact('orders'));
    }

    public function create()
    {
        return view('purchase-orders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_konsumen' => 'required|string|max:255',
            'jenis_po' => 'required|string|max:255',
            'file' => 'nullable|file|max:10240',
            'jumlah' => 'nullable|integer|min:0',
            'meteran' => 'nullable|numeric|min:0',
            'images.*' => 'nullable|image|max:5120',
            'tanggal_order' => 'required|date',
            'deadline' => 'required|date',
            'jenis_bahan' => 'required|string|max:255',
        ]);

        $validated['po_number'] = PurchaseOrder::generatePoNumber();
        $validated['created_by'] = auth()->id();
        $validated['current_stage'] = 'waiting_list';
        $validated['stage_status'] = 'pending';
        $validated['active'] = 1;
        $validated['jumlah'] = $request->jumlah ?? 0;
        $validated['meteran'] = $request->meteran ?? 0;

        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file')->store('po-files', 'public');
        }

        $po = PurchaseOrder::create($validated);
        // OrderHistory::recordCreation($po->id);
        OrderHistory::recordTransition(
        $po->id,
        null,
        'waiting_list',
        null,
        'pending',
        'Purchase Order dibuat'
);

        // Upload multiple images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('po-images', 'public');
                PoImage::create([
                    'po_id' => $po->id,
                    'image_path' => $path,
                    'original_name' => $image->getClientOriginalName(),
                    'uploaded_from_stage' => 'purchase_order', // TAMBAHAN
                    'uploaded_by' => auth()->id(),              // TAMBAHAN
                ]);
            }
        }

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil dibuat');
    }

public function edit($id)
{
    $order = PurchaseOrder::with(['images', 'files', 'histories.user'])->findOrFail($id);
    return view('purchase-orders.edit', compact('order'));
}

    public function update(Request $request, $id)
    {
        $order = PurchaseOrder::findOrFail($id);

        $validated = $request->validate([
            'nama_konsumen' => 'required|string|max:255',
            'jenis_po' => 'required|string|max:255',
            'file' => 'nullable|file|max:10240',
            'jumlah' => 'nullable|integer|min:0',
            'meteran' => 'nullable|numeric|min:0',
            'images.*' => 'nullable|image|max:5120',
            'tanggal_order' => 'required|date',
            'deadline' => 'required|date',
            'jenis_bahan' => 'required|string|max:255',
        ]);

        $validated['jumlah'] = $request->jumlah ?? 0;
        $validated['meteran'] = $request->meteran ?? 0;

        if ($request->hasFile('file')) {
            if ($order->file) {
                Storage::disk('public')->delete($order->file);
            }
            $validated['file'] = $request->file('file')->store('po-files', 'public');
        }
        $order->update($validated);

        // Upload new images if provided
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('po-images', 'public');
                PoImage::create([
                    'po_id' => $order->id,
                    'image_path' => $path,
                    'original_name' => $image->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil diupdate');
    }

    public function destroy($id)
    {
        $order = PurchaseOrder::findOrFail($id);
        
        // Delete all images
        foreach ($order->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        // Delete file if exists
        if ($order->file) {
            Storage::disk('public')->delete($order->file);
        }
        
        $order->delete();
        
        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil dihapus');
    }

    public function printSpk($id)
    {
        $order = PurchaseOrder::with(['images'])->findOrFail($id);
        $pdf = PDF::loadView('purchase-orders.spk', compact('order'));
        return $pdf->download('SPK-' . $order->po_number . '.pdf');
    }

    public function moveToStage(Request $request, $id)
    {
        $order = PurchaseOrder::findOrFail($id);

        $validated = $request->validate([
            'next_stage' => 'required|in:waiting_list,desain,printing,press,qc,pengiriman,selesai',
        ]);

//         OrderHistory::recordTransition(
//     $order->id,
//     $order->current_stage,
//     $validated['next_stage'],
//     $order->stage_status,
//     'pending',
//     'Order dipindahkan dari Purchase Order menu'
// );
        // Record history
OrderHistory::recordStageMove($order, $validated['next_stage']);
        $order->update([
            'current_stage' => $validated['next_stage'],
            'stage_status' => 'pending',
            'active' => $validated['next_stage'] === 'selesai' ? 2 : 1,
        ]);

        return redirect()->back()->with('success', 'Order berhasil dipindahkan ke ' . $validated['next_stage']);
    }

    public function deleteImage($id)
    {
        $image = PoImage::findOrFail($id);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        
        return redirect()->back()->with('success', 'Gambar berhasil dihapus');
    }
}