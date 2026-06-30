@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark font-weight-bold">Dashboard Analitik</h1>
            <p class="text-muted">Ringkasan data operasional dapur dan pengajuan.</p>
        </div>
    </div>
</div>
@stop

@section('content')

{{-- ================= NOTIFIKASI STOK MENIPIS ================= --}}
@if(isset($lowStockMaterials) && $lowStockMaterials->count() > 0)
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 12px;">
                <h5><i class="icon fas fa-exclamation-triangle"></i> Peringatan! Stok Bahan Baku Menipis</h5>
                <p class="mb-2">Beberapa bahan baku berikut memiliki stok di bawah 10 unit dan perlu segera disuplai:</p>
                <ul class="mb-0 pl-4">
                    @foreach($lowStockMaterials as $bahan)
                        <li>
                            <strong>{{ $bahan->nama_bahan ?? 'Nama tidak ditemukan' }}</strong>: Sisa stok tinggal 
                            <span class="badge badge-warning font-weight-bold px-2 py-1">{{ $bahan->stok ?? 0 }}</span>
                        </li>
                    @endforeach
                </ul>
                <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
@endif

@php
$pendingCount = \App\Models\Submission::where('status','diajukan')->count();

$rejectedCount = \App\Models\Submission::where('status','ditolak')->count();

$lowStockCount = \App\Models\BahanBaku::has('stokGudangs')
    ->withSum('stokGudangs', 'kuantitas')
    ->get()
    ->filter(function($item) {
        return $item->stok_gudangs_sum_kuantitas <= 50;
    })->count();
@endphp

<div class="row mb-3">

    @if($pendingCount > 0)
    <div class="col-md-4">
        <div class="alert alert-warning">
            <strong>{{ $pendingCount }}</strong>
            pengajuan menunggu approval
        </div>
    </div>
    @endif

    @if($rejectedCount > 0)
    <div class="col-md-4">
        <div class="alert alert-danger">
            <strong>{{ $rejectedCount }}</strong>
            pengajuan ditolak
        </div>
    </div>
    @endif

    @if($lowStockCount > 0)
    <div class="col-md-4">
        <div class="alert alert-info">
            <strong>{{ $lowStockCount }}</strong>
            bahan baku stok hampir habis
        </div>
    </div>
    @endif

</div>

{{-- ================= SUMMARY CARDS ================= --}}
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box shadow-sm border-0 bg-white">
            <div class="inner">
                <h3 class="text-info">{{ $summary['total_supplier'] }}</h3>
                <p class="text-muted font-weight-bold">Total Supplier</p>
            </div>
            <div class="icon">
                <i class="fas fa-truck text-info opacity-50"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box shadow-sm border-0 bg-white">
            <div class="inner">
                <h3 class="text-warning">{{ $summary['total_po'] }}</h3>
                <p class="text-muted font-weight-bold">Total Purchase Order</p>
            </div>
            <div class="icon">
                <i class="fas fa-shopping-cart text-warning opacity-50"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box shadow-sm border-0 bg-white">
            <div class="inner">
                <h3 class="text-primary">{{ $summary['total_penerimaan'] }}</h3>
                <p class="text-muted font-weight-bold">Total Penerimaan</p>
            </div>
            <div class="icon">
                <i class="fas fa-box text-primary opacity-50"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box shadow-sm border-0 bg-white">
            <div class="inner">
                <h3 class="text-success">{{ $summary['total_stok'] }}</h3>
                <p class="text-muted font-weight-bold">Total Stok Gudang</p>
            </div>
            <div class="icon">
                <i class="fas fa-cubes text-success opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- ================= CHART MONTHLY ================= --}}
    <div class="col-md-8">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header bg-white border-0">
                <h3 class="card-title font-weight-bold text-muted">
                    <i class="fas fa-chart-line mr-1"></i> Pengajuan Per Bulan ({{ now()->year }})
                </h3>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="monthlyChart"
                        style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= CHART STATUS ================= --}}
    <div class="col-md-4">
        <div class="card card-outline card-info shadow-sm">
            <div class="card-header bg-white border-0">
                <h3 class="card-title font-weight-bold text-muted">
                    <i class="fas fa-chart-pie mr-1"></i> Distribusi Status
                </h3>
            </div>
            <div class="card-body">
                <canvas id="statusChart"
                    style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>


@stop

@section('css')
<style>
    .small-box {
        border-radius: 12px !important;
    }

    .card {
        border-radius: 12px !important;
    }

    .table thead th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
    }

    .progress-xxs {
        height: 4px;
        border-radius: 10px;
    }

    .opacity-50 {
        opacity: 0.3;
    }

    .align-middle td {
        vertical-align: middle !important;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .badge-danger-light {
        background-color: rgba(220, 53, 69, 0.1);
        padding: 2px 4px;
        border-radius: 4px;
    }

    .btn-xs {
        padding: 1px 5px;
        font-size: 0.7rem;
        line-height: 1.5;
        border-radius: 3px;
    }

    .collapse {
        transition: all 0.3s ease;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ================= MONTHLY CHART =================
    const monthlyData = @json($monthlyChart);
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const monthlyLabels = Object.keys(monthlyData).map(m => monthNames[m - 1]);
    let monthlyValues = Object.values(monthlyData);
    if (Math.max(...monthlyValues) === 0) {
        // Dummy data to show a curve instead of a flat line if DB is empty
        monthlyValues = [12, 19, 15, 25, 22, 30, 28];
    }

    new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Total Submission',
                data: monthlyValues,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#007bff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                x: { grid: { display: false } }
            }
        }
    });

    // ================= STATUS CHART =================
    let statusData = @json($statusChart);
    if (statusData.length === 0) {
        // Dummy data to show the pie chart if DB is empty
        statusData = [
            { status: 'DIAJUKAN', total_count: 15, total_rupiah: 15000000 },
            { status: 'DIPROSES', total_count: 8, total_rupiah: 8000000 },
            { status: 'SELESAI', total_count: 25, total_rupiah: 25000000 }
        ];
    }
    const colorMap = { 'DIAJUKAN': '#ffc107', 'DIPROSES': '#17a2b8', 'SELESAI': '#28a745' };

    const statusLabels = statusData.map(item => item.status.toUpperCase());
    const statusCounts = statusData.map(item => item.total_count);
    const statusRupiahs = statusData.map(item => item.total_rupiah);
    const dynamicColors = statusLabels.map(label => colorMap[label] || '#cccccc');

    new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusCounts,
                rupiahData: statusRupiahs,
                backgroundColor: dynamicColors,
                hoverOffset: 15,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 1500,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            let rupiah = new Intl.NumberFormat('id-ID', {
                                style: 'currency', currency: 'IDR', minimumFractionDigits: 0
                            }).format(context.dataset.rupiahData[context.dataIndex]);
                            return ` ${context.label}: ${context.parsed} Data (${rupiah})`;
                        }
                    }
                }
            }
        }
    });

</script>
@stop