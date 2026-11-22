<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#fcfaf7"><meta name="description" content="Restaurante Mesa & Sabor: menú y gestión de pedidos">
    <title>@yield('title','Mesa & Sabor') — Restaurante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('modules/restaurant.css') }}">
    <link rel="icon" href="data:,">
</head>
<body>
  @php $me=auth()->user(); @endphp
  <nav class="navbar navbar-expand-lg app-nav sticky-top" aria-label="Navegación principal">
    <div class="container-xl">
      <a class="navbar-brand app-brand" href="{{ route('dishes.catalog') }}"><span class="brand-mark"><i class="bi bi-cup-hot-fill"></i></span> Mesa<span class="brand-and">&</span>Sabor</a>
      <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#appNav" aria-controls="appNav" aria-expanded="false" aria-label="Abrir menú"><span class="navbar-toggler-icon"></span></button>
      <div class="collapse navbar-collapse" id="appNav">
        <ul class="navbar-nav ms-lg-5 me-auto gap-lg-2">
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('dishes.catalog','home')?'active':'' }}" href="{{ route('dishes.catalog') }}">Nuestro menú</a></li>
          @if($me?->hasRole('Administrator'))
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Panel</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('dishes.index') }}">Platos</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}">Usuarios</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.orders.index') }}">Pedidos</a></li>
          @elseif($me?->hasRole('Client'))
            <li class="nav-item"><a class="nav-link" href="{{ route('orders.index') }}">Mis pedidos</a></li>
          @elseif($me?->hasRole('Chef'))
            <li class="nav-item"><a class="nav-link" href="{{ route('kitchen.index') }}">Cocina</a></li>
          @elseif($me?->hasRole('Waiter'))
            <li class="nav-item"><a class="nav-link" href="{{ route('waiter.index') }}">Camarero</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('waiter.create') }}">Tomar pedido</a></li>
          @endif
        </ul>
        <div class="d-flex align-items-lg-center flex-wrap gap-2 mt-3 mt-lg-0">
          @auth
            <span class="account-chip"><i class="bi bi-person-circle"></i> {{ $me->name }} <small>· {{ $me->role?->name }}</small></span>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-soft btn-sm" type="submit">Salir</button></form>
          @else
            <a class="btn btn-soft btn-sm" href="{{ route('login') }}">Iniciar sesión</a>
            <a class="btn btn-brand btn-sm" href="{{ route('register') }}">Crear cuenta</a>
          @endauth
        </div>
      </div>
    </div>
  </nav>
  <main class="container-xl page-wrap">
    @if(session('success'))<div class="alert alert-success app-alert"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>@endif
    @if(session('checkout_after_login'))<div class="alert alert-info app-alert">Tu selección se conservó en este navegador. Revisa tu pedido y pulsa «Confirmar pedido».</div>@endif
    @if($errors->any())<div class="alert alert-danger app-alert"><strong>Revisa la información:</strong><ul class="mb-0 mt-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    @yield('content')
  </main>
  <footer class="app-footer"><div class="container-xl d-flex flex-wrap justify-content-between gap-2"><span><i class="bi bi-cup-hot-fill me-1"></i> Mesa & Sabor</span><small>Sistema de gestión gastronómica · Proyecto universitario</small></div></footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
