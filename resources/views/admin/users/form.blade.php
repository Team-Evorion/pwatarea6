@extends('layouts.app')
@section('title',$user->exists?'Editar usuario':'Nuevo usuario')
@section('content')
<div class="form-section"><a href="{{ route('admin.users.index') }}" class="d-inline-flex align-items-center gap-1 text-decoration-none mb-3"><i class="bi bi-arrow-left"></i> Volver a usuarios</a><div class="page-heading"><div><div class="eyebrow">Administración</div><h1 class="mt-2">{{ $user->exists?'Editar usuario':'Nuevo usuario' }}</h1></div></div>
<div class="panel-card p-md-4"><form action="{{ $user->exists?route('admin.users.update',$user):route('admin.users.store') }}" method="POST">@csrf @if($user->exists)@method('PUT')@endif
<div class="row g-3">
<div class="col-md-6"><label class="form-label" for="name">Nombre completo</label><input id="name" name="name" required maxlength="100" class="form-control" value="{{ old('name',$user->name) }}"></div>
<div class="col-md-6"><label class="form-label" for="username">Usuario</label><input id="username" name="username" required maxlength="50" class="form-control" value="{{ old('username',$user->username) }}"></div>
<div class="col-md-6"><label class="form-label" for="email">Correo</label><input id="email" type="email" name="email" required maxlength="100" class="form-control" value="{{ old('email',$user->email) }}"></div>
<div class="col-md-6"><label class="form-label" for="role_id">Rol</label><select id="role_id" name="role_id" required class="form-select"><option value="">Selecciona un rol</option>@foreach($roles as $role)<option value="{{ $role->id }}" @selected((string)old('role_id',$user->role_id)===(string)$role->id)>{{ $role->name }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label" for="password">Contraseña {{ $user->exists?'(dejar vacía para conservar)':'' }}</label><input id="password" type="password" name="password" class="form-control" {{ $user->exists?'':'required' }} autocomplete="new-password"></div>
<div class="col-md-6"><label class="form-label" for="password_confirmation">Confirmar contraseña</label><input id="password_confirmation" type="password" name="password_confirmation" class="form-control" {{ $user->exists?'':'required' }} autocomplete="new-password"></div>
</div><div class="d-flex justify-content-end gap-2 mt-4"><a class="btn btn-soft" href="{{ route('admin.users.index') }}">Cancelar</a><button type="submit" class="btn btn-brand">{{ $user->exists?'Guardar cambios':'Crear usuario' }}</button></div>
</form></div></div>
@endsection
