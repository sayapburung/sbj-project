<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoImage extends Model
{
    use HasFactory;

    protected $fillable = [
    'po_id', 
    'image_path', 
    'original_name',
    'uploaded_from_stage',  // TAMBAHAN BARU
    'uploaded_by',          // TAMBAHAN BARU
    'description'           // TAMBAHAN BARU
];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    public function uploader()
{
    return $this->belongsTo(User::class, 'uploaded_by');
}

    public function scopeFromStage($query, $stage)
{
    return $query->where('uploaded_from_stage', $stage);
}
    public function scopeFromDesain($query)
    {
        return $query->where('uploaded_from_stage', 'desain');
    }

    public function scopeFromAdmin($query)
    {
        return $query->where('uploaded_from_stage', 'purchase_order');
    }

    public function getStageLabel()
    {
        $stages = [
            'purchase_order' => 'Admin PO',
            'desain' => 'Desain',
            'printing' => 'Printing',
            'press' => 'Press',
            'qc' => 'QC',
        ];

        return $stages[$this->uploaded_from_stage] ?? $this->uploaded_from_stage;
    }

    public function getBadgeColor()
    {
        $colors = [
            'purchase_order' => 'secondary',
            'desain' => 'info',
            'printing' => 'primary',
            'press' => 'warning',
            'qc' => 'dark',
        ];

        return $colors[$this->uploaded_from_stage] ?? 'secondary';
    }

    public function getBorderColor()
    {
        $colors = [
            'purchase_order' => '#ddd',
            'desain' => '#17a2b8',
            'printing' => '#007bff',
            'press' => '#ffc107',
            'qc' => '#343a40',
        ];

        return $colors[$this->uploaded_from_stage] ?? '#ddd';
    }
    public function canDelete($userId)
    {
        // User yang upload bisa hapus
        if ($this->uploaded_by == $userId) {
            return true;
        }

        // Admin bisa hapus semua
        $user = \App\Models\User::find($userId);
        if ($user && $user->hasPermission('purchase_order')) {
            return true;
        }

        return false;
    }
}