@extends('layouts.app')
@section('title','Nuestro menú')
@section('content')
<section class="hero mb-4"><div class="hero-content"><span class="hero-tag"><i class="bi bi-stars"></i> Sabor para compartir</span><h1>Momentos deliciosos, <span style="color:var(--brand)">hechos a tu gusto.</span></h1><p>Descubre nuestro menú, elige tus favoritos y sigue tu pedido desde la cocina hasta tu mesa.</p><a class="btn btn-brand mt-2" href="#platos">Explorar platos <i class="bi bi-arrow-down ms-1"></i></a></div></section>
<div class="panel-card search-panel mb-4"><div class="row g-3 align-items-center"><div class="col-lg-8"><label class="visually-hidden" for="searchInput">Buscar platos</label><div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span><input id="searchInput" class="form-control" placeholder="Busca tu plato favorito por nombre o descripción…"></div></div><div class="col-lg-4"><label class="visually-hidden" for="priceFilter">Filtrar por precio</label><select id="priceFilter" class="form-select"><option value="all">Todos los precios</option><option value="low">Menos de $10</option><option value="mid">De $10 a $20</option><option value="high">Más de $20</option></select></div></div></div>
<div class="page-heading" id="platos"><div><div class="eyebrow">Carta del restaurante</div><h2 class="mt-2 mb-0">Nuestro menú</h2></div><span class="muted-label">{{ $dishes->count() }} plato(s) en el catálogo</span></div>
@if($dishes->isEmpty())
<div class="panel-card empty-state"><div class="empty-icon"><i class="bi bi-egg-fried"></i></div><h3 class="h4">Pronto habrá algo delicioso</h3><p>El restaurante aún no ha publicado platos.</p>@auth @if(auth()->user()->hasRole('Administrator'))<a href="{{ route('dishes.create') }}" class="btn btn-brand">Crear el primer plato</a>@endif @endauth</div>
@else
<div class="menu-grid" id="dishesContainer">
@foreach($dishes as $dish)
<article class="card dish-card" data-name="{{ mb_strtolower($dish->name) }}" data-desc="{{ mb_strtolower($dish->description??'') }}" data-price="{{ $dish->price }}">
  <div class="dish-visual">
    @if($dish->image_url)
      <img src="{{ $dish->image_url }}" alt="Fotografía de {{ $dish->name }}" loading="lazy" width="640" height="420">
    @else
      <div class="dish-photo-placeholder" aria-hidden="true"><i class="bi bi-egg-fried"></i></div>
    @endif
  </div>
  <div class="dish-body d-flex flex-column">
    <div class="d-flex gap-3 justify-content-between align-items-start mb-2"><h3 class="h5 mb-0">{{ $dish->name }}</h3><span class="dish-price">${{ number_format($dish->price,2) }}</span></div>
    <p class="dish-description flex-grow-1">{{ $dish->description ?: 'Preparado con mucho cariño y los mejores ingredientes.' }}</p>
    <div class="pt-3 border-top d-flex align-items-center justify-content-between gap-2"><div><div class="muted-label mb-2">Cantidad</div><div class="quantity-picker"><button type="button" class="qty-minus" data-id="{{ $dish->id }}" aria-label="Reducir cantidad de {{ $dish->name }}">−</button><input id="qty-{{ $dish->id }}" data-id="{{ $dish->id }}" data-price="{{ $dish->price }}" type="number" value="0" min="0" max="50" readonly aria-label="Cantidad de {{ $dish->name }}"><button type="button" class="qty-plus" data-id="{{ $dish->id }}" aria-label="Aumentar cantidad de {{ $dish->name }}">+</button></div></div><span class="subtotal" id="subtotal-{{ $dish->id }}">$0.00</span></div>
  </div>
</article>
@endforeach
</div>
<div class="panel-card empty-state d-none" id="noMatches"><div class="empty-icon"><i class="bi bi-search"></i></div><h3 class="h5">Sin resultados</h3><p>Cambia el texto o el filtro para buscar otros platos.</p></div>
<form id="orderForm" method="POST" action="{{ route('orders.store') }}">@csrf<input type="hidden" name="items_json" id="itemsJson"><div class="checkout-bar"><div><h3 class="h5 mb-1">Tu selección</h3><small id="selectedSummary">Aún no has seleccionado platos.</small></div><div class="d-flex flex-wrap gap-3 align-items-center"><span class="checkout-total" id="grandTotal">$0.00</span><button type="submit" id="confirmOrder" class="btn btn-brand" disabled><i class="bi bi-bag-check me-1"></i> Confirmar pedido</button></div></div></form>
@endif
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
 const form=document.getElementById('orderForm'); if(!form) return;
 const inputs=Array.from(document.querySelectorAll('[id^="qty-"]'));
 const cards=Array.from(document.querySelectorAll('.dish-card'));
 const search=document.getElementById('searchInput'), priceFilter=document.getElementById('priceFilter');
 const button=document.getElementById('confirmOrder');
 const signedIn=@json(auth()->check()), isClient=@json(auth()->user()?->hasRole('Client')??false);
 const loginUrl=@json(route('login',['next'=>'checkout']));
 let persisted=[]; try { persisted=JSON.parse(sessionStorage.getItem('selected_dishes')||'[]'); if(!Array.isArray(persisted)) persisted=[]; }catch(e){persisted=[];}
 for(const input of inputs){const found=persisted.find(p=>Number(p.dish_id)===Number(input.dataset.id));input.value=found?Math.max(0,Math.min(50,Number(found.quantity)||0)):0;}
 function update(){let chosen=[],total=0,count=0;
   for(const input of inputs){const qty=Number(input.value), unit=Number(input.dataset.price),sub=qty*unit;document.getElementById(`subtotal-${input.dataset.id}`).textContent=`$${sub.toFixed(2)}`;
    if(qty>0){chosen.push({dish_id:Number(input.dataset.id),quantity:qty});total+=sub;count+=qty;}}
   document.getElementById('selectedSummary').textContent=count?`${count} plato(s) seleccionados`:'Aún no has seleccionado platos.';
   document.getElementById('grandTotal').textContent=`$${total.toFixed(2)}`;
   document.getElementById('itemsJson').value=JSON.stringify(chosen);
   button.disabled=chosen.length===0;
   sessionStorage.setItem('selected_dishes',JSON.stringify(chosen));
 }
 for(const action of ['plus','minus']) document.querySelectorAll(`.qty-${action}`).forEach(btn=>btn.addEventListener('click',()=>{
   const input=document.getElementById(`qty-${btn.dataset.id}`);
   input.value=Math.max(0,Math.min(50,Number(input.value)+(action==='plus'?1:-1)));update();
 }));
 const filter=()=>{const q=search.value.toLocaleLowerCase().trim(),range=priceFilter.value;let visible=0;cards.forEach(c=>{const p=Number(c.dataset.price),matches=(c.dataset.name.includes(q)||c.dataset.desc.includes(q))&&(range==='all'||(range==='low'&&p<10)||(range==='mid'&&p>=10&&p<=20)||(range==='high'&&p>20));c.hidden=!matches;c.style.display=matches?'':'none';if(matches)visible++;});document.getElementById('noMatches')?.classList.toggle('d-none',visible>0);};
 search?.addEventListener('input',filter);priceFilter?.addEventListener('change',filter);
 form.addEventListener('submit',e=>{
    if(!signedIn){e.preventDefault();location.href=loginUrl;return;}
    if(!isClient){e.preventDefault();alert('Para ordenar desde el catálogo necesitas una cuenta de Cliente.');return;}
    if(!confirm('¿Confirmas el registro de este pedido?')) e.preventDefault();
 });update();
});
</script>
@endpush
