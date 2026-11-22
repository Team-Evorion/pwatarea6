@extends('layouts.app')
@section('title','Gestión de platos')
@section('content')
<div class="page-heading"><div><div class="eyebrow">Administración del menú</div><h1 class="mt-2 mb-1">Nuestros platos</h1><p class="page-subtitle mb-0">Edita el catálogo que ven tus clientes.</p></div><div class="d-flex flex-wrap gap-2"><a class="btn btn-soft" href="{{ route('dishes.catalog') }}">Ver catálogo</a><a class="btn btn-brand" href="{{ route('dishes.create') }}"><i class="bi bi-plus-lg"></i> Nuevo plato</a></div></div>
<div class="panel-card"><div class="table-responsive"><table class="table app-table mb-0"><thead><tr><th>Plato</th><th>Descripción</th><th>Precio</th><th class="text-end">Acciones</th></tr></thead><tbody>@forelse($dishes as $dish)<tr><td><div class="d-flex align-items-center gap-3">
  <div class="dish-list-thumb">
    @if($dish->image_url)
      <img src="{{ $dish->image_url }}" alt="Foto de {{ $dish->name }}" loading="lazy" width="68" height="56">
    @else
      <i class="bi bi-image" aria-hidden="true"></i>
    @endif
  </div>
  <strong>{{ $dish->name }}</strong>
</div></td><td>{{ \Illuminate\Support\Str::limit($dish->description??'Sin descripción',70) }}</td><td><span class="dish-price">${{ number_format($dish->price,2) }}</span></td><td class="text-end"><div class="d-flex justify-content-end flex-wrap gap-2"><a class="btn btn-soft btn-sm" href="{{ route('dishes.edit',$dish) }}">Editar</a><form action="{{ route('dishes.destroy',$dish) }}" method="POST" onsubmit="return confirm('¿Eliminar este plato? Solo se permite si no tiene pedidos asociados.');">@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm" type="submit">Eliminar</button></form></div></td></tr>@empty<tr><td colspan="4"><div class="empty-state">Todavía no has registrado platos.</div></td></tr>@endforelse</tbody></table></div><div class="mt-3">{{ $dishes->links('pagination::bootstrap-5') }}</div></div>
@endsection
