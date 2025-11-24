<x-mail::message>
# ¡Bienvenido, {{ $user->name }}!

Tu registro en la plataforma ha sido exitoso. A continuación te presentamos los detalles de tu cuenta:

## Datos de la Cuenta
* **Organización:** {{ $cuenta->nombre_cuenta }}
* **RFC:** {{ $cuenta->rfc }}
* **Teléfono:** {{ $cuenta->telefono }}

## Datos de Acceso
* **Usuario:** {{ $user->email }}
* **API Key:** `{{ $user->api_key }}`

---

### Documentación Adjunta
Como parte de tu registro, hemos adjuntado a este correo:
1. **Manual del Panel:** Para aprender a usar el sistema.
2. **Guía Técnica API:** Para integrar tus sistemas con nuestra API.

Si tienes dudas, contáctanos.

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>