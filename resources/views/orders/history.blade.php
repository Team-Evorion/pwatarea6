@extends('layouts.app')
@section('title','Mis pedidos')
@section('content')
<div class="page-heading"><div><div class="eyebrow">Tu experiencia</div><h1 class="mt-2 mb-1">Mis pedidos</h1><p class="page-subtitle mb-0">Consulta lo que pediste y cómo avanza su preparación.</p></div><a href="{{ route('dishes.catalog') }}" class="btn btn-brand"><i class="bi bi-plus"></i> Hacer otro pedido</a></div>
@forelse($groups as $group=>$orders)
<div class="order-group"><div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-3"><div><div class="eyebrow mb-2">Pedido #{{ strtoupper(substr($group,0,8)) }}</div><div class="muted-label">{{ $orders->first()->created_at?->format('d/m/Y H:i') }}</div></div><a class="btn btn-soft btn-sm" href="{{ route('orders.show',['group'=>$group]) }}">Ver detalle <i class="bi bi-arrow-right"></i></a></div>
@foreach($orders as $order)<div class="order-line"><span><strong>{{ $order->dish?->name }}</strong><br><small class="text-muted">{{ $order->quantity }} × ${{ number_format($order->unit_price,2) }}</small></span><div class="text-end"><x-order-status :status="$order->status" /><div class="fw-bold mt-1">${{ number_format($order->line_total,2) }}</div></div></div>@endforeach
<div class="d-flex justify-content-between mt-3"><span class="text-muted">Total</span><span class="order-total">${{ number_format($orders->sum(fn($o)=>$o->line_total),2) }}</span></div></div>
@empty<div class="panel-card empty-state"><div class="empty-icon"><i class="bi bi-bag"></i></div><h3 class="h4">Aún no tienes pedidos</h3><p>Explora el menú y realiza tu primera selección.</p><a class="btn btn-brand" href="{{ route('dishes.catalog') }}">Ver menú</a></div>@endforelse
@endsection
