@extends('layouts.app')

@section('titulo', 'Crear cuenta')

@section('contenido')
    <h1 class="mb-5 font-titulo text-2xl font-bold">Crear cuenta</h1>

    <form method="POST" action="{{ route('registro') }}"
          class="grid max-w-md gap-3.5 rounded-tarjeta border border-linea bg-white p-6 shadow-tarjeta">
        @csrf

        <label class="grid gap-1">
            <span class="text-xs font-bold text-apagado">Nombre</span>
            <input name="name" value="{{ old('name') }}"
                   class="rounded-[9px] border px-3 py-2 {{ $errors->has('name') ? 'border-peligro bg-peligro-tenue' : 'border-linea' }}">
            @error('name') <span class="entra text-xs font-semibold text-peligro">{{ $message }}</span> @enderror
        </label>

        <label class="grid gap-1">
            <span class="text-xs font-bold text-apagado">Correo</span>
            <input name="email" type="email" value="{{ old('email') }}"
                   class="rounded-[9px] border px-3 py-2 {{ $errors->has('email') ? 'border-peligro bg-peligro-tenue' : 'border-linea' }}">
            @error('email') <span class="entra text-xs font-semibold text-peligro">{{ $message }}</span> @enderror
        </label>

        <label class="grid gap-1">
            <span class="text-xs font-bold text-apagado">Contraseña</span>
            <input name="password" type="password"
                   class="rounded-[9px] border px-3 py-2 {{ $errors->has('password') ? 'border-peligro bg-peligro-tenue' : 'border-linea' }}">
            @error('password') <span class="entra text-xs font-semibold text-peligro">{{ $message }}</span> @enderror
        </label>

        <label class="grid gap-1">
            <span class="text-xs font-bold text-apagado">Repite la contraseña</span>
            <input name="password_confirmation" type="password" class="rounded-[9px] border border-linea px-3 py-2">
        </label>

        <div class="mt-1 flex items-center gap-3">
            <button class="rounded-lg bg-primario px-4 py-2 text-sm font-semibold text-white hover:bg-primario-fuerte">
                Crear cuenta
            </button>
            <a href="{{ route('login') }}" class="text-sm font-semibold text-apagado hover:text-primario">Ya tengo cuenta</a>
        </div>
    </form>
@endsection
