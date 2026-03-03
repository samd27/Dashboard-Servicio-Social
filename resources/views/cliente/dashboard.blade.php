<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mi Portal de Cliente
            </h2>

            {{-- BOTONES DE NAVEGACIÓN --}}
            <div class="flex items-center gap-4">
                {{-- Ir a la página principal --}}
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

                {{-- Cerrar Sesión --}}
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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-indigo-500 flex items-center gap-4">
                <div class="bg-indigo-100 p-3 rounded-full text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">¡Bienvenido, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600">Desde aquí puedes consultar tu historial y contactar a soporte.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h4 class="text-lg font-bold text-gray-800">Mis Servicios Activos</h4>
                        <span class="text-xs font-bold bg-gray-100 text-gray-600 px-2 py-1 rounded">Últimos 30 días</span>
                    </div>

                    @if(empty($historial))
                        <div class="text-center py-8 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p>Aún no tienes servicios o compras registradas.</p>
                        </div>
                    @else
                        @endif
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Centro de Ayuda</h4>

                    <div class="space-y-4">
                        <div class="p-4 bg-gray-50 rounded border border-gray-100 hover:border-blue-300 transition cursor-pointer">
                            <h5 class="font-bold text-blue-600">📖 Manual de Usuario</h5>
                            <p class="text-sm text-gray-600 mt-1">Descarga la guía paso a paso para utilizar nuestra plataforma.</p>
                        </div>

                        <div class="p-4 bg-gray-50 rounded border border-gray-100 hover:border-blue-300 transition cursor-pointer">
                            <h5 class="font-bold text-blue-600">✉️ Levantar un Ticket</h5>
                            <p class="text-sm text-gray-600 mt-1">¿Tienes algún problema técnico? Contáctanos directamente.</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
