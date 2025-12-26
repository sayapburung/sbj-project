<?php

// ============================================
// app/Models/QcRejection.php - LENGKAP
// ============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QcRejection extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_id',
        'rejected_from_stage',
        'rejection_reason',
        'rejection_notes',
        'severity',
        'rejected_by',
        'rejected_at',
        'is_resolved',
        'resolved_at',
    ];

    protected $casts = [
        'rejected_at' => 'datetime',
        'resolved_at' => 'datetime',
        'is_resolved' => 'boolean',
    ];

    // ============================================
    // RELASI
    // ============================================

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    // ============================================
    // SCOPE
    // ============================================

    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    public function scopeResolved($query)
    {
        return $query->where('is_resolved', true);
    }

    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    public function scopeFromStage($query, $stage)
    {
        return $query->where('rejected_from_stage', $stage);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('rejected_by', $userId);
    }

    // ============================================
    // ACCESSOR
    // ============================================

    public function getSeverityLabelAttribute()
    {
        $labels = [
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            'critical' => 'Kritis',
        ];

        return $labels[$this->severity] ?? $this->severity;
    }

    public function getSeverityColorAttribute()
    {
        $colors = [
            'low' => 'info',
            'medium' => 'warning',
            'high' => 'danger',
            'critical' => 'dark',
        ];

        return $colors[$this->severity] ?? 'secondary';
    }

    public function getStageLabelAttribute()
    {
        $stages = [
            'desain' => 'Desain',
            'printing' => 'Printing',
            'press' => 'Press',
        ];

        return $stages[$this->rejected_from_stage] ?? $this->rejected_from_stage;
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->is_resolved) {
            return '<span class="badge bg-success">✓ Resolved</span>';
        }
        return '<span class="badge bg-danger">Unresolved</span>';
    }

    // ============================================
    // STATIC METHODS
    // ============================================

    /**
     * Record rejection dari QC
     */
    public static function recordRejection($poId, $fromStage, $reason, $notes = null, $severity = 'medium')
    {
        return self::create([
            'po_id' => $poId,
            'rejected_from_stage' => $fromStage,
            'rejection_reason' => $reason,
            'rejection_notes' => $notes,
            'severity' => $severity,
            'rejected_by' => auth()->id(),
            'rejected_at' => now(),
            'is_resolved' => false,
        ]);
    }

    /**
     * Mark rejection sebagai resolved
     */
    public function markAsResolved()
    {
        $this->update([
            'is_resolved' => true,
            'resolved_at' => now(),
        ]);

        return $this;
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    /**
     * Get durasi rejection (berapa lama belum resolved)
     */
    public function getDurationAttribute()
    {
        if ($this->is_resolved) {
            return $this->rejected_at->diffForHumans($this->resolved_at, true);
        }

        return $this->rejected_at->diffForHumans(now(), true);
    }

    /**
     * Check apakah rejection masih aktif (unresolved)
     */
    public function isActive()
    {
        return !$this->is_resolved;
    }

    /**
     * Get rejection dengan severity tertinggi untuk PO
     */
    public static function getHighestSeverityForPO($poId)
    {
        $severityOrder = ['critical' => 4, 'high' => 3, 'medium' => 2, 'low' => 1];
        
        $rejections = self::where('po_id', $poId)
            ->where('is_resolved', false)
            ->get();

        if ($rejections->isEmpty()) {
            return null;
        }

        return $rejections->sortByDesc(function($rejection) use ($severityOrder) {
            return $severityOrder[$rejection->severity] ?? 0;
        })->first();
    }

    /**
     * Get total rejection count untuk PO
     */
    public static function countForPO($poId)
    {
        return self::where('po_id', $poId)->count();
    }

    /**
     * Get unresolved rejection count untuk PO
     */
    public static function countUnresolvedForPO($poId)
    {
        return self::where('po_id', $poId)->where('is_resolved', false)->count();
    }
}