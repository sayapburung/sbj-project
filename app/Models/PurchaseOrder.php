<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number', 
        'nama_konsumen',
        'nama_po', 
        'jenis_po_id',
        'jenis_po', 
        'file', 
        'jumlah', 
        'meteran', 
        'tanggal_order', 
        'deadline',
        'jenis_bahan', 
        'current_stage', 
        'stage_status', 
        'active', 
        'created_by'
    ];

    protected $casts = [
        'tanggal_order' => 'date',
        'deadline' => 'date',
        'jumlah' => 'integer',
        'meteran' => 'decimal:2',
        'active' => 'integer',
    ];

    public function images()
    {
        return $this->hasMany(PoImage::class, 'po_id');
    }

    public function files()
    {
        return $this->hasMany(PoFile::class, 'po_id');
    }

    public function workflowStages()
    {
        return $this->hasMany(WorkflowStage::class, 'po_id');
    }

    public function stageInputs()
    {
        return $this->hasOne(StageInput::class, 'po_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

public static function generatePoNumber($jenisPoId)
{
    return DB::transaction(function () use ($jenisPoId) {

        $jenisPo = \App\Models\JenisPo::findOrFail($jenisPoId);

        $kode = strtoupper($jenisPo->kode); // PRT

        $now = Carbon::now();
        $year = $now->format('y');   // 26
        $month = $now->format('m');  // 02

        $prefix = $kode . '-' . $year . $month;

        // Ambil PO terakhir bulan ini berdasarkan prefix
        $lastPo = self::where('po_number', 'like', $prefix . '-%')
            ->lockForUpdate() // 
            ->orderByDesc('id')
            ->first();

        $lastNumber = 0;

        if ($lastPo) {
            $parts = explode('-', $lastPo->po_number);
            $lastNumber = (int) end($parts);
        }

        $newCounter = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return $prefix . '-' . $newCounter;
    });
}

    public function histories()
{
    return $this->hasMany(OrderHistory::class, 'po_id')->orderBy('created_at', 'desc');
}

    public function qcRejections()
{
    return $this->hasMany(QcRejection::class, 'po_id')->orderBy('rejected_at', 'desc');
}

public function unresolvedRejections()
{
    return $this->hasMany(QcRejection::class, 'po_id')->where('is_resolved', false);
}

public function resolvedRejections()
{
    return $this->hasMany(QcRejection::class, 'po_id')->where('is_resolved', true);
}

// Helper method untuk check apakah ada rejection aktif
public function hasActiveRejections()
{
    return $this->unresolvedRejections()->exists();
}

// Helper method untuk get rejection count
public function getRejectionCountAttribute()
{
    return $this->qcRejections()->count();
}

// Helper method untuk get unresolved rejection count
public function getUnresolvedRejectionCountAttribute()
{
    return $this->unresolvedRejections()->count();
}
protected static function booted()
{
    static::saving(function ($model) {
        $model->nama_konsumen = strtoupper($model->nama_konsumen);
    });
}
public function jenisPo()
    {
        return $this->belongsTo(JenisPo::class);
    }
}