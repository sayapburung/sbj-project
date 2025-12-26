<?php

// app/Models/OrderHistory.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    use HasFactory;

    public $timestamps = false; // Karena kita hanya pakai created_at

    protected $fillable = [
        'po_id',
        'from_stage',
        'to_stage',
        'from_status',
        'to_status',
        'notes',
        'user_id',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ============================================
    // RELASI
    // ============================================

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ============================================
    // ACCESSOR - untuk mendapatkan label yang readable
    // ============================================

    public function getFromStageLabelAttribute()
    {
        return $this->getStageName($this->from_stage);
    }

    public function getToStageLabelAttribute()
    {
        return $this->getStageName($this->to_stage);
    }

    private function getStageName($stage)
    {
        if (!$stage) {
            return '-';
        }

        $stages = [
            'waiting_list' => 'Waiting List',
            'desain' => 'Desain',
            'printing' => 'Printing',
            'press' => 'Press',
            'qc' => 'QC',
            'pengiriman' => 'Pengiriman',
            'selesai' => 'Selesai',
        ];

        return $stages[$stage] ?? ucfirst($stage);
    }

    public function getStageColorAttribute()
    {
        $colors = [
            'waiting_list' => 'secondary',
            'desain' => 'info',
            'printing' => 'primary',
            'press' => 'warning',
            'qc' => 'dark',
            'pengiriman' => 'purple',
            'selesai' => 'success',
        ];

        return $colors[$this->to_stage] ?? 'secondary';
    }

    // ============================================
    // STATIC METHOD - untuk create history dengan mudah
    // ============================================

    /**
     * Record transisi stage atau perubahan status
     * 
     * @param int $poId - ID Purchase Order
     * @param string|null $fromStage - Stage sebelumnya (null jika baru dibuat)
     * @param string $toStage - Stage tujuan
     * @param string|null $fromStatus - Status sebelumnya
     * @param string $toStatus - Status baru
     * @param string|null $notes - Catatan tambahan
     * @return OrderHistory
     */
    public static function recordTransition($poId, $fromStage, $toStage, $fromStatus, $toStatus, $notes = null)
    {
        return self::create([
            'po_id' => $poId,
            'from_stage' => $fromStage,
            'to_stage' => $toStage,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'notes' => $notes,
            'user_id' => auth()->id(),
            'created_at' => now(),
        ]);
    }

    /**
     * Helper method untuk record pembuatan PO baru
     */
    public static function recordCreation($poId, $notes = 'Purchase Order dibuat')
    {
        return self::recordTransition(
            $poId,
            null, // from_stage = null
            'waiting_list',
            null, // from_status = null
            'pending',
            $notes
        );
    }

    /**
     * Helper method untuk record perpindahan stage
     */
    public static function recordStageMove($order, $toStage, $notes = null)
    {
        if (!$notes) {
            $notes = 'Order dipindahkan dari ' . $order->current_stage . ' ke ' . $toStage;
        }

        return self::recordTransition(
            $order->id,
            $order->current_stage,
            $toStage,
            $order->stage_status,
            $toStage === 'selesai' ? 'selesai' : 'pending',
            $notes
        );
    }

    /**
     * Helper method untuk record perubahan status (tanpa pindah stage)
     */
    public static function recordStatusChange($order, $toStatus, $notes = null)
    {
        if (!$notes) {
            $notes = 'Status diubah menjadi ' . $toStatus;
        }

        return self::recordTransition(
            $order->id,
            $order->current_stage,
            $order->current_stage, // tetap di stage yang sama
            $order->stage_status,
            $toStatus,
            $notes
        );
    }
}

// ============================================
// UPDATE: app/Models/PurchaseOrder.php
// Tambahkan relasi ini di dalam class PurchaseOrder
// ============================================

// Di app/Models/PurchaseOrder.php, tambahkan method ini: