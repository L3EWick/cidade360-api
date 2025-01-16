@component('mail::message')
# Token de Login

Olá,

Use o token abaixo para acessar sua conta no Cidade360:

@component('mail::panel')
**{{ $loginToken }}**
@endcomponent

Se você não solicitou este login, ignore esta mensagem.

Obrigado,<br>
{{ config('app.name') }}
@endcomponent