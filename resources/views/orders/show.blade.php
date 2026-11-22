@extends('layouts.app')
@section('title','Detalle del pedido')
@section('content')
<a href="{{ route('orders.index') }}" class="d-inline-flex align-items-center gap-1 text-decoration-none mb-3"><i class="bi bi-arrow-left"></i> Mis pedidos</a><div class="page-heading"><div><div class="eyebrow">Seguimiento</div><h1 class="mt-2 mb-0">Pedido #{{ strtoupper(substr($group,0,8)) }}</h1></div></div>
<div class="order-group"><div class="page-heading mb-3"><h2 class="h5 mb-0">Detalle de tu compra</h2><span class="muted-label">{{ $orders->first()->created_at?->format('d/m/Y H:i') }}</span></div>
@foreach($orders as $order)<div class="order-line"><div><strong>{{ $order->dish?->name }}</strong><div class="muted-label">{{ $order->quantity }} × ${{ number_format($order->unit_price,2) }}</div></div><div class="text-end"><x-order-status :status="$order->status" /><div class="fw-bold mt-1">${{ number_format($order->line_total,2) }}</div></div></div>@endforeach
<div class="d-flex align-items-center justify-content-between mt-4"><strong>Total</strong><span class="order-total">${{ number_format($orders->sum(fn($o)=>$o->line_total),2) }}</span></div></div>
<div class="text-muted">Los estados se actualizan cuando la cocina y el camarero procesan tus platos. <a href="{{ url()->current() }}">Actualizar estado</a></div>
@endsection
@push('scripts')<script>sessionStorage.removeItem('selected_dishes');</script>@endpush
