<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPo extends Model
{
    protected $table = 'jenis_pos';
    protected $fillable = [
        'kategori',
        'kode',
        'nama',
        'is_active'
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'jenis_po_id');
    }
}
