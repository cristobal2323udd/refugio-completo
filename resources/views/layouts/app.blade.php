{{-- El marco que comparten todas las páginas. Es el mismo layout de la clase 9,
     con el menú apuntando a las rutas del refugio. --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Refugio')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-lienzo font-sans text-tinta antialiased">

    <nav class="border-b border-linea bg-white">
        <div class="mx-auto flex max-w-3xl items-center gap-6 px-6 py-4">
            <a href="{{ route('animales.index') }}" class="font-titulo text-lg font-bold">
                Refugio
            </a>

            <span class="ml-auto flex items-center gap-5 text-sm font-semibold text-apagado">
                {{-- @auth se dibuja solo si hay sesión; @guest solo si no la hay. --}}
                @auth
                    <a href="{{ route('animales.index') }}" class="hover:text-primario">Los animales</a>
                    <a href="{{ route('animales.create') }}" class="hover:text-primario">Agregar</a>

                    <span class="text-tinta">{{ auth()->user()->name }}</span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="font-semibold text-apagado hover:text-primario">Salir</button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="hover:text-primario">Entrar</a>
                    <a href="{{ route('registro') }}" class="hover:text-primario">Crear cuenta</a>
                @endguest
            </span>
        </div>
    </nav>

    <main class="mx-auto max-w-3xl px-6 py-10">
        {{-- El mensaje que dejan los redirect()->with('ok', ...) del controlador.
             Se muestra una sola vez y después desaparece solo. --}}
        @if (session('ok'))
            <p class="entra mb-4 flex items-center gap-2 rounded-[10px] border border-exito-linea bg-exito-suave px-3.5 py-2.5 text-sm font-semibold text-exito">
                <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20 6 9 17l-5-5" />
                </svg>
                {{ session('ok') }}
            </p>
        @endif

        @yield('contenido')
    </main>

    <footer class="mx-auto max-w-3xl px-6 pb-10 text-sm text-apagado">
        IIP323W · 2026
    </footer>
</body>
</html>
