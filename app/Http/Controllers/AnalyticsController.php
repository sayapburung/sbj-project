<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\StageInput;
use App\Models\OrderHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // ===== FILTER PARAMETERS =====
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $filterType = $request->input('filter_type', 'all');
        $customer = $request->input('customer');
        $stage = $request->input('stage');
        $dateMode = $request->input('date_mode', 'created'); // 'created' atau 'completed'

        // Set tanggal berdasarkan filter type
        if ($filterType === 'today') {
            $startDate = Carbon::today()->format('Y-m-d');
            $endDate = Carbon::today()->format('Y-m-d');
        } elseif ($filterType === 'week') {
            $startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
            $endDate = Carbon::now()->endOfWeek()->format('Y-m-d');
        } elseif ($filterType === 'month') {
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        } elseif ($filterType === 'year') {
            $startDate = Carbon::now()->startOfYear()->format('Y-m-d');
            $endDate = Carbon::now()->endOfYear()->format('Y-m-d');
        }

        // ===== QUERY BUILDER UNTUK DUA MODE =====
        
        // MODE 1: Berdasarkan PO Created (tanggal_order)
        $queryCreated = PurchaseOrder::query();
        if ($startDate && $endDate) {
            $queryCreated->whereBetween('tanggal_order', [$startDate, $endDate]);
        } elseif ($startDate) {
            $queryCreated->whereDate('tanggal_order', '>=', $startDate);
        } elseif ($endDate) {
            $queryCreated->whereDate('tanggal_order', '<=', $endDate);
        }
        if ($customer) $queryCreated->where('nama_konsumen', $customer);
        if ($stage) $queryCreated->where('current_stage', $stage);

        // MODE 2: Berdasarkan Order Completed (order_histories dengan to_stage = 'selesai')
        $queryCompleted = PurchaseOrder::query()
            ->join('order_histories', 'purchase_orders.id', '=', 'order_histories.po_id')
            ->where('order_histories.to_stage', 'selesai')
            ->select('purchase_orders.*', 'order_histories.created_at as completion_date');
        
        if ($startDate && $endDate) {
            $queryCompleted->whereBetween(DB::raw('DATE(order_histories.created_at)'), [$startDate, $endDate]);
        } elseif ($startDate) {
            $queryCompleted->whereDate('order_histories.created_at', '>=', $startDate);
        } elseif ($endDate) {
            $queryCompleted->whereDate('order_histories.created_at', '<=', $endDate);
        }
        if ($customer) $queryCompleted->where('purchase_orders.nama_konsumen', $customer);
        if ($stage) $queryCompleted->where('purchase_orders.current_stage', $stage);

        // ===== SUMMARY CARDS =====
        $totalPoThisMonth = PurchaseOrder::whereMonth('tanggal_order', now()->month)
            ->whereYear('tanggal_order', now()->year)
            ->count();
        
        $totalPoActive = PurchaseOrder::where('active', 1)->count();
        
        $totalPoFinished = PurchaseOrder::where('stage_status', 'selesai')->count();
        
        $totalCustomers = PurchaseOrder::distinct()->count('nama_konsumen');

        // Total dalam filter
        if ($dateMode === 'completed') {
            $totalPoFiltered = (clone $queryCompleted)->distinct('purchase_orders.id')->count('purchase_orders.id');
        } else {
            $totalPoFiltered = (clone $queryCreated)->count();
        }

        // ===== RATA-RATA LEAD TIME =====
        // Menggunakan order_histories.created_at (to_stage=selesai) - purchase_orders.tanggal_order
        $avgLeadTimeQuery = DB::table('order_histories')
            ->join('purchase_orders', 'purchase_orders.id', '=', 'order_histories.po_id')
            ->where('order_histories.to_stage', 'selesai');
        
        if ($dateMode === 'completed') {
            // Filter berdasarkan tanggal selesai
            if ($startDate && $endDate) {
                $avgLeadTimeQuery->whereBetween(DB::raw('DATE(order_histories.created_at)'), [$startDate, $endDate]);
            } elseif ($startDate) {
                $avgLeadTimeQuery->whereDate('order_histories.created_at', '>=', $startDate);
            } elseif ($endDate) {
                $avgLeadTimeQuery->whereDate('order_histories.created_at', '<=', $endDate);
            }
        } else {
            // Filter berdasarkan tanggal order dibuat
            if ($startDate && $endDate) {
                $avgLeadTimeQuery->whereBetween('purchase_orders.tanggal_order', [$startDate, $endDate]);
            } elseif ($startDate) {
                $avgLeadTimeQuery->whereDate('purchase_orders.tanggal_order', '>=', $startDate);
            } elseif ($endDate) {
                $avgLeadTimeQuery->whereDate('purchase_orders.tanggal_order', '<=', $endDate);
            }
        }
        
        if ($customer) $avgLeadTimeQuery->where('purchase_orders.nama_konsumen', $customer);
        
        $avgLeadTime = $avgLeadTimeQuery->selectRaw('AVG(DATEDIFF(order_histories.created_at, purchase_orders.tanggal_order)) as avg_days')
            ->value('avg_days');

        // ===== PO ON TIME VS LATE =====
        // Menggunakan deadline dari purchase_orders vs order_histories.created_at (to_stage=selesai)
        $poOnTimeQuery = DB::table('purchase_orders')
            ->join('order_histories', 'purchase_orders.id', '=', 'order_histories.po_id')
            ->where('order_histories.to_stage', 'selesai')
            ->whereNotNull('purchase_orders.deadline')
            ->whereRaw('DATE(order_histories.created_at) <= purchase_orders.deadline');
        
        if ($dateMode === 'completed') {
            if ($startDate && $endDate) {
                $poOnTimeQuery->whereBetween(DB::raw('DATE(order_histories.created_at)'), [$startDate, $endDate]);
            } elseif ($startDate) {
                $poOnTimeQuery->whereDate('order_histories.created_at', '>=', $startDate);
            } elseif ($endDate) {
                $poOnTimeQuery->whereDate('order_histories.created_at', '<=', $endDate);
            }
        } else {
            if ($startDate && $endDate) {
                $poOnTimeQuery->whereBetween('purchase_orders.tanggal_order', [$startDate, $endDate]);
            } elseif ($startDate) {
                $poOnTimeQuery->whereDate('purchase_orders.tanggal_order', '>=', $startDate);
            } elseif ($endDate) {
                $poOnTimeQuery->whereDate('purchase_orders.tanggal_order', '<=', $endDate);
            }
        }
        
        if ($customer) $poOnTimeQuery->where('purchase_orders.nama_konsumen', $customer);
        $poOnTime = $poOnTimeQuery->count();

        $poLateQuery = DB::table('purchase_orders')
            ->join('order_histories', 'purchase_orders.id', '=', 'order_histories.po_id')
            ->where('order_histories.to_stage', 'selesai')
            ->whereNotNull('purchase_orders.deadline')
            ->whereRaw('DATE(order_histories.created_at) > purchase_orders.deadline');
        
        if ($dateMode === 'completed') {
            if ($startDate && $endDate) {
                $poLateQuery->whereBetween(DB::raw('DATE(order_histories.created_at)'), [$startDate, $endDate]);
            } elseif ($startDate) {
                $poLateQuery->whereDate('order_histories.created_at', '>=', $startDate);
            } elseif ($endDate) {
                $poLateQuery->whereDate('order_histories.created_at', '<=', $endDate);
            }
        } else {
            if ($startDate && $endDate) {
                $poLateQuery->whereBetween('purchase_orders.tanggal_order', [$startDate, $endDate]);
            } elseif ($startDate) {
                $poLateQuery->whereDate('purchase_orders.tanggal_order', '>=', $startDate);
            } elseif ($endDate) {
                $poLateQuery->whereDate('purchase_orders.tanggal_order', '<=', $endDate);
            }
        }
        
        if ($customer) $poLateQuery->where('purchase_orders.nama_konsumen', $customer);
        $poLate = $poLateQuery->count();

        // Completion Rate
        $completionRate = $totalPoFiltered > 0 ? round(($totalPoFinished / $totalPoFiltered) * 100, 1) : 0;

        // Total Revenue (jika ada kolom harga/nilai)
        $totalRevenue = 0;
        if (DB::getSchemaBuilder()->hasColumn('purchase_orders', 'nilai_order')) {
            if ($dateMode === 'completed') {
                $totalRevenue = DB::table('purchase_orders')
                    ->join('order_histories', 'purchase_orders.id', '=', 'order_histories.po_id')
                    ->where('order_histories.to_stage', 'selesai');
                
                if ($startDate && $endDate) {
                    $totalRevenue->whereBetween(DB::raw('DATE(order_histories.created_at)'), [$startDate, $endDate]);
                } elseif ($startDate) {
                    $totalRevenue->whereDate('order_histories.created_at', '>=', $startDate);
                } elseif ($endDate) {
                    $totalRevenue->whereDate('order_histories.created_at', '<=', $endDate);
                }
                if ($customer) $totalRevenue->where('purchase_orders.nama_konsumen', $customer);
                
                $totalRevenue = $totalRevenue->sum('purchase_orders.nilai_order');
            } else {
                $totalRevenue = (clone $queryCreated)->sum('nilai_order');
            }
        }

        // ===== SUMMARY STAGE =====
        if ($dateMode === 'completed') {
            $stageStats = DB::table('order_histories')
                ->join('purchase_orders', 'purchase_orders.id', '=', 'order_histories.po_id')
                ->where('order_histories.to_stage', 'selesai')
                ->select('purchase_orders.current_stage', DB::raw('COUNT(DISTINCT purchase_orders.id) as total'));
            
            if ($startDate && $endDate) {
                $stageStats->whereBetween(DB::raw('DATE(order_histories.created_at)'), [$startDate, $endDate]);
            } elseif ($startDate) {
                $stageStats->whereDate('order_histories.created_at', '>=', $startDate);
            } elseif ($endDate) {
                $stageStats->whereDate('order_histories.created_at', '<=', $endDate);
            }
            if ($customer) $stageStats->where('purchase_orders.nama_konsumen', $customer);
            
            $stageStats = $stageStats->groupBy('purchase_orders.current_stage')
                ->orderByDesc('total')
                ->get();
        } else {
            $stageStats = (clone $queryCreated)
                ->select('current_stage', DB::raw('COUNT(*) as total'))
                ->groupBy('current_stage')
                ->orderByDesc('total')
                ->get();
        }

        // ===== TOP MATERIALS =====
        $bahanUsageQuery = DB::table('stage_inputs')
            ->join('purchase_orders', 'purchase_orders.id', '=', 'stage_inputs.po_id')
            ->select(
                'purchase_orders.jenis_bahan',
                DB::raw('SUM(COALESCE(stage_inputs.meteran_desain,0)) AS total_desain'),
                DB::raw('SUM(COALESCE(stage_inputs.meteran_printing,0)) AS total_printing'),
                DB::raw('SUM(COALESCE(stage_inputs.meteran_press,0)) AS total_press')
            );

        if ($dateMode === 'completed') {
            $bahanUsageQuery->join('order_histories', 'purchase_orders.id', '=', 'order_histories.po_id')
                ->where('order_histories.to_stage', 'selesai');
            
            if ($startDate && $endDate) {
                $bahanUsageQuery->whereBetween(DB::raw('DATE(order_histories.created_at)'), [$startDate, $endDate]);
            } elseif ($startDate) {
                $bahanUsageQuery->whereDate('order_histories.created_at', '>=', $startDate);
            } elseif ($endDate) {
                $bahanUsageQuery->whereDate('order_histories.created_at', '<=', $endDate);
            }
        } else {
            if ($startDate && $endDate) {
                $bahanUsageQuery->whereBetween('purchase_orders.tanggal_order', [$startDate, $endDate]);
            } elseif ($startDate) {
                $bahanUsageQuery->whereDate('purchase_orders.tanggal_order', '>=', $startDate);
            } elseif ($endDate) {
                $bahanUsageQuery->whereDate('purchase_orders.tanggal_order', '<=', $endDate);
            }
        }

        if ($customer) $bahanUsageQuery->where('purchase_orders.nama_konsumen', $customer);

        $bahanUsage = $bahanUsageQuery
            ->groupBy('purchase_orders.jenis_bahan')
            ->orderByRaw("
                SUM(COALESCE(stage_inputs.meteran_desain,0)) +
                SUM(COALESCE(stage_inputs.meteran_printing,0)) +
                SUM(COALESCE(stage_inputs.meteran_press,0)) DESC
            ")
            ->get();

        // ===== TOP CUSTOMERS =====
        if ($dateMode === 'completed') {
            $topCustomers = DB::table('purchase_orders')
                ->join('order_histories', 'purchase_orders.id', '=', 'order_histories.po_id')
                ->where('order_histories.to_stage', 'selesai')
                ->select('purchase_orders.nama_konsumen', DB::raw('COUNT(DISTINCT purchase_orders.id) as total'));
            
            if ($startDate && $endDate) {
                $topCustomers->whereBetween(DB::raw('DATE(order_histories.created_at)'), [$startDate, $endDate]);
            } elseif ($startDate) {
                $topCustomers->whereDate('order_histories.created_at', '>=', $startDate);
            } elseif ($endDate) {
                $topCustomers->whereDate('order_histories.created_at', '<=', $endDate);
            }
            
            $topCustomers = $topCustomers->groupBy('purchase_orders.nama_konsumen')
                ->orderByDesc('total')
                ->limit(10)
                ->get();
        } else {
            $topCustomers = (clone $queryCreated)
                ->select('nama_konsumen', DB::raw('COUNT(*) as total'))
                ->groupBy('nama_konsumen')
                ->orderByDesc('total')
                ->limit(10)
                ->get();
        }

        // ===== USER PRODUCTIVITY =====
        if ($dateMode === 'completed') {
            $rawStats = DB::table('purchase_orders')
                ->join('order_histories', 'purchase_orders.id', '=', 'order_histories.po_id')
                ->where('order_histories.to_stage', 'selesai')
                ->select('purchase_orders.created_by', DB::raw('COUNT(DISTINCT purchase_orders.id) as total'));
            
            if ($startDate && $endDate) {
                $rawStats->whereBetween(DB::raw('DATE(order_histories.created_at)'), [$startDate, $endDate]);
            } elseif ($startDate) {
                $rawStats->whereDate('order_histories.created_at', '>=', $startDate);
            } elseif ($endDate) {
                $rawStats->whereDate('order_histories.created_at', '<=', $endDate);
            }
            
            $rawStats = $rawStats->groupBy('purchase_orders.created_by')
                ->orderByDesc('total')
                ->get();
        } else {
            $rawStats = (clone $queryCreated)
                ->select('created_by', DB::raw('COUNT(*) as total'))
                ->groupBy('created_by')
                ->orderByDesc('total')
                ->get();
        }

        $userStats = $rawStats->map(function ($row) {
            $user = User::find($row->created_by);
            return [
                'name' => $user ? $user->name : 'User #' . $row->created_by,
                'total' => $row->total,
            ];
        });

        // ===== DATA UNTUK FILTER DROPDOWN =====
        $allCustomers = PurchaseOrder::distinct()->pluck('nama_konsumen')->sort();
        $allStages = PurchaseOrder::distinct()->whereNotNull('current_stage')->pluck('current_stage')->sort();

        return view('analytics.index', compact(
            'totalPoThisMonth',
            'totalPoActive',
            'totalPoFinished',
            'totalCustomers',
            'totalPoFiltered',
            'avgLeadTime',
            'poOnTime',
            'poLate',
            'completionRate',
            'totalRevenue',
            'stageStats',
            'topCustomers',
            'userStats',
            'bahanUsage',
            'allCustomers',
            'allStages',
            'startDate',
            'endDate',
            'filterType',
            'customer',
            'stage',
            'dateMode'
        ));
    }
}