@extends('layouts.app')

@section('titulo', 'Entrar')

@section('contenido')
    <h1 class="mb-5 font-titulo text-2xl font-bold">Entrar</h1>

    <form method="POST" action="{{ route('login') }}"
          class="grid max-w-md gap-3.5 rounded-tarjeta border border-linea bg-white p-6 shadow-tarjeta">
        @csrf

        <label class="grid gap-1">
            <span class="text-xs font-bold text-apagado">Correo</span>
            <input name="email" type="email" value="{{ old('email') }}"
                   class="rounded-[9px] border px-3 py-2 {{ $errors->has('email') ? 'border-peligro bg-peligro-tenue' : 'border-linea' }}">
            @error('email') <span class="entra text-xs font-semibold text-peligro">{{ $message }}</span> @enderror
        </label>

        <label class="grid gap-1">
            <span class="text-xs font-bold text-apagado">Contraseña</span>
            <input name="password" type="password" class="rounded-[9px] border border-linea px-3 py-2">
        </label>

        <div class="mt-1 flex items-center gap-3">
            <button class="rounded-lg bg-primario px-4 py-2 text-sm font-semibold text-white hover:bg-primario-fuerte">
                Entrar
            </button>
            <a href="{{ route('registro') }}" class="text-sm font-semibold text-apagado hover:text-primario">Crear una cuenta</a>
        </div>
    </form>

    {{-- Las dos cuentas que deja el seeder, a la vista para poder probar el
         refugio sin crear una cuenta primero. --}}
    <div class="mt-5 max-w-md rounded-tarjeta border border-linea bg-primario-suave p-5">
        <p class="font-titulo text-sm font-bold">Cuentas de prueba</p>

        <dl class="mt-3 grid gap-2">
            <div class="flex items-baseline justify-between gap-3">
                <dt class="font-mono text-sm">ana@refugio.cl</dt>
                <dd class="text-xs text-apagado">Firulais y Michi</dd>
            </div>
            <div class="flex items-baseline justify-between gap-3">
                <dt class="font-mono text-sm">beto@refugio.cl</dt>
                <dd class="text-xs text-apagado">Lola</dd>
            </div>
        </dl>

        <p class="mt-3 text-xs text-apagado">
            La contraseña de las dos es
            <span class="font-mono font-semibold text-tinta">refugio123</span>.
            Entra con una y después con la otra: cada una ve solo sus animales.
        </p>
    </div>
@endsection
