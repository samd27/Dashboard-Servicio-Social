<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-left ml-4">
            Panel de Control
        </h2>
    </x-slot>

    <script src="https://cdn.tiny.cloud/1/9ej5ywjfi1v3c7m4p84qq132v8tilc9igp509qq70mqjjj9u/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    
    <script>
        tinymce.init({
            selector: 'textarea.rich-editor', 
            height: 250, 
            menubar: false,
            plugins: 'code image media link lists table preview',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link',
            automatic_uploads: true,
            file_picker_types: 'image',
        });
    </script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('status') }}</p>
                </div>
            @endif

            {{-- GRID PRINCIPAL: 2 COLUMNAS (Izquierda: Config, Derecha: Noticia) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- COLUMNA IZQUIERDA: CONFIGURACIÓN --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-6 h-fit">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Configuración General (RF03)</h3>
                    
                    <form action="{{ route('admin.config.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Título del Sitio</label>
                            <input type="text" name="site_title" value="{{ $config->titulo_sitio }}" class="w-full border-gray-300 rounded shadow-sm">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Logo Actual</label>
                            <div class="flex items-center space-x-4">
                                @if($config->logo_path)
                                    <div class="bg-gray-100 p-2 rounded">
                                        <img src="{{ asset('storage/'. $config->logo_path) }}" class="h-12">
                                    </div>
                                @endif
                                <input type="file" name="logo" class="text-xs text-gray-500 file:mr-2 file:py-2 file:px-2 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Pie de Página (Footer HTML)</label>
                            <textarea name="footer_content" class="rich-editor w-full border-gray-300 rounded">{{ $config->footer_html }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                Guardar Config
                            </button>
                        </div>
                    </form>
                </div>

                {{-- COLUMNA DERECHA: PUBLICAR NOTICIA --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-6 h-fit">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Publicar Nueva Noticia (RF02)</h3>
                    
                    <form action="{{ route('admin.news.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Título</label>
                            <input type="text" name="title" class="w-full border-gray-300 rounded shadow-sm" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Contenido</label>
                            <textarea name="content" class="rich-editor"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Video de YouTube (URL)</label>
                            <input type="url" name="video_url" placeholder="https://www.youtube.com/watch?v=..." class="w-full border-gray-300 rounded shadow-sm">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded">
                                Publicar Noticia
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            {{-- HISTORIAL (OCUPA TODO EL ANCHO ABAJO) --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h4 class="font-bold mb-4">Historial de Noticias</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Título</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($news as $item)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $item->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-medium">
                                    {{ $item->title }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                    <form action="{{ route('admin.news.delete', $item->id) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres borrar esto?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>