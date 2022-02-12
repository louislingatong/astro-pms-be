<?php

namespace App\Exceptions;

use Exception;

class UserNotCreatedException extends Exception
{
    /**
     * UserNotCreatedException constructor.
     * @param string $message
     */
    public function __construct($message = 'Unable to create user.')
    {
        parent::__construct($message);
    }
}
