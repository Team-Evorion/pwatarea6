@extends('layouts.app')
@section('title','Registrar plato')
@section('content')
<div class="form-section"><a href="{{ route('dishes.index') }}" class="d-inline-flex align-items-center gap-1 text-decoration-none mb-3"><i class="bi bi-arrow-left"></i> Volver a platos</a><div class="eyebrow">Administración</div><h1 class="mt-2 mb-4">Registrar nuevo plato</h1><div class="panel-card p-md-4"><form action="{{ route('dishes.store') }}" method="POST" enctype="multipart/form-data">@csrf @include('dishes._form',['dish'=>new \App\Models\Dish()])</form></div></div>
@endsection
