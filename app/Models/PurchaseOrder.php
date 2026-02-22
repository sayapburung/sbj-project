<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number', 
        'nama_konsumen', 
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

    public static function generatePoNumber()
    {
        $date = date('Ymd');
        $count = self::whereDate('created_at', Carbon::today())->count() + 1;
        return 'PO-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
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
}