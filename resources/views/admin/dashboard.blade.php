@extends('layouts.app')
@section('title','Panel administrativo')
@section('content')
<div class="page-heading"><div><div class="eyebrow">Administración</div><h1 class="mt-2 mb-1">Panel general</h1><p class="page-subtitle mb-0">Panorama actualizado de tu restaurante.</p></div><div class="d-flex flex-wrap gap-2"><a class="btn btn-soft" href="{{ route('admin.users.index') }}"><i class="bi bi-people"></i> Usuarios</a><a class="btn btn-brand" href="{{ route('dishes.index') }}"><i class="bi bi-journal-richtext"></i> Gestionar menú</a></div></div>
<div class="row g-3 mb-4">
 @foreach([['label'=>'Usuarios','number'=>$stats['users'],'icon'=>'people'],['label'=>'Platos','number'=>$stats['dishes'],'icon'=>'egg-fried'],['label'=>'Pedidos','number'=>$stats['orders'],'icon'=>'receipt'],['label'=>'Pendientes','number'=>$stats['pending'],'icon'=>'hourglass-split']] as $s)
 <div class="col-6 col-lg-3"><div class="stat-card"><span class="stat-icon"><i class="bi bi-{{ $s['icon'] }}"></i></span><div class="stat-number">{{ $s['number'] }}</div><div class="stat-label">{{ $s['label'] }}</div></div></div>
 @endforeach
</div>
<div class="row g-4 mb-4">
 <div class="col-lg-6"><div class="panel-card h-100"><h3 class="h5 mb-3">Usuarios por rol</h3><div class="row g-3">
 @foreach([['Clientes',$stats['clients']],['Chefs',$stats['chefs']],['Camareros',$stats['waiters']]] as $s)
 <div class="col-4"><div class="bg-light rounded-3 p-3 text-center"><div class="stat-number fs-3">{{ $s[1] }}</div><small class="text-muted">{{ $s[0] }}</small></div></div>@endforeach
 </div></div></div>
 <div class="col-lg-6"><div class="panel-card h-100"><h3 class="h5 mb-3">Estado de pedidos</h3>
 @foreach([['Pendientes','pending'],['En preparación','preparing'],['Listos','ready'],['Servidos','served']] as $s)
 <div class="d-flex justify-content-between py-2 border-bottom"><span>{{ $s[0] }}</span><strong>{{ $stats[$s[1]] }}</strong></div>
 @endforeach</div></div>
</div>
<div class="panel-card"><div class="page-heading mb-3"><h3 class="h5 mb-0">Actividad reciente</h3><a href="{{ route('admin.orders.index') }}" class="btn btn-soft btn-sm">Ver todos los pedidos</a></div>
 @if($recent->isEmpty())<div class="empty-state"><div class="empty-icon"><i class="bi bi-receipt"></i></div><h4 class="h5">Todavía no hay pedidos</h4><p class="mb-0">La actividad del restaurante aparecerá aquí.</p></div>
 @else <div class="table-responsive"><table class="table app-table"><thead><tr><th>Cliente</th><th>Plato</th><th>Cant.</th><th>Estado</th></tr></thead><tbody>@foreach($recent as $o)<tr><td>{{ $o->user?->name }}</td><td>{{ $o->dish?->name }}</td><td>{{ $o->quantity }}</td><td><span class="pill pill-{{ ['Pending'=>'pending','Preparing'=>'preparing','Ready to serve'=>'ready','Served'=>'served'][$o->status]??'pending' }}">{{ ['Pending'=>'Pendiente','Preparing'=>'En preparación','Ready to serve'=>'Listo','Served'=>'Servido'][$o->status]??$o->status }}</span></td></tr>@endforeach</tbody></table></div>@endif
</div>
@endsection
