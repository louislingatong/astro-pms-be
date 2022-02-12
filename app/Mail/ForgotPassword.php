<?php

namespace App\Mail;

use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    /** @var PasswordReset */
    protected $passwordReset;

    /** @var string */
    public $view;

    /** @var string */
    protected $url;

    /** @var User */
    protected $user;

    /** @var string */
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PasswordReset $passwordReset)
    {
        $this->view = 'mail.password.forgot';
        $this->subject = 'Reset your Password';
        $this->user = $passwordReset->user;
        $this->url = env('APP_URL') . '/password/reset?token=' . $passwordReset->token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->markdown($this->view)
            ->with([
                'user' => $this->user,
                'url' => $this->url,
            ]);
    }
}
