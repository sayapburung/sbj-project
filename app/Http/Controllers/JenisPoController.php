<?php

namespace App\Http\Controllers;

use App\Models\JenisPO;
use Illuminate\Http\Request;

class JenisPoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = JenisPo::query();

    if ($request->search) {
        $query->where('nama', 'like', '%' . $request->search . '%')
              ->orWhere('kode', 'like', '%' . $request->search . '%');
    }

    $jenisPos = $query->latest()->paginate(10);

    return view('jenis_po.index', compact('jenisPos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('jenis_po.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required|string|max:100',
            'kode' => 'required|string|max:10',
            'nama' => 'required|string|max:255',
        ]);

        JenisPO::create($validated);

        return redirect()->route('jenis-po.index')
            ->with('success', 'Jenis PO berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jenisPo = JenisPo::findOrFail($id);
        return view('jenis_po.edit', compact('jenisPo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'kategori' => 'required|string|max:100',
            'kode' => 'required|string|max:10',
            'nama' => 'required|string|max:255',
            'is_active' => 'required|boolean'
        ]);

        $jenis_po = JenisPo::findOrFail($id);

        $jenis_po->update($validated);

        return redirect()->route('jenis-po.index')
            ->with('success', 'Jenis PO berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jenis_po = JenisPo::findOrFail($id);

        // Cek apakah masih dipakai di purchase_orders
        if ($jenis_po->purchaseOrders()->exists()) {
            return redirect()->route('jenis-po.index')
                ->with('error', 'Jenis PO tidak bisa dihapus sudah ada Purchase Order.');
        }

        $jenis_po->delete();

        return redirect()->route('jenis-po.index')
            ->with('success', 'Jenis PO berhasil dihapus.');
    }
}
