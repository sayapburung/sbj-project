<?php

// app/Http/Controllers/CompletedOrderController.php
namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\QcRejection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompletedOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = PurchaseOrder::with([
            'images', 
            'stageInputs', 
            'histories.user', 
            'qcRejections',
            'creator'
        ])->where('active', 2); // active = 2 artinya selesai

        // Filter
        $this->applyFilters($query, $request);

        // Sorting
        $sortBy = $request->get('sort_by', 'completed_date');
        $sortOrder = $request->get('sort_order', 'desc');

        switch($sortBy) {
            case 'po_number':
                $query->orderBy('po_number', $sortOrder);
                break;
            case 'customer':
                $query->orderBy('nama_konsumen', $sortOrder);
                break;
            case 'created_date':
                $query->orderBy('created_at', $sortOrder);
                break;
            case 'completed_date':
            default:
                $query->orderBy('updated_at', $sortOrder);
                break;
        }

        $orders = $query->paginate(20);

        // Statistics
        $stats = $this->getStatistics($request);

        return view('completed-orders.index', compact('orders', 'stats'));
    }

    public function show($id)
    {
        $order = PurchaseOrder::with([
            'images.uploader', 
            'stageInputs', 
            'histories.user', 
            'qcRejections.rejectedBy',
            'creator'
        ])->where('active', 2)
          ->findOrFail($id);

        // Hitung durasi per stage dari history
        $stageDurations = $this->calculateStageDurations($order);

        // Summary data
        $summary = [
            'total_duration' => $order->created_at->diffInDays($order->updated_at),
            'total_rejections' => $order->qcRejections->count(),
            'total_images' => $order->images->count(),
            'total_history' => $order->histories->count(),
        ];

        return view('completed-orders.show', compact('order', 'stageDurations', 'summary'));
    }

    private function applyFilters($query, $request)
    {
        if ($request->filled('nama_konsumen')) {
            $query->where('nama_konsumen', 'like', '%' . $request->nama_konsumen . '%');
        }

        if ($request->filled('jenis_po')) {
            $query->where('jenis_po', 'like', '%' . $request->jenis_po . '%');
        }

        if ($request->filled('po_number')) {
            $query->where('po_number', 'like', '%' . $request->po_number . '%');
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('updated_at', [$request->date_from, $request->date_to]);
        }

        // Filter by rejection
        if ($request->filled('has_rejection')) {
            if ($request->has_rejection == 'yes') {
                $query->has('qcRejections');
            } elseif ($request->has_rejection == 'no') {
                $query->doesntHave('qcRejections');
            }
        }
    }

    private function getStatistics($request)
    {
        $query = PurchaseOrder::where('active', 2);
        $this->applyFilters($query, $request);

        $totalOrders = $query->count();
        $totalWithRejection = $query->clone()->has('qcRejections')->count();
        $totalWithoutRejection = $totalOrders - $totalWithRejection;

        // Average duration
        $avgDuration = $query->clone()
            ->select(DB::raw('AVG(DATEDIFF(updated_at, created_at)) as avg_days'))
            ->first()
            ->avg_days ?? 0;

        // Top customers
        $topCustomers = PurchaseOrder::where('active', 2)
            ->select('nama_konsumen', DB::raw('COUNT(*) as total'))
            ->groupBy('nama_konsumen')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Completion by month (last 6 months)
        $completionByMonth = PurchaseOrder::where('active', 2)
            ->select(
                DB::raw('DATE_FORMAT(updated_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('updated_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        return [
            'total_orders' => $totalOrders,
            'total_with_rejection' => $totalWithRejection,
            'total_without_rejection' => $totalWithoutRejection,
            'rejection_rate' => $totalOrders > 0 ? round(($totalWithRejection / $totalOrders) * 100, 1) : 0,
            'avg_duration' => round($avgDuration, 1),
            'top_customers' => $topCustomers,
            'completion_by_month' => $completionByMonth,
        ];
    }

    private function calculateStageDurations($order)
    {
        $histories = $order->histories()->orderBy('created_at', 'asc')->get();
        
        $durations = [];
        $stages = ['waiting_list', 'desain', 'printing', 'press', 'qc', 'pengiriman'];
        
        foreach ($stages as $stage) {
            $stageHistories = $histories->where('to_stage', $stage);
            
            if ($stageHistories->isEmpty()) {
                continue;
            }

            $firstEntry = $stageHistories->first();
            $lastExit = $histories->where('from_stage', $stage)->last();
            
            if ($firstEntry && $lastExit) {
                $duration = $firstEntry->created_at->diffInHours($lastExit->created_at);
                $durations[$stage] = [
                    'hours' => $duration,
                    'days' => round($duration / 24, 1),
                    'entered_at' => $firstEntry->created_at,
                    'exited_at' => $lastExit->created_at,
                ];
            }
        }

        return $durations;
    }

    public function exportPdf($id)
    {
        $order = PurchaseOrder::with([
            'images.uploader', 
            'stageInputs', 
            'histories.user', 
            'qcRejections.rejectedBy',
            'creator'
        ])->where('active', 2)
          ->findOrFail($id);

        $stageDurations = $this->calculateStageDurations($order);
        
        $pdf = \PDF::loadView('completed-orders.pdf', compact('order', 'stageDurations'));
        return $pdf->download("Completed-Order-{$order->po_number}.pdf");
    }
}