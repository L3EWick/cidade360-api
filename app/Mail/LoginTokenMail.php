<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginTokenMail extends Mailable
{
    use Queueable, SerializesModels;

    public $loginToken;

    public function __construct($loginToken)
    {
        $this->loginToken = $loginToken;
    }

    public function build()
    {
        return $this->subject("{$this->loginToken} é o eu token para login no Cidade360")
                    ->markdown('emails.login_token')
                    ->with(['loginToken' => $this->loginToken]);
    }
}