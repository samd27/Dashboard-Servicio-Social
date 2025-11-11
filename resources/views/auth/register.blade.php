<?php
?>
<x-guest-layout>
    <x-slot name="title">
        <h1 class="text-4xl font-bold text-gray-700 dark:text-gray-200">
            Registro
        </h1>
    </x-slot>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="nombre_cuenta" :value="__('Nombre de la Cuenta')" />
            <x-text-input id="nombre_cuenta" class="block mt-1 w-full" type="text" name="nombre_cuenta" :value="old('nombre_cuenta')" required autofocus autocomplete="organization" />
            <x-input-error :messages="$errors->get('nombre_cuenta')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email Principal (Usuario de Login)')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email_2_opcional" :value="__('Email Secundario (Opcional)')" />
            <x-text-input id="email_2_opcional" class="block mt-1 w-full" type="email" name="email_2_opcional" :value="old('email_2_opcional')" />
            <x-input-error :messages="$errors->get('email_2_opcional')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="telefono" :value="__('Teléfono')" />
            <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono')" required />
            <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="rfc" :value="__('RFC')" />
            <x-text-input id="rfc" class="block mt-1 w-full" type="text" name="rfc" :value="old('rfc')" required />
            <x-input-error :messages="$errors->get('rfc')" class="mt-2" />
        </div>


        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('¿Ya estás registrado?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Registrar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>