@extends('adminlte::page')

@section('title', 'Notifikasi')

@section('content_header')
    <h1>Notifikasi Sistem</h1>
@stop

@section('content')

<div class="row mb-3">

    <div class="col-md-4">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $summary['pending'] }}</h3>
                <p>Menunggu Approval</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $summary['rejected'] }}</h3>
                <p>Pengajuan Ditolak</p>
            </div>
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $summary['low_stock'] }}</h3>
                <p>Stok Kritis</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
    </div>

</div>

<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-bell mr-1"></i> Rincian Notifikasi</h3>
    </div>
    <div class="card-body p-0">
        @if($totalLowStock > 0)
            <div class="alert alert-warning m-3 border-0 shadow-sm" style="border-radius: 10px;">
                <h5><i class="icon fas fa-exclamation-triangle"></i> Perhatian!</h5>
                Ada <strong>{{ $totalLowStock }}</strong> bahan baku yang memiliki stok kritis dan perlu segera dilakukan Purchase Order.
            </div>
        @endif

        <table class="table table-hover">
            <tbody>
                @forelse($notifications as $notif)
                    <tr>
                        <td class="text-{{ $notif['type'] }} align-middle" style="width: 60px; text-align: center;">
                            @if($notif['type'] == 'warning')
                                <i class="fas fa-clock fa-2x"></i>
                            @elseif($notif['type'] == 'danger')
                                <i class="fas fa-exclamation-circle fa-2x"></i>
                            @else
                                <i class="fas fa-info-circle fa-2x"></i>
                            @endif
                        </td>
                        <td class="align-middle">
                            <h6 class="mb-0 font-weight-bold text-{{ $notif['type'] }}">{{ $notif['message'] }}</h6>
                            <small class="text-muted">Harap periksa modul terkait untuk menindaklanjuti.</small>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3 d-block"></i>
                            <h5 class="text-muted">Semua aman! Tidak ada notifikasi baru.</h5>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@stop