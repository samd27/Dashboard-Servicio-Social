<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $config->titulo_sitio ?? 'Laravel' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans antialiased text-gray-900 flex flex-col min-h-screen">

    {{-- ENCABEZADO: Alineado a la izquierda --}}
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center justify-start"> {{-- justify-start fuerza izquierda --}}
                    @if (isset($config->logo_path))
                        <img src="{{ asset('storage/' . $config->logo_path) }}" class="h-12 w-auto mr-4">
                    @endif

                    <span class="font-bold text-2xl text-blue-700 tracking-tight">
                        {{ $config->titulo_sitio ?? 'Mi Sitio Web' }}
                    </span>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="group flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">
                            <div
                                class="p-1.5 bg-gray-100 rounded-full group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <span>Mi Panel</span>
                        </a>

                        <div class="h-4 w-px bg-gray-300"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center gap-1 text-sm font-medium text-gray-500 hover:text-red-600 transition-colors"
                                title="Cerrar Sesión">
                                <span>Salir</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm">
                            Iniciar Sesión
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">

        <h1 class="text-3xl font-bold mb-8 text-gray-900 border-b pb-4">Últimas Noticias</h1>

        @if ($news->isEmpty())
            <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
                No hay noticias publicadas aún.
            </div>
        @else
            {{-- AQUÍ ESTÁ EL CAMBIO: Grid de 2 columnas --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach ($news as $item)
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 flex flex-col h-full">

                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $item->title }}</h2>

                        <p class="text-xs text-gray-400 mb-4 uppercase tracking-wide">
                            {{ $item->created_at->format('d/m/Y') }}
                        </p>

                        <div class="prose max-w-none text-gray-700 mb-6 flex-grow">
                            {!! $item->content !!}
                        </div>

                        @if ($item->video_url)
                            <div class="mt-auto">
                                <?php
                                $videoID = '';
                                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $item->video_url, $matches)) {
                                    $videoID = $matches[1];
                                }
                                ?>
                                @if ($videoID)
                                    <div class="w-full bg-black rounded-lg overflow-hidden shadow-md">
                                        <div class="relative pb-[56.25%] h-0">
                                            <iframe src="https://www.youtube.com/embed/{{ $videoID }}"
                                                class="absolute top-0 left-0 w-full h-full" frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen>
                                            </iframe>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    {{-- PIE DE PÁGINA --}}
    <footer class="bg-gray-900 text-white mt-auto border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 py-8 text-center">
            <div class="text-sm text-gray-300">
                {!! $config->footer_html ?? '© 2026 Todos los derechos reservados.' !!}
            </div>
        </div>
    </footer>

</body>

</html>
