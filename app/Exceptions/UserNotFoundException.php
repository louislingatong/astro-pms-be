<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    /** @var string */
    public $errorType = 'user_not_found';

    /**
     * UserNotFoundException constructor.
     * @param string $message
     */
    public function __construct($message = 'Unable to retrieve user.')
    {
        parent::__construct($message);
    }
}
