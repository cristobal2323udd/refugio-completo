<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarAnimalRequest;
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

    public function store(GuardarAnimalRequest $request)
    {
        $datos = $request->validated();

        $request->user()->animales()->create($datos);

        return redirect()->route('animales.index')
            ->with('ok', "{$datos['nombre']} quedó registrado en el refugio.");
    }

    public function edit(Animal $animal)
    {
        Gate::authorize('update', $animal);

        return view('animales.editar', ['animal' => $animal]);
    }

    public function update(GuardarAnimalRequest $request, Animal $animal)
    {
        Gate::authorize('update', $animal);

        $animal->update($request->validated());

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
