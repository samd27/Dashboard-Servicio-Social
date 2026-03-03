<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Panel de Administración General
            </h2>

            {{-- BOTONES DE NAVEGACIÓN --}}
            <div class="flex items-center gap-4">

                {{-- 1. Ir a la página principal (Estilo Icono Circular) --}}
                <a href="{{ url('/') }}"
                    class="group flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">
                    <div
                        class="p-1.5 bg-gray-100 rounded-full group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <span>Ir al Inicio</span>
                </a>

                {{-- Separador Vertical --}}
                <div class="h-6 w-px bg-gray-300 mx-1"></div>

                {{-- 2. Cerrar Sesión --}}
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-2 text-sm font-bold text-red-600 hover:text-red-800 border border-red-200 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded transition shadow-sm hover:shadow">
                        <span>Cerrar Sesión</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    {{-- SCRIPTS DE TINYMCE PARA EL EDITOR DE TEXTO --}}
    <script src="https://cdn.tiny.cloud/1/9ej5ywjfi1v3c7m4p84qq132v8tilc9igp509qq70mqjjj9u/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>

    <script>
        tinymce.init({
            selector: 'textarea.rich-editor',
            height: 250,
            menubar: false,
            plugins: 'code image media link lists table preview',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link',
            extended_valid_elements: "iframe[src|frameborder|style|scrolling|class|width|height|name|align|allowfullscreen],script[src|type],div[*],p[*],span[*]",
            automatic_uploads: true,
            file_picker_types: 'image',
        });
    </script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- MENSAJES DE ÉXITO GLOBALES --}}
            @if (session('status'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">{{ session('status') }}</p>
                </div>
            @endif

            {{-- GRID PRINCIPAL: CONFIGURACIÓN Y NOTICIAS --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- COLUMNA IZQUIERDA: CONFIGURACIÓN --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-6 h-fit border-t-4 border-indigo-500">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Configuración General (RF03)</h3>

                    <form action="{{ route('admin.config.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Título del Sitio</label>
                            <input type="text" name="site_title" value="{{ $config->titulo_sitio }}"
                                class="w-full border-gray-300 rounded shadow-sm">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Logo Actual</label>
                            <div class="flex items-center space-x-4">
                                @if ($config->logo_path)
                                    <div class="bg-gray-100 p-2 rounded">
                                        <img src="{{ asset('storage/' . $config->logo_path) }}" class="h-12">
                                    </div>
                                @endif
                                <input type="file" accept="image/*" name="logo"
                                    class="text-xs text-gray-500 file:mr-2 file:py-2 file:px-2 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Pie de Página (Footer HTML)</label>
                            <textarea name="footer_content" class="rich-editor w-full border-gray-300 rounded">{{ $config->footer_html }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow">
                                Guardar Configuración
                            </button>
                        </div>
                    </form>
                </div>

                {{-- COLUMNA DERECHA: PUBLICAR NOTICIA --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-6 h-fit border-t-4 border-green-500">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Publicar Nueva Noticia (RF02)</h3>

                    <form action="{{ route('admin.news.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Título</label>
                            <input type="text" name="title" class="w-full border-gray-300 rounded shadow-sm"
                                required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Contenido</label>
                            <textarea name="content" class="rich-editor"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Video de YouTube (URL)</label>
                            <input type="url" name="video_url" placeholder="https://www.youtube.com/watch?v=..."
                                class="w-full border-gray-300 rounded shadow-sm">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow">
                                Publicar Noticia
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            {{-- HISTORIAL DE NOTICIAS --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mt-6">
                <h4 class="font-bold mb-4 text-gray-800">Historial de Noticias</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Título
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Acción
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($news as $item)
                                <tr>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        {{ $item->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-medium">
                                        {{ $item->title }}
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                        <form action="{{ route('admin.news.delete', $item->id) }}" method="POST"
                                            onsubmit="return confirm('¿Seguro que quieres borrar esto?');">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 font-bold">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ADMINISTRACIÓN DE USUARIOS --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6 border-t-4 border-blue-500">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Gestión de Usuarios</h3>

                    <div class="bg-gray-50 p-4 rounded mb-6 border">
                        <h4 class="font-semibold text-sm mb-2 text-gray-700">Registrar Nuevo Usuario</h4>

                        <form action="{{ route('admin.users.store') }}" method="POST"
                            class="flex flex-wrap gap-2 items-end" autocomplete="off">
                            @csrf

                            <div class="flex-1 min-w-[150px]">
                                <label class="text-xs font-bold text-gray-600">Nombre</label>
                                <input type="text" name="name" required
                                    class="w-full text-sm rounded border-gray-300">
                            </div>

                            <div class="flex-1 min-w-[150px]">
                                <label class="text-xs font-bold text-gray-600">Email</label>
                                <input type="email" name="email" required
                                    class="w-full text-sm rounded border-gray-300" autocomplete="off">
                            </div>

                            <div class="flex-1 min-w-[150px]">
                                <label class="text-xs font-bold text-gray-600">Contraseña</label>
                                <input type="password" name="password" required
                                    class="w-full text-sm rounded border-gray-300" autocomplete="new-password">
                            </div>

                            <div class="w-32">
                                <label class="text-xs font-bold text-gray-600">Rol</label>
                                <select name="nivel_usuario" class="w-full text-sm rounded border-gray-300 bg-white">
                                    <option value="1">Distribuidor (1)</option>
                                    <option value="2">Cliente (2)</option>
                                    <option value="0">Admin (0)</option>
                                </select>
                            </div>

                            <button type="submit"
                                class="bg-blue-600 text-white text-sm font-bold py-2 px-4 rounded shadow hover:bg-blue-800 transition">
                                Crear Usuario
                            </button>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm whitespace-nowrap">
                            <thead class="uppercase tracking-wider border-b-2 border-gray-200 bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Nombre</th>
                                    <th scope="col" class="px-6 py-4">Rol</th>
                                    <th scope="col" class="px-6 py-4">Estado</th>
                                    <th scope="col" class="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $u)
                                    <tr class="border-b hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-900">{{ $u->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $u->email }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($u->nivel_usuario == 0)
                                                <span class="text-purple-600 font-bold bg-purple-50 px-2 py-1 rounded">Admin</span>
                                            @elseif($u->nivel_usuario == 1)
                                                <span class="text-blue-600 font-bold bg-blue-50 px-2 py-1 rounded">Distribuidor</span>
                                            @else
                                                <span class="text-gray-600 font-bold bg-gray-100 px-2 py-1 rounded">Cliente</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($u->vigente)
                                                <span
                                                    class="bg-green-100 text-green-800 text-xs font-bold mr-2 px-2.5 py-1 rounded-full">Vigente</span>
                                            @else
                                                <span
                                                    class="bg-red-100 text-red-800 text-xs font-bold mr-2 px-2.5 py-1 rounded-full">Baja</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right flex justify-end gap-3 items-center">

                                            <form action="{{ route('admin.users.toggle', $u->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="text-xs font-bold px-3 py-1.5 rounded transition shadow-sm {{ $u->vigente ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                                    {{ $u->vigente ? 'Desactivar' : 'Activar' }}
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.users.delete', $u->id) }}" method="POST"
                                                onsubmit="return confirm('¿Borrar usuario permanentemente? Esta acción no se puede deshacer.');">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-700 font-bold text-lg hover:bg-red-50 px-2 rounded" title="Eliminar Permanente">×</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if ($users->isEmpty())
                            <p class="text-center text-gray-500 py-6 font-medium">No hay otros usuarios registrados en el sistema.</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
