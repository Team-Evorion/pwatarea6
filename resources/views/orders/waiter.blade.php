@extends('layouts.app')
@section('title','Panel del camarero')
@section('content')
<div class="page-heading"><div><div class="eyebrow">Servicio de sala</div><h1 class="mt-2 mb-1">Panel del camarero</h1><p class="page-subtitle mb-0">Atiende los pedidos listos y consulta los que tienes asignados.</p></div><div class="d-flex gap-2"><a class="btn btn-soft" href="{{ route('waiter.index') }}">Actualizar</a><a class="btn btn-brand" href="{{ route('waiter.create') }}"><i class="bi bi-plus-circle"></i> Nuevo pedido</a></div></div>
<div class="panel-card"><div class="table-responsive"><table class="table app-table"><thead><tr><th>Plato</th><th>Cliente</th><th>Cantidad</th><th>Estado</th><th>Camarero</th><th class="text-end">Acción</th></tr></thead><tbody>
@forelse($orders as $o)<tr><td><strong>{{ $o->dish?->name }}</strong><div class="muted-label">{{ $o->created_at?->format('d/m H:i') }}</div></td><td>{{ $o->user?->name }}</td><td>{{ $o->quantity }}</td><td><x-order-status :status="$o->status" /></td><td>{{ $o->waiter?->name??'Sin asignar' }}</td><td class="text-end">@if($o->status==='Ready to serve' && (!$o->waiter_id || $o->waiter_id===auth()->id()))<form method="POST" action="{{ route('waiter.serve',$o) }}">@csrf @method('PATCH')<button class="btn btn-brand btn-sm">Marcar servido</button></form>@else<span class="muted-label">—</span>@endif</td></tr>
@empty<tr><td colspan="6"><div class="empty-state">No hay pedidos asignados ni platos listos.</div></td></tr>@endforelse
</tbody></table></div></div>
@endsection
