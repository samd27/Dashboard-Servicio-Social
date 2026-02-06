<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Hemos enviado un código de verificación a tu correo. Ingrésalo para continuar.
    </div>

    <form method="POST" action="{{ route('verify.2fa.store') }}">
        @csrf

        <div>
            <x-input-label for="two_factor_code" value="Código de Seguridad" />

            <x-text-input id="two_factor_code" class="block mt-1 w-full" type="text" name="two_factor_code" required autofocus />

            <x-input-error :messages="$errors->get('two_factor_code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                Verificar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
