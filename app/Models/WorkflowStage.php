<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_id', 
        'stage_name', 
        'status', 
        'started_at', 
        'completed_at', 
        'handled_by', 
        'order'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}