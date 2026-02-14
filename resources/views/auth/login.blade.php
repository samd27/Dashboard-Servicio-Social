<x-guest-layout>
    <x-slot name="title">
        <h1 class="text-4xl font-bold text-gray-700 dark:text-gray-200">
            Iniciar Sesión
        </h1>
    </x-slot>

    @if (session('status'))
        <div x-data="{ show: true }"
             x-show="show"
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm"
             style="display: none;">
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md mx-4 border border-purple-200 dark:border-purple-900">
                
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-purple-700 dark:text-purple-400">
                        ¡Atención!
                    </h2>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        &times;
                    </button>
                </div>

                <div class="text-gray-600 dark:text-gray-300 mb-6">
                    {{ session('status') }}
                </div>

                <div class="flex justify-end">
                    <button @click="show = false" 
                            class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md font-semibold text-sm transition ease-in-out duration-150">
                        Entendido
                    </button>
                </div>
            </div>
        </div>
    @endif

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Recuérdame') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4 gap-4">
            
            {{-- NUEVO: Enlace para Registrarse --}}
            <a class="underline text-sm text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('register') }}">
                {{ __('¿No tienes cuenta?') }}
            </a>

            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Iniciar Sesión') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>