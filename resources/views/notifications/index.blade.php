@extends('adminlte::page')

@section('title', 'Notifikasi')

@section('content_header')
    <h1>Notifikasi Sistem</h1>
@stop

@section('content')

@if($totalLowStock > 0)
<div class="alert alert-warning">
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