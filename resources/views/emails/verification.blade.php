@component('mail::message')
# Verificação de Email

Olá,

Obrigado por se registrar! Use o código abaixo para verificar seu email:

@component('mail::panel')
**{{ $token }}**
@endcomponent

Se você não realizou essa solicitação, ignore este email.

Obrigado,<br>
{{ config('app.name') }}
@endcomponent