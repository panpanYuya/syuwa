<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistTmpUserMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userName, $registUrl)
    {
        $this->userName = $userName;
        $this->registUrl = $registUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.registTmpUser')
                ->from(config('app.MAIL_FROM_ADDRESS'))
                ->subject('仮登録を受付ました。')
                ->with('userName', $this->userName)
                ->with('registUrl', $this->registUrl);
    }
}
