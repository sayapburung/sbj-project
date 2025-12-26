@extends('layouts.app')

@section('title', 'Analytics & Reports')

@section('content')
<div class="container">

    <h2 class="mb-4">Analytics & Reports</h2>

    {{-- === SUMMARY CARDS === --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5>Total PO Bulan Ini</h5>
                    <h3 class="fw-bold">{{ $totalPoThisMonth }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5>PO Aktif</h5>
                    <h3 class="fw-bold">{{ $totalPoActive }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5>PO Selesai</h5>
                    <h3 class="fw-bold">{{ $totalPoFinished }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- === GRAFIK PER STAGE === --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-dark text-white">Jumlah PO Berdasarkan Stage</div>
        <div class="card-body">
            <canvas id="stageChart" height="120"></canvas>
        </div>
    </div>

    {{-- === GRAFIK PENGGUNAAN BAHAN === --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-dark text-white">Konsumsi Bahan</div>
        <div class="card-body">
            <canvas id="materialChart" height="120"></canvas>
        </div>
    </div>

    {{-- === GRAFIK PO PER CUSTOMER === --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-dark text-white">Jumlah PO per Customer</div>
        <div class="card-body">
            <canvas id="customerChart" height="120"></canvas>
        </div>
    </div>

    {{-- === TOP CUSTOMER === --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-dark text-white">Top Customer</div>
        <div class="card-body">
            <canvas id="topCustomerChart" height="120"></canvas>
        </div>
    </div>

    {{-- === ANALYTICS PER USER === --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-dark text-white">Analytic per User (Created By)</div>
        <div class="card-body">
            <canvas id="userChart" height="120"></canvas>
        </div>
    </div>

    {{-- === DAILY PO TREND === --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-dark text-white">PO Masuk per Hari</div>
        <div class="card-body">
            <canvas id="dailyChart" height="120"></canvas>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // === Stage Chart ===
    new Chart(document.getElementById("stageChart"), {
        type: 'bar',
        data: {
            labels: @json($stageLabels),
            datasets: [{
                label: "Jumlah PO",
                data: @json($stageData),
                backgroundColor: "rgba(0, 123, 255, 0.7)"
            }]
        }
    });

    // === Material Chart ===
    new Chart(document.getElementById("materialChart"), {
        type: 'bar',
        data: {
            labels: @json($materialLabels),
            datasets: [{
                label: "Jumlah",
                data: @json($materialData),
                backgroundColor: "rgba(40, 167, 69, 0.7)"
            }]
        }
    });

    // === Customer Chart ===
    new Chart(document.getElementById("customerChart"), {
        type: 'bar',
        data: {
            labels: @json($customerLabels),
            datasets: [{
                label: "Jumlah PO",
                data: @json($customerData),
                backgroundColor: "rgba(255, 193, 7, 0.7)"
            }]
        }
    });

    // === Top Customer ===
    new Chart(document.getElementById("topCustomerChart"), {
        type: 'bar',
        data: {
            labels: @json($topCustomerLabels),
            datasets: [{
                label: "Total PO",
                data: @json($topCustomerData),
                backgroundColor: "rgba(220, 53, 69, 0.7)"
            }]
        }
    });

    // === User Analytics ===
    new Chart(document.getElementById("userChart"), {
        type: 'bar',
        data: {
            labels: @json($userLabels),
            datasets: [{
                label: "PO Dibuat",
                data: @json($userData),
                backgroundColor: "rgba(23, 162, 184, 0.7)"
            }]
        }
    });

    // === Daily Chart ===
    new Chart(document.getElementById("dailyChart"), {
        type: 'line',
        data: {
            labels: @json($poDailyLabels),
            datasets: [{
                label: "PO Masuk",
                data: @json($poDailyData),
                borderColor: "rgba(0, 123, 255, 1)",
                fill: false
            }]
        }
    });
</script>
@endsection
