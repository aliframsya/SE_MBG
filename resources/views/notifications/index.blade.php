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

@if($totalLowStock > 0)
<div class="alert alert-info">
    <strong>
        {{ $totalLowStock }}
    </strong>
    bahan baku memiliki stok kritis.
</div>
@endif

    @forelse($notifications as $notif)

        <div class="alert alert-{{ $notif['type'] }}">
            {{ $notif['message'] }}
        </div>

    @empty

        <div class="alert alert-success">
            Tidak ada notifikasi
        </div>

    @endforelse

@stop