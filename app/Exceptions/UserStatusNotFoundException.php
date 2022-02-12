<?php

namespace App\Exceptions;

use Exception;

class UserStatusNotFoundException extends Exception
{
    /**
     * UserStatusNotFoundException constructor.
     * @param string $message
     */
    public function __construct($message = 'Unable to retrieve status.')
    {
        parent::__construct($message);
    }
}
