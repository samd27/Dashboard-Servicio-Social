<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Panel de Business Intelligence
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

            <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border-l-4 border-blue-500">
                <h3 class="text-2xl font-bold text-gray-800">¡Hola, {{ Auth::user()->name }}!</h3>
                <p class="text-gray-600 mt-1">Este es el resumen de métricas y rendimiento de tu MIPYME.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition">
                    <div class="text-sm font-bold text-gray-500 uppercase">Ingresos del Mes</div>
                    <div class="mt-2 text-3xl font-extrabold text-gray-900">{{ $bi_stats['ingresos_mes'] ?? '$0' }}</div>
                    <div class="mt-2 text-sm text-green-600 font-bold flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                        {{ $bi_stats['crecimiento'] ?? '0%' }} vs mes anterior
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition">
                    <div class="text-sm font-bold text-gray-500 uppercase">Nuevos Clientes</div>
                    <div class="mt-2 text-3xl font-extrabold text-gray-900">{{ $bi_stats['clientes_nuevos'] ?? '0' }}</div>
                    <div class="mt-2 text-sm text-gray-500 font-medium">En los últimos 30 días</div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition">
                    <div class="text-sm font-bold text-gray-500 uppercase">Productos Vendidos</div>
                    <div class="mt-2 text-3xl font-extrabold text-gray-900">{{ $bi_stats['productos_vendidos'] ?? '0' }}</div>
                    <div class="mt-2 text-sm text-gray-500 font-medium">Unidades totales</div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 flex flex-col justify-center items-center hover:shadow-md transition bg-gradient-to-br from-white to-gray-50">
                    <div class="text-sm font-bold text-gray-500 uppercase mb-3">Estado de la Cuenta</div>
                    <span class="px-4 py-1.5 bg-green-100 text-green-800 rounded-full text-sm font-bold uppercase tracking-wider shadow-sm border border-green-200">
                        Óptimo
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow-sm p-6 lg:col-span-2">
                    <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Tendencia de Ventas (Últimos 6 meses)</h4>
                    <div class="h-72 bg-gray-50 rounded border border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <p class="font-medium">[ Espacio reservado para Chart.js o ApexCharts ]</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Top Productos</h4>
                    <ul class="space-y-4 mt-4">
                        <li class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">1. Licencia Básica</span>
                            <span class="text-sm font-bold text-gray-900">145 uds.</span>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">2. Soporte Premium</span>
                            <span class="text-sm font-bold text-gray-900">89 uds.</span>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">3. Capacitación</span>
                            <span class="text-sm font-bold text-gray-900">34 uds.</span>
                        </li>
                    </ul>
                    <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                        <a href="#" class="text-sm font-bold text-blue-600 hover:text-blue-800">Ver reporte completo &rarr;</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
