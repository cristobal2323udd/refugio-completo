<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AnimalController extends Controller
{
    public function index(Request $request)
    {
        $animales = $request->user()->animales;

        return view('animales.index', ['animales' => $animales]);
    }

    public function create()
    {
        return view('animales.crear');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre'  => 'required|string|max:60',
            'edad'    => 'required|integer|min:0|max:30',
            'especie' => 'required|in:perro,gato,pajaro,conejo',
        ], [
            'nombre.required'  => 'El nombre es obligatorio.',
            'edad.required'    => 'La edad es obligatoria.',
            'edad.integer'     => 'La edad tiene que ser un número entero.',
            'especie.required' => 'Elige una especie.',
        ]);

        $request->user()->animales()->create($datos);

        return redirect()->route('animales.index')
            ->with('ok', "{$datos['nombre']} quedó registrado en el refugio.");
    }

    public function edit(Animal $animal)
    {
        Gate::authorize('update', $animal);

        return view('animales.editar', ['animal' => $animal]);
    }

    public function update(Request $request, Animal $animal)
    {
        Gate::authorize('update', $animal);

        $datos = $request->validate([
            'nombre'  => 'required|string|max:60',
            'edad'    => 'required|integer|min:0|max:30',
            'especie' => 'required|in:perro,gato,pajaro,conejo',
        ], [
            'nombre.required'  => 'El nombre es obligatorio.',
            'edad.required'    => 'La edad es obligatoria.',
            'edad.integer'     => 'La edad tiene que ser un número entero.',
            'especie.required' => 'Elige una especie.',
        ]);

        $animal->update($datos);

        return redirect()->route('animales.index')
            ->with('ok', "Se actualizaron los datos de {$animal->nombre}.");
    }

    public function adoptar(Animal $animal)
    {
        Gate::authorize('update', $animal);

        $animal->update(['adoptado' => true]);

        return redirect()->route('animales.index')
            ->with('ok', "{$animal->nombre} encontró familia.");
    }

    public function destroy(Animal $animal)
    {
        Gate::authorize('delete', $animal);

        $animal->delete();

        return redirect()->route('animales.index')
            ->with('ok', "Se eliminó la ficha de {$animal->nombre}.");
    }
}
