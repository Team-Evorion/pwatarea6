@extends('layouts.app')
@section('title','Ingresar')
@section('content')
<div class="auth-shell"><div class="panel-card auth-card">
  <div class="auth-icon"><i class="bi bi-person-check"></i></div>
  <div class="eyebrow">Bienvenido de nuevo</div><h1 class="h2 mt-2 mb-2">Inicia sesión</h1>
  <p class="text-muted mb-4">Entra para continuar con tu experiencia gastronómica.</p>
  <form method="POST" action="{{ route('login.submit') }}" novalidate>@csrf
    <input type="hidden" name="next" value="{{ request('next')==='checkout'?'checkout':'' }}">
    <div class="mb-3"><label class="form-label" for="email">Correo electrónico</label><input id="email" name="email" type="email" autocomplete="email" class="form-control" value="{{ old('email') }}" required></div>
    <div class="mb-3"><label class="form-label" for="password">Contraseña</label><input id="password" name="password" type="password" autocomplete="current-password" class="form-control" required></div>
    <label class="form-check mb-4"><input class="form-check-input" type="checkbox" name="remember" value="1"><span class="form-check-label">Recordarme</span></label>
    <button class="btn btn-brand w-100" type="submit">Ingresar <i class="bi bi-arrow-right ms-1"></i></button>
  </form><p class="text-center text-muted mt-4 mb-0">¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a></p>
</div></div>
@endsection
