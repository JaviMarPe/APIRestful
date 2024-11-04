<x-mail::message>
# Hola {{$user->name}}

Has cambiado tu correo electronico. Por favor verifica la nueva direccion usando el siguiente boton: 

<x-mail::button :url="route('verify', ['token'=>$user->verification_token])">
Confirmar mi cuenta
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
