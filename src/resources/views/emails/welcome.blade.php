<x-mail::message>
# Hola {{$user->name}}

Gracias por crear una cuenta. Por favor verificala usando el siguiente boton: 

<x-mail::button :url="route('verify', ['token'=>$user->verification_token])">
Confirmar mi cuenta
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>