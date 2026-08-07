@extends('layouts.app')

@section('titulo', 'Refugio')

@section('contenido')
    <div class="mb-5 flex items-center justify-between gap-3">
        <h1 class="font-titulo text-2xl font-bold">Refugio</h1>

        <a href="{{ route('animales.create') }}"
           class="inline-flex items-center gap-1.5 rounded-lg bg-primario px-3.5 py-2 text-sm font-semibold text-white hover:bg-primario-fuerte">
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M5 12h14M12 5v14" />
            </svg>
            Agregar animal
        </a>
    </div>

    @if ($animales->isEmpty())
        <p class="rounded-tarjeta border border-linea bg-white px-6 py-12 text-center text-apagado italic shadow-tarjeta">
            Todavía no hay animales en el refugio.
        </p>
    @else
        <div class="overflow-hidden rounded-tarjeta border border-linea bg-white shadow-tarjeta">
            <table class="w-full text-sm">
                @foreach ($animales as $animal)
                    <tr class="border-t border-linea first:border-t-0 {{ $animal->adoptado ? 'opacity-60' : '' }}">
                        <td class="w-10 py-3 pl-4">
                            <x-icono-especie :especie="$animal->especie" />
                        </td>

                        <td class="py-3 pr-3 font-bold">{{ $animal->nombre }}</td>
                        <td class="py-3 pr-3 text-apagado">{{ $animal->especie }}</td>
                        <td class="py-3 pr-3 text-apagado">{{ $animal->edad }} años</td>

                        <td class="py-3 pr-3">
                            @if ($animal->adoptado)
                                <span class="rounded-full bg-exito-suave px-2.5 py-1 text-xs font-bold text-exito">Adoptado</span>
                            @else
                                <span class="rounded-full bg-primario-suave px-2.5 py-1 text-xs font-bold text-primario-fuerte">En el refugio</span>
                            @endif
                        </td>

                        <td class="py-3 pr-4">
                            <div class="flex justify-end gap-1.5">
                                @can('update', $animal)
                                    <a href="{{ route('animales.edit', $animal) }}"
                                       class="rounded-lg border border-linea px-2.5 py-1.5 text-xs font-semibold hover:border-primario hover:bg-primario-suave">
                                        Editar
                                    </a>
                                @endcan

                                {{-- El botón desaparece si ya está adoptado. --}}
                                @can('update', $animal)
                                    @unless ($animal->adoptado)
                                        <form method="POST" action="{{ route('animales.adoptar', $animal) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button class="rounded-lg border border-linea px-2.5 py-1.5 text-xs font-semibold hover:border-primario hover:bg-primario-suave">
                                                Adoptar
                                            </button>
                                        </form>
                                    @endunless
                                @endcan

                                @can('delete', $animal)
                                    <form method="POST" action="{{ route('animales.destroy', $animal) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button onclick="return confirm('¿Eliminar la ficha?')"
                                                class="rounded-lg border border-peligro-linea px-2.5 py-1.5 text-xs font-semibold text-peligro hover:bg-peligro-suave">
                                            Eliminar
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    @endif
@endsection
