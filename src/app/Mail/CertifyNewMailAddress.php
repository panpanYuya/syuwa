<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertifyNewMailAddress extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($certifyUrl)
    {
        $this->certifyUrl = $certifyUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.certify_new_password')
                ->from(config('app.MAIL_FROM_ADDRESS'))
                ->subject('メールアドレス認証')
                ->with('certifyUrl', $this->certifyUrl);
    }
}
