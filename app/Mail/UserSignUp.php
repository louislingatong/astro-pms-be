<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserSignUp extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    public $view;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    public $subject;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $token
     * @return void
     */
    public function __construct(User $user, $token)
    {
        $this->view = 'mail.users.signup';
        $this->subject = 'Activate your Account';
        $this->user = $user;
        $this->url = env('APP_URL') . '/activate?token=' . $token;
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
