<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DishController extends Controller
{
    public function catalog()
    {
        return view('dishes.catalog', ['dishes' => Dish::orderBy('name')->get()]);
    }

    public function index()
    {
        return view('dishes.index', ['dishes' => Dish::latest()->paginate(12)]);
    }

    public function create()
    {
        return view('dishes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(true));
        unset($data['image']);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('dishes', 'public');
        }

        try {
            Dish::create($data);
        } catch (\Throwable $exception) {
            if (isset($data['image_path'])) {
                Storage::disk('public')->delete($data['image_path']);
            }
            throw $exception;
        }

        return redirect()->route('dishes.index')->with('success', 'Plato registrado correctamente.');
    }

    public function edit(Dish $dish)
    {
        return view('dishes.edit', compact('dish'));
    }

    public function update(Request $request, Dish $dish)
    {
        // El archivo es obligatorio solamente si el plato todavía no tiene foto.
        // Si ya tiene una, el administrador puede conservarla sin volverla a subir.
        $data = $request->validate($this->rules(empty($dish->image_path)));
        unset($data['image']);

        $previousPath = $dish->image_path;
        $newPath = null;
        if ($request->hasFile('image')) {
            $newPath = $request->file('image')->store('dishes', 'public');
            $data['image_path'] = $newPath;
        }

        try {
            $dish->update($data);
        } catch (\Throwable $exception) {
            if ($newPath) {
                Storage::disk('public')->delete($newPath);
            }
            throw $exception;
        }

        if ($newPath && $previousPath && $previousPath !== $newPath) {
            Storage::disk('public')->delete($previousPath);
        }

        return redirect()->route('dishes.index')->with('success', 'Plato actualizado correctamente.');
    }

    public function destroy(Dish $dish)
    {
        if ($dish->orders()->exists()) {
            return back()->withErrors(['dish' => 'No puedes eliminar un plato asociado a pedidos existentes.']);
        }

        $imagePath = $dish->image_path;
        $dish->delete();
        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        return redirect()->route('dishes.index')->with('success', 'Plato eliminado correctamente.');
    }

    private function rules(bool $photoRequired): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'image' => [$photoRequired ? 'required' : 'nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ];
    }
}
