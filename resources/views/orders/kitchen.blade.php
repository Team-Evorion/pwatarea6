@extends('layouts.app')
@section('title','Panel de cocina')
@section('content')
<div class="page-heading"><div><div class="eyebrow">Operaciones</div><h1 class="mt-2 mb-1">Panel de cocina <span style="font-size:.7em">👨‍🍳</span></h1><p class="page-subtitle mb-0">Gestiona las preparaciones en el orden de llegada.</p></div><a class="btn btn-soft" href="{{ route('kitchen.index') }}"><i class="bi bi-arrow-clockwise"></i> Actualizar</a></div>
@php $active=$orders->whereIn('status',[\App\Models\Order::PENDING,\App\Models\Order::PREPARING]); @endphp
<div class="row g-3 mb-4"><div class="col-md-6"><div class="stat-card"><span class="stat-label">Pendientes</span><div class="stat-number">{{ $orders->where('status',\App\Models\Order::PENDING)->count() }}</div></div></div><div class="col-md-6"><div class="stat-card"><span class="stat-label">En preparación</span><div class="stat-number">{{ $orders->where('status',\App\Models\Order::PREPARING)->count() }}</div></div></div></div>
<div class="panel-card"><h2 class="h5 mb-3">Cola de preparación</h2>
@if($active->isEmpty())<div class="empty-state"><div class="empty-icon"><i class="bi bi-check2-all"></i></div><h3 class="h5">¡Todo al día!</h3><p>No hay preparaciones pendientes.</p></div>
@else<div class="table-responsive"><table class="table app-table"><thead><tr><th>Plato</th><th>Cliente</th><th>Cantidad</th><th>Estado</th><th class="text-end">Acción</th></tr></thead><tbody>
@foreach($active as $o)<tr><td><strong>{{ $o->dish?->name }}</strong><div class="muted-label">{{ $o->created_at?->format('H:i') }}</div></td><td>{{ $o->user?->name }}</td><td>{{ $o->quantity }}</td><td><x-order-status :status="$o->status" /></td><td class="text-end"><form action="{{ route('kitchen.transition',['order'=>$o,'action'=>$o->status==='Pending'?'start':'ready']) }}" method="POST">@csrf @method('PATCH')<button class="btn btn-brand btn-sm">{{ $o->status==='Pending'?'Iniciar preparación':'Marcar listo' }}</button></form></td></tr>@endforeach
</tbody></table></div>@endif</div>
@endsection
