@extends('layouts.app')

@section('titulo', 'Editar a ' . $animal->nombre)

@section('contenido')
    <h1 class="mb-5 font-titulo text-2xl font-bold">Editar a {{ $animal->nombre }}</h1>

    <form method="POST" action="{{ route('animales.update', $animal) }}"
          class="grid max-w-md gap-3.5 rounded-tarjeta border border-linea bg-white p-6 shadow-tarjeta">
        @csrf
        {{-- Un formulario HTML solo sabe hacer GET y POST. Este campo escondido
             le dice a Laravel cuál es el verbo de verdad. --}}
        @method('PUT')

        <label class="grid gap-1">
            <span class="text-xs font-bold text-apagado">Nombre</span>
            <input name="nombre" value="{{ old('nombre', $animal->nombre) }}"
                   class="rounded-[9px] border px-3 py-2 focus-visible:outline-2 focus-visible:outline-primario {{ $errors->has('nombre') ? 'border-peligro bg-peligro-tenue' : 'border-linea' }}">
            @error('nombre') <span class="entra text-xs font-semibold text-peligro">{{ $message }}</span> @enderror
        </label>

        <label class="grid gap-1">
            <span class="text-xs font-bold text-apagado">Edad</span>
            <input name="edad" value="{{ old('edad', $animal->edad) }}"
                   class="rounded-[9px] border px-3 py-2 focus-visible:outline-2 focus-visible:outline-primario {{ $errors->has('edad') ? 'border-peligro bg-peligro-tenue' : 'border-linea' }}">
            @error('edad') <span class="entra text-xs font-semibold text-peligro">{{ $message }}</span> @enderror
        </label>

        {{-- Este campo tiene que ir: update() valida las mismas reglas que store(),
             y ahí especie es obligatoria. --}}
        <label class="grid gap-1">
            <span class="text-xs font-bold text-apagado">Especie</span>
            <select name="especie"
                    class="rounded-[9px] border px-3 py-2 focus-visible:outline-2 focus-visible:outline-primario {{ $errors->has('especie') ? 'border-peligro bg-peligro-tenue' : 'border-linea' }}">
                <option value="perro" @selected(old('especie', $animal->especie) === 'perro')>perro</option>
                <option value="gato" @selected(old('especie', $animal->especie) === 'gato')>gato</option>
                <option value="pajaro" @selected(old('especie', $animal->especie) === 'pajaro')>pájaro</option>
                <option value="conejo" @selected(old('especie', $animal->especie) === 'conejo')>conejo</option>
            </select>
            @error('especie') <span class="entra text-xs font-semibold text-peligro">{{ $message }}</span> @enderror
        </label>

        <div class="mt-1 flex gap-2">
            <button class="rounded-lg bg-primario px-4 py-2 text-sm font-semibold text-white hover:bg-primario-fuerte">
                Guardar cambios
            </button>

            <a href="{{ route('animales.index') }}"
               class="rounded-lg border border-linea px-4 py-2 text-sm font-semibold hover:border-primario hover:bg-primario-suave">
                Cancelar
            </a>
        </div>
    </form>
@endsection
