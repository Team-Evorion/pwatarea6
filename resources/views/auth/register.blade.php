@extends('layouts.app')
@section('title','Crear cuenta')
@section('content')
<div class="auth-shell"><div class="panel-card auth-card">
  <div class="auth-icon"><i class="bi bi-person-plus"></i></div><div class="eyebrow">Únete a la mesa</div><h1 class="h2 mt-2 mb-2">Crea tu cuenta</h1>
  <p class="text-muted mb-4">Tu cuenta será de cliente para realizar y seguir pedidos.</p>
  <form method="POST" action="{{ route('register.submit') }}">@csrf
    <div class="mb-3"><label class="form-label" for="name">Nombre completo</label><input id="name" name="name" class="form-control" value="{{ old('name') }}" maxlength="100" required></div>
    <div class="mb-3"><label class="form-label" for="username">Usuario</label><input id="username" name="username" class="form-control" value="{{ old('username') }}" maxlength="50" required></div>
    <div class="mb-3"><label class="form-label" for="email">Correo electrónico</label><input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" maxlength="100" required></div>
    <div class="mb-3"><label class="form-label" for="password">Contraseña</label><input id="password" type="password" name="password" class="form-control" minlength="8" autocomplete="new-password" required></div>
    <div class="mb-4"><label class="form-label" for="password_confirmation">Confirmar contraseña</label><input id="password_confirmation" type="password" name="password_confirmation" class="form-control" autocomplete="new-password" required></div>
    <button type="submit" class="btn btn-brand w-100">Crear cuenta <i class="bi bi-arrow-right ms-1"></i></button>
  </form><p class="text-center text-muted mt-4 mb-0">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
</div></div>
@endsection
