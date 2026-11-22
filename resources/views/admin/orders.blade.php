@extends('layouts.app')
@section('title','Supervisión de pedidos')
@section('content')
<div class="page-heading"><div><div class="eyebrow">Administración</div><h1 class="mt-2 mb-1">Pedidos</h1><p class="page-subtitle mb-0">Consulta todos los pedidos y su estado actual.</p></div><a class="btn btn-soft" href="{{ route('admin.dashboard') }}">Volver al panel</a></div>
<div class="filter-bar"><a href="{{ route('admin.orders.index') }}" class="{{ !$status?'active':'' }}">Todos</a>@foreach(['Pending'=>'Pendientes','Preparing'=>'En preparación','Ready to serve'=>'Listos','Served'=>'Servidos'] as $key=>$value)<a class="{{ $status===$key?'active':'' }}" href="{{ route('admin.orders.index',['status'=>$key]) }}">{{ $value }}</a>@endforeach</div>
<div class="panel-card"><div class="table-responsive"><table class="table app-table"><thead><tr><th>Cliente</th><th>Plato</th><th>Cantidad</th><th>Total</th><th>Estado</th><th>Fecha</th></tr></thead><tbody>
@forelse($orders as $o)<tr><td>{{ $o->user?->name }}</td><td><strong>{{ $o->dish?->name }}</strong><div class="muted-label">#{{ strtoupper(substr($o->order_group,0,8)) }}</div></td><td>{{ $o->quantity }}</td><td>${{ number_format($o->line_total,2) }}</td><td><x-order-status :status="$o->status" /></td><td>{{ $o->created_at?->format('d/m/Y H:i') }}</td></tr>
@empty<tr><td colspan="6"><div class="empty-state">No hay pedidos en esta categoría.</div></td></tr>@endforelse
</tbody></table></div><div class="mt-3">{{ $orders->links('pagination::bootstrap-5') }}</div></div>
@endsection
