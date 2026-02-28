<?php
namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PoImage;
use App\Models\PoFile;
use App\Models\JenisPo;
use App\Models\OrderHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;


class PurchaseOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

public function index(Request $request)
{
    $query = PurchaseOrder::with(['images', 'files', 'creator']) // tambah relasi creator
                ->where('active', 1);

    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('nama_konsumen', 'like', "%{$search}%")
              ->orWhere('jenis_po', 'like', "%{$search}%")
              ->orWhereDate('deadline', $search)
              ->orWhere('po_number', 'like', "%{$search}%");

              $q->orWhereHas('creator', function ($q2) use ($search) {
                $q2->where('name', 'like', "%{$search}%");
            });
        });
    }

    $orders = $query->orderBy('created_at', 'desc')->paginate(10);

    return view('purchase-orders.index', compact('orders'));
}
        public function create()
        {
            $jenisPos = JenisPo::where('is_active', 1)
                ->orderBy('kategori')
                ->orderBy('nama')
                ->get();
            return view('purchase-orders.create', compact('jenisPos'));
        }

        public function store(Request $request)
        {
            // Pastikan action selalu ada
            $request->merge([
                'action' => $request->action ?? 'save'
            ]);

            $validated = $request->validate([
                'nama_konsumen' => 'required|string|max:255',
                'nama_po' => 'required|string|max:255',
                'jenis_po_id' => ['required', Rule::exists(JenisPo::class, 'id')],
                // 'jenis_po' => 'required|string|max:255',
                'file' => 'nullable|file|max:10240',
                'jumlah' => 'nullable|integer|min:0',
                'meteran' => 'nullable|numeric|min:0',
                'images.*' => 'nullable|image|max:5120',
                'tanggal_order' => 'required|date',
                'deadline' => 'required|date',
                'jenis_bahan' => 'required|string|max:255',
            ],[
                'images.*.max' => 'Maksimal ukuran gambar adalah 5MB per file.',
                'file.max' => 'Ukuran file maksimal 10MB.',
            ]);

            // 🔥 Ambil data jenis_po berdasarkan ID
            $jenisPo = JenisPo::findOrFail($validated['jenis_po_id']);

            // 🔥 Simpan juga nama jenis_po (string)
            $validated['jenis_po'] = $jenisPo->nama;

            // Generate nomor PO & user
            $validated['po_number'] = PurchaseOrder::generatePoNumber($validated['jenis_po_id']);
            $validated['created_by'] = auth()->id();
            $validated['active'] = 1;
            $validated['jumlah'] = $request->jumlah ?? 0;
            $validated['meteran'] = $request->meteran ?? 0;

            // Default stage
            $currentStage = 'waiting_list';
            $stageStatus  = 'pending';

            // Jika klik "Simpan & Kirim ke Desain"
            if ($request->action === 'kirim_desain') {
                $currentStage = 'desain';
                $stageStatus  = 'pending';
            }

            $validated['current_stage'] = $currentStage;
            $validated['stage_status']  = $stageStatus;

            // Upload file utama
            if ($request->hasFile('file')) {
                $validated['file'] = $request->file('file')->store('po-files', 'public');
            }

            // ✅ Create hanya sekali
            $po = PurchaseOrder::create($validated);

            // Record history
            OrderHistory::recordTransition(
                $po->id,
                null,
                $currentStage,
                null,
                $stageStatus,
                $request->action === 'kirim_desain'
                    ? 'Purchase Order dibuat & langsung dikirim ke desain'
                    : 'Purchase Order dibuat'
            );

            // Upload multiple images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('po-images', 'public');

                    PoImage::create([
                        'po_id' => $po->id,
                        'image_path' => $path,
                        'original_name' => $image->getClientOriginalName(),
                        'uploaded_from_stage' => 'purchase_order',
                        'uploaded_by' => auth()->id(),
                    ]);
                }
            }

            return redirect()
                ->route('purchase-orders.index')
                ->with('success', 'Purchase Order berhasil dibuat');
        }

    public function edit($id)
    {
        $order = PurchaseOrder::with(['images', 'files', 'histories.user'])->findOrFail($id);
        $jenisPos = JenisPo::where('is_active', 1)->get();
        return view('purchase-orders.edit', compact('order','jenisPos'));
    }

//         public function update(Request $request, $id)
//     {
//         $order = PurchaseOrder::findOrFail($id);

//         $validated = $request->validate([
//             'nama_konsumen' => 'required|string|max:255',
//             'nama_po' => 'required|string|max:255',
//             // 'jenis_po_id' => ['required', Rule::exists(JenisPo::class, 'id')],
//             // 'jenis_po' => 'required|string|max:255',
//             'file' => 'nullable|file|max:10240',
//             'jumlah' => 'nullable|integer|min:0',
//             'meteran' => 'nullable|numeric|min:0',
//             'images.*' => 'nullable|image|max:5120',
//             'tanggal_order' => 'required|date',
//             'deadline' => 'required|date',
//             'jenis_bahan' => 'required|string|max:255',
//         ]);

//         // Ambil nama jenis_po berdasarkan ID
//         // $jenisPo = JenisPo::findOrFail($validated['jenis_po_id']);
//         // $validated['jenis_po'] = $jenisPo->nama;

//         $validated['jumlah'] = $request->jumlah ?? 0;
//         $validated['meteran'] = $request->meteran ?? 0;

//         if ($request->hasFile('file')) {
//             if ($order->file) {
//                 Storage::disk('public')->delete($order->file);
//             }
//             $validated['file'] = $request->file('file')->store('po-files', 'public');
//         }
//         $oldStage  = $order->current_stage;
//         $oldStatus = $order->stage_status;
//         $oldData   = $order->getOriginal();
//         $order->update($validated);
//         $changes = $order->getChanges();

// // Hapus field yang tidak perlu dicatat
// unset($changes['updated_at']);

// if (!empty($changes)) {

//     $changedFields = [];

//     foreach ($changes as $field => $newValue) {
//         $oldValue = $oldData[$field] ?? '-';
//         $changedFields[] = "$field: $oldValue → $newValue";
//     }

//     OrderHistory::create([
//         'po_id'       => $order->id,
//         'from_stage'  => $oldStage,
//         'to_stage'    => $order->current_stage,
//         'from_status' => $oldStatus,
//         'to_status'   => $order->stage_status,
//         'notes'       => 'Data diupdate: ' . implode(', ', $changedFields),
//         'user_id'     => auth()->id(),
//     ]);
// }

//         // Upload new images if provided
//         if ($request->hasFile('images')) {
//             foreach ($request->file('images') as $image) {
//                 $path = $image->store('po-images', 'public');
//                 PoImage::create([
//                     'po_id' => $order->id,
//                     'image_path' => $path,
//                     'original_name' => $image->getClientOriginalName(),
//                 ]);
//             }
//         }

//         return redirect()
//             ->route('purchase-orders.index')
//             ->with('success', 'Purchase Order berhasil diupdate');
//     }

    public function update(Request $request, $id)
{
    $order = PurchaseOrder::findOrFail($id);

    // 1️⃣ Validasi input
    $validated = $request->validate([
        'nama_konsumen' => 'required|string|max:255',
        'nama_po'       => 'required|string|max:255',
        'jenis_po_id'   => ['nullable', Rule::exists(JenisPo::class, 'id')],
        'file'          => 'nullable|file|max:10240',
        'jumlah'        => 'nullable|integer|min:0',
        'meteran'       => 'nullable|numeric|min:0',
        'images.*'      => 'nullable|image|max:5120',
        'tanggal_order' => 'required|date',
        'deadline'      => 'required|date',
        'jenis_bahan'   => 'required|string|max:255',
    ]);

    // 2️⃣ Ambil nama jenis_po jika ID dikirim
    if (!empty($validated['jenis_po_id'])) {
        $jenisPo = JenisPo::find($validated['jenis_po_id']);
        $validated['jenis_po'] = $jenisPo ? $jenisPo->nama : null;
    }

    // 3️⃣ Default jumlah & meteran kalau kosong
    $validated['jumlah']  = $request->jumlah ?? 0;
    $validated['meteran'] = $request->meteran ?? 0;

    // 4️⃣ Upload file PO (jika ada)
    if ($request->hasFile('file')) {
        if ($order->file) {
            Storage::disk('public')->delete($order->file);
        }
        $validated['file'] = $request->file('file')->store('po-files', 'public');
    }

    // 5️⃣ Simpan perubahan & buat history data PO
    $oldStage  = $order->current_stage;
    $oldStatus = $order->stage_status;
    $oldData   = $order->getOriginal();

    $order->update($validated); // otomatis save

    $forceFields = ['nama_konsumen', 'nama_po', 'jumlah', 'meteran', 'jenis_bahan'];
    $changedFields = [];

    foreach ($forceFields as $field) {
        $oldValue = $oldData[$field] ?? '-';
        $newValue = $order->$field;
        if ($oldValue != $newValue) {
            $changedFields[] = "$field: $oldValue → $newValue";
        }
    }

    if (!empty($changedFields)) {
        OrderHistory::create([
            'po_id'       => $order->id,
            'from_stage'  => (string) $oldStage,
            'to_stage'    => (string) $order->current_stage,
            'from_status' => (string) $oldStatus,
            'to_status'   => (string) $order->stage_status,
            'notes'       => 'Data diupdate: ' . implode(', ', $changedFields),
            'user_id'     => auth()->id(),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    // 6️⃣ Upload gambar baru + record history per gambar
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('po-images', 'public');
            PoImage::create([
                'po_id'         => $order->id,
                'image_path'    => $path,
                'original_name' => $image->getClientOriginalName(),
            ]);

            OrderHistory::create([
                'po_id'       => $order->id,
                'from_stage'  => (string) $order->current_stage,
                'to_stage'    => (string) $order->current_stage,
                'from_status' => (string) $order->stage_status,
                'to_status'   => (string) $order->stage_status,
                'notes'       => 'Menambahkan gambar: ' . $image->getClientOriginalName(),
                'user_id'     => auth()->id(),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }

    return redirect()
        ->route('purchase-orders.index')
        ->with('success', 'Purchase Order berhasil diupdate');
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
    $po_id = $image->po_id;
    $name  = $image->original_name;

    Storage::disk('public')->delete($image->image_path);
    $image->delete();

    // Catat history
    OrderHistory::create([
        'po_id'       => $po_id,
        'from_stage'  => '-',
        'to_stage'    => '-',
        'from_status' => '-',
        'to_status'   => '-',
        'notes'       => 'Menghapus gambar: ' . $name,
        'user_id'     => auth()->id(),
        'created_at'  => now(),
        'updated_at'  => now(),
    ]);

    return back()->with('success', 'Gambar berhasil dihapus');
}
    public function previewPoNumber($jenisPoId)
    {
        $number = PurchaseOrder::generatePoNumber($jenisPoId);
        return response()->json(['po_number' => $number]);
    }
}