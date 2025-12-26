<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StageInput extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_id', 
        'stage_name', 
        'kiloan', 
        'meteran_press', 
        'meteran_printing',
        'meteran_desain'
    ];

    protected $casts = [
        'kiloan' => 'decimal:2',
        'meteran_press' => 'decimal:2',
        'meteran_printing' => 'decimal:2',
        'meteran_desain' => 'decimal:2'
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    public static function getMeteranByStage($poId, $stage)
    {
        $input = self::where('po_id', $poId)
            ->where('stage_name', $stage)
            ->first();

        if (!$input) {
            return null;
        }

        switch ($stage) {
            case 'desain':
                return $input->meteran_desain;
            case 'printing':
                return $input->meteran_printing;
            case 'press':
                return $input->meteran_press;
            default:
                return null;
        }
    }
     public static function getAllMeteran($poId)
    {
        $inputs = self::where('po_id', $poId)->get();
        
        $result = [
            'meteran_desain' => null,
            'meteran_printing' => null,
            'meteran_press' => null,
            'kiloan' => null,
        ];

        foreach ($inputs as $input) {
            if ($input->meteran_desain) {
                $result['meteran_desain'] = $input->meteran_desain;
            }
            if ($input->meteran_printing) {
                $result['meteran_printing'] = $input->meteran_printing;
            }
            if ($input->meteran_press) {
                $result['meteran_press'] = $input->meteran_press;
            }
            if ($input->kiloan) {
                $result['kiloan'] = $input->kiloan;
            }
        }

        return $result;
    }
}
