<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordChange extends Mailable
{
    use Queueable, SerializesModels;

    /** @var string */
    public $view;
    /** @var string */
    public $subject;
    /** @var User */
    protected $user;
    /** @var string */
    protected $url;

    /**
     * Create a new message instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->view = 'mail.password.reset';
        $this->subject = 'Password Changed';
        $this->user = $user;
        $this->url = config('app.url') . '/sign-in';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->view($this->view)
            ->with([
                'user' => $this->user,
                'url' => $this->url,
            ]);
    }
}
