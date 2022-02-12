<?php

namespace App\Exceptions;

use Exception;

class UserPendingException extends Exception
{
    /** @var string */
    public $errorType = 'user_pending';

    /**
     * UserPendingException constructor.
     * @param string $message
     */
    public function __construct($message = 'Email has not been verified.')
    {
        parent::__construct($message);
    }
}
