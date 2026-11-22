<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;

class DishController extends Controller
{
    // Vista del catálogo gastronómico (Clientes / Camareros)
    public function catalog()
    {
        $dishes = Dish::orderBy('name', 'asc')->get();
        return view('dishes.catalog', compact('dishes'));
    }

    // Listado administrativo de platos (CRUD - Consultar)
    public function index()
    {
        $dishes = Dish::orderBy('id', 'desc')->get();
        return view('dishes.index', compact('dishes'));
    }

    // Formulario de crear plato
    public function create()
    {
        return view('dishes.create');
    }

    // Guardar plato en BD (CRUD - Registrar)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0.01',
        ]);

        Dish::create($request->all());

        return redirect()->route('dishes.index')
            ->with('success', 'Plato registrado correctamente.');
    }

    // Formulario de editar plato
    public function edit(Dish $dish)
    {
        return view('dishes.edit', compact('dish'));
    }

    // Actualizar plato en BD (CRUD - Editar)
    public function update(Request $request, Dish $dish)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0.01',
        ]);

        $dish->update($request->all());

        return redirect()->route('dishes.index')
            ->with('success', 'Plato actualizado correctamente.');
    }

    // Eliminar plato de BD (CRUD - Eliminar)
    public function destroy(Dish $dish)
    {
        $dish->delete();

        return redirect()->route('dishes.index')
            ->with('success', 'Plato eliminado del menú.');
    }
}