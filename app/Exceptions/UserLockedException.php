<?php

namespace App\Exceptions;

use Exception;

class UserLockedException extends Exception
{
    /** @var string */
    public $errorType = 'user_locked';

    /**
     * UserLockedException constructor.
     * @param string $message
     */
    public function __construct($message = 'Your account has been locked out, please reset your password.')
    {
        parent::__construct($message);
    }
}
