<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Total counts per stage
        $counts = [
            'waiting_list' => PurchaseOrder::where('current_stage', 'waiting_list')->where('active', 1)->count(),
            'desain' => PurchaseOrder::where('current_stage', 'desain')->where('active', 1)->count(),
            'printing' => PurchaseOrder::where('current_stage', 'printing')->where('active', 1)->count(),
            'press' => PurchaseOrder::where('current_stage', 'press')->where('active', 1)->count(),
            'qc' => PurchaseOrder::where('current_stage', 'qc')->where('active', 1)->count(),
            'pengiriman' => PurchaseOrder::where('current_stage', 'pengiriman')->where('active', 1)->count(),
            'selesai' => PurchaseOrder::where('active', 2)->count(),
        ];

        // Breakdown by stage_status for each stage
        $statusBreakdown = [];
        $stages = ['waiting_list', 'desain', 'printing', 'press', 'qc', 'pengiriman'];
        
        foreach ($stages as $stage) {
            $statusBreakdown[$stage] = [
                'pending' => PurchaseOrder::where('current_stage', $stage)
                    ->where('stage_status', 'pending')
                    ->where('active', 1)
                    ->count(),
                'start' => PurchaseOrder::where('current_stage', $stage)
                    ->where('stage_status', 'start')
                    ->where('active', 1)
                    ->count(),
                'progress' => PurchaseOrder::where('current_stage', $stage)
                    ->where('stage_status', 'progress')
                    ->where('active', 1)
                    ->count(),
                'selesai' => PurchaseOrder::where('current_stage', $stage)
                    ->where('stage_status', 'selesai')
                    ->where('active', 1)
                    ->count(),
            ];
        }

        return view('dashboard', compact('counts', 'statusBreakdown'));
    }
}